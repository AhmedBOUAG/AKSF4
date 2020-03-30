<?php

namespace App\Repository;

use App\Entity\CategorieYoutube;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method CategorieYoutube|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategorieYoutube|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategorieYoutube[]    findAll()
 * @method CategorieYoutube[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategorieYoutubeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategorieYoutube::class);
    }

    // /**
    //  * @return CategorieYoutube[] Returns an array of CategorieYoutube objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CategorieYoutube
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
