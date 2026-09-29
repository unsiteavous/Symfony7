# Cours 12 : Fixtures

Lorsque nous travaillons sur un projet qui nécessite d'avoir des données d'exemple, pour pouvoir voir si nos routes marchent, si on affiche correctement les données, remplir les tableaux, ... Nous avons deux choix : Soit nous remplissons notre BDD à la main, soit on utilise les fixtures.

Les fixtures sont des fausses données qui ressemblent à des vraies, juste pour l'exemple. Vous en utilisez une depuis le début : lorem ipsum.

## Installer les paquets
Pour commencer, on doit installer les fixtures, qui ne sont pas fournies par défaut dans Symfony. Comme c'est une librairie que nous utiliserons qu'en dev, on ajoute le drapeau `--dev` dans notre commande. Cela permettra que lorsqu'on passera en prod, les fixtures ne seront pas installées.

```bash
composer require --dev orm-fixtures
```
Cela va nous créer un nouveau dossier dataFixtures, avec un fichier AppFixtures près à être modifié.
On peut avoir plusieurs fichiers, qu'il faudra créer à la main. Il est même souhaitable, plutôt que d'en avoir un seul immense.

Maintenant, nous voulons mettre dans nos fixtures des fausses données, et pour ça nous allons utiliser `fakerphp` :

```bash
composer require --dev fakerphp/faker
```

## Construire une fixture

Pour construire une fixture, on va modifier (ou ajouter) un fichier dans notre dossier `DataFixtures`.

Nous allons faire celui pour les films : Modifions le AppFixtures, et ajoutons les dépendances nécessaires.

```php
<?php

namespace App\DataFixtures;

use App\Entity\Film;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class FilmFixtures extends Fixture
{
    protected $faker;
    
    public function __construct()
    {
      // On instancie Faker en langue française :
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {

      // Ici, on fait une boucle pour créer 10 films :

        for ($i=0; $i < 10; $i++) { 
            $film = new Film;
            $film->setTitre($this->faker->sentence())
            ->setDuree($this->faker->dateTime())
            ->setDateSortie($this->faker->dateTime())
            ->setUrlAffiche($this->faker->url());

            $manager->persist($film);
        }

        $manager->flush();
    }
}
```

Pour tester si ça marche, on va lancer une commande.

## Exécution des fixtures
Et maintenant, il ne nous reste plus qu'à exécuter ce code, pour remplir la base de données :

```bash
# vide toute la base avant de la reremplir
symfony console doctrine:fixtures:load 
# ajoute les données à la base, sans la vider.
symfony console doctrine:fixtures:load --append 
```

## Fixtures avec relation entre entités
Si on veut associer notre film aux classification, on va avoir besoin de spécifier une dépendance entre les deux fixtures. Cela va se faire grâce à l'implémentation de `DependentFixtureInterface`, qu'on ajoute à la déclaration de la classe :

```php
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class FilmFixtures extends Fixture implements DependentFixtureInterface
{
  
  // ...

  public function getDependencies()
    {
        
    }

}
  
```

Pour la petite histoire, une `interface` est un fichier qui va vous forcer à avoir une ou plusieurs méthodes dans votre classe. Cela permet de s'assurer que toutes les classes qui implémentent une interface auront toujours les méthodes attendues. Ici, la méthode obligatoire est `getDependencies()`.

### Création de la classe ClassificationFixtures

Nous allons commencer par créer la classe ClassificationFixtures, comme les autres. 

dans cette classe, nous allons commencer par nous faire une fonction statique qui nous renverra le tableau des intitulés des classification que nous voudrions voir en BDD. 

```php
public static function getClassificationsArray(){
  return ['Tout public', 'Interdit aux - de 12 ans', 'Interdit aux - de 16 ans', 'Interdit aux - de 18 ans'];
}
```

ensuite, il va falloir préciser qu'on ajoute une référence depuis chaque classification, et qu'on pourra retrouver cette référence grâce à tel ou tel champ de l'objet (son id, son intitulé, ...).

```php
$this->addReference($classification->getIntitule(), $classification);
```
<details>
<summary>Voir le code du fichier en entier</summary>

```php
<?php

namespace App\DataFixtures;

use App\Entity\Classification;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ClassificationFixtures extends Fixture
{
    protected $faker;
    
    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {


        for ($i=0; $i < sizeof(self::getClassificationsArray()); $i++) { 
            $classification = new Classification;
            $classification->setIntitule(self::getClassificationsArray()[$i])
            ->setAvertissement($this->faker->sentence());

            $manager->persist($classification);

            $this->addReference($classification->getIntitule(), $classification);
        }

        $manager->flush();
    }

    public static function getClassificationsArray(){
      return ['Tout public', 'Interdit aux - de 12 ans', 'Interdit aux - de 16 ans', 'Interdit aux - de 18 ans'];
    }
}

```

</details>

### Modification de notre classe FilmFixtures

On va commencer par lui dire de quelle autre fixture notre film dépend, en ajoutant ceci dans la méthode `getDependencies()` :

```php
public function getDependencies()
  {
      return [ClassificationFixtures::class];
  }
```
Ensuite, on va ajouter le champ classification, et on va aller chercher la référence à celle qu'on veut, de manière aléatoire, grâce à faker :

```php
$film->setClassification($this->getReference($this->faker->randomElement(ClassificationFixtures::getClassificationsArray())))
```
<details>
<summary>Voir le code du fichier en entier</summary>

```php
<?php

namespace App\DataFixtures;

use App\Entity\Film;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class FilmFixtures extends Fixture implements DependentFixtureInterface
{
    protected $faker;
    
    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {

        for ($i=0; $i < 10; $i++) { 
            $film = new Film;
            $film->setTitre($this->faker->sentence())
            ->setDuree($this->faker->dateTime())
            ->setDateSortie($this->faker->dateTime())
            ->setUrlAffiche($this->faker->url())
            ->setClassification($this->getReference($this->faker->randomElement(ClassificationFixtures::getClassificationsArray())));

            $manager->persist($film);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [ClassificationFixtures::class];
    }
}
```
</details>

Si maintenant on refait les commandes de load, on voit que dans notre base de données, on a bien enregistré toutes nos classifications et tous nos films avec leurs associations ! 


## Champ unique ?
Nos titres de films doivent être uniques. Or, comme nous faisons une insertion de manière aléatoire, même s'il y a peu de chances que ça arrive, il n'est pas exclu que le programme nous mette deux fois le même titre. Et là c'est le drame.

Pour éviter ça, on peut spécifier à faker que le champ est unique : 

```php
$film->setTitre($this->faker->unique()->sentence());
```

[📜 Documentation de Faker](https://fakerphp.github.io/)



## Activité
Aller à [l'activité 9](<21 Activité 9.md>).