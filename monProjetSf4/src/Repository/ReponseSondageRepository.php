<?php

namespace App\Repository;

use App\Entity\ReponseSondage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ReponseSondage|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReponseSondage|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReponseSondage[]    findAll()
 * @method ReponseSondage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReponseSondageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReponseSondage::class);
    }

    // /**
    //  * @return ReponseSondage[] Returns an array of ReponseSondage objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ReponseSondage
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
