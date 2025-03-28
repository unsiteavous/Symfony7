<?php

namespace App\Repository;

use App\Entity\Film;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Film>
 */
class FilmRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Film::class);
    }

//    /**
//     * @return Film[] Returns an array of Film objects
//     */
//    public function findByTitre($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.titre = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Film
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    /**
     * Fonction qui me permet de récupérer les films qui sont supérieurs à la durée indiquée
     *
     * @param string $duration La durée indiquée avec ce format : 00:00 (heures:minutes)
     * @return array
     */
    public function findAllFilmsWithDurationGreaterThan(string $duration): array
    {
        $duration = new \DateTime('1970-01-01 '.$duration.':00');

        return $this->createQueryBuilder('film')
            ->where('film.duree > :duration')
            ->orderBy('film.duree', 'ASC')
            ->setParameter(':duration', $duration)
            ->getQuery()
            ->getResult();
    }

    /**
     * Fonction qui me permet de récupérer les films qui sont sortis il y a moins d'un mois
     *
     * @return array
     */
    public function findAllFilmsWithDateGreaterThanOneMonth(): array
    {
        $date = new \DateTime('now - 1 month');

        return $this->createQueryBuilder('film')
            ->where('film.dateSortie > :date')
            ->orderBy('film.dateSortie', 'ASC')
            ->setParameter(':date', $date)
            ->getQuery()
            ->getResult();
    }
}
