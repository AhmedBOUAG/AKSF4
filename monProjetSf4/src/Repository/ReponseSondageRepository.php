<?php

namespace App\Repository;

use App\Entity\ReponseSondage;
use App\Entity\QuestionSondage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ReponseSondage|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReponseSondage|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReponseSondage[]    findAll()
 * @method ReponseSondage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReponseSondageRepository extends ServiceEntityRepository {

    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, ReponseSondage::class);
    }

    /**
     * @return ReponseSondage[] Returns an array of ReponseSondage objects
     */
    public function getLastPool() {
        $lastQuestion = $this->getEntityManager()->getRepository(QuestionSondage::class)->getLastQuestion();
        return $this->createQueryBuilder('r')
                        ->where('r.question = :question')
                        ->setParameter('question', $lastQuestion)
                        ->getQuery()
                        ->getResult()
        ;
    }

    public function getTotalVote($value) {
        return $this->createQueryBuilder('r')
                        ->andWhere('r.question = :val')
                        ->setParameter('val', $value)
                        ->select('SUM(r.nbVote) as totalVote')
                        ->getQuery()
                        ->getOneOrNullResult()
        ;
    }
}
