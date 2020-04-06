<?php

namespace App\Repository;

use App\Entity\YoutubeVideos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method YoutubeVideos|null find($id, $lockMode = null, $lockVersion = null)
 * @method YoutubeVideos|null findOneBy(array $criteria, array $orderBy = null)
 * @method YoutubeVideos[]    findAll()
 * @method YoutubeVideos[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class YoutubeVideosRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, YoutubeVideos::class);
    }

     /**
      * @return YoutubeVideos[] Returns an array of Last 3 videos
      */
    
    public function findLastThreeVideos()
    {
        return $this->createQueryBuilder('y')
            //->andWhere('y.exampleField = :val')
            //->setParameter('val', $value)
            ->orderBy('y.id', 'DESC')
            ->setMaxResults(3)
            ->getQuery()
            ->getResult()
        ;
    }
    

    /*
    public function findOneBySomeField($value): ?YoutubeVideos
    {
        return $this->createQueryBuilder('y')
            ->andWhere('y.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
