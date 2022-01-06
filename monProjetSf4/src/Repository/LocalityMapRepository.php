<?php

namespace App\Repository;

use App\Entity\LocalityMap;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LocalityMap|null find($id, $lockMode = null, $lockVersion = null)
 * @method LocalityMap|null findOneBy(array $criteria, array $orderBy = null)
 * @method LocalityMap[]    findAll()
 * @method LocalityMap[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LocalityMapRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LocalityMap::class);
    }

    // /**
    //  * @return LocalityMap[] Returns an array of LocalityMap objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?LocalityMap
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
