<?php
namespace App\Service;

use Doctrine\ORM\Event\LoadClassMetadataEventArgs;

class DatabaseTablePrefix
{
    private $prefix;

    public function __construct(string $prefix)
    {
        $this->prefix = $prefix;
    }

    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
  {
    $classMetadata = $eventArgs->getClassMetadata();

    // Première chose à faire, on change le nom de l'entité en lui ajoutant le préfixe.
    if (!$classMetadata->isInheritanceTypeSingleTable() || $classMetadata->getName() === $classMetadata->rootEntityName) {
      $classMetadata->setPrimaryTable([
        'name' => $this->prefix . $classMetadata->getTableName()
      ]);
    }

    // Deuxième chose à faire, on vérifie s'il y a des associations entre entités, pour changer le nom de l'entité appelée aussi.
    foreach ($classMetadata->getAssociationMappings() as $fieldName => $mapping) {
      if ($mapping['type'] == \Doctrine\ORM\Mapping\ClassMetadata::MANY_TO_MANY && $mapping['isOwningSide']) {
        $mappedTableName = $mapping['joinTable']['name'];
        $classMetadata->associationMappings[$fieldName]['joinTable']['name'] = $this->prefix . $mappedTableName;
      }
    }
  }
}