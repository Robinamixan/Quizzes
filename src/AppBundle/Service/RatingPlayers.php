<?php
/**
 * Created by PhpStorm.
 * User: robinam
 * Date: 08.01.18
 * Time: 12:58
 */

namespace AppBundle\Service;


use AppBundle\Entity\Passage;
use Doctrine\ORM\EntityManagerInterface;

class RatingPlayers
{
    private $em;

    function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getListQuizRating(int $id_quiz)
    {
        $qb3 = $this->em->createQueryBuilder();
        $qb3->select()
            ->from(Passage::class, 'p')
            ->leftJoin("p.user", "u")
            ->leftJoin("p.results", "r")
            ->leftJoin("r.answer", "a")
            ->leftJoin("p.quiz", "q")
            ->addSelect('u.username')
            ->addSelect('p.id_passage')
            ->addSelect($qb3->expr()->count('a.id_answer') . 'AS right_amount')
            ->andWhere('q.flag_active=1')
            ->andWhere('a.flag_right=1')
            ->andWhere('q.id_quiz=' . $id_quiz)
            ->addGroupBy('p.id_passage')
            ->orderBy('right_amount', 'DESC')
            ->setFirstResult( 0 )
            ->setMaxResults( 10 )
        ;
        $query = $qb3->getQuery();
        $passages = $query->getResult();
        return $passages;
    }

    public function getListPlayerResults(int $id_user)
    {
        $qb3 = $this->em->createQueryBuilder();
        $qb3->select()
            ->from(Passage::class, 'p')
            ->leftJoin("p.user", "u")
            ->leftJoin("p.results", "r")
            ->leftJoin("p.condition", "c")
            ->leftJoin("r.answer", "a")
            ->leftJoin("p.quiz", "q")
            ->addSelect('q.id_quiz')
            ->addSelect('q.name')
            ->addSelect('p.id_passage')
            ->addSelect($qb3->expr()->count('a.id_answer') . 'AS right_amount')
            ->andWhere('q.flag_active=1')
            ->andWhere('a.flag_right=1')
            ->andWhere('c.id_condition=2')
            ->andWhere('u.id=' . $id_user)
            ->addGroupBy('p.id_passage')
        ;
        $query = $qb3->getQuery();
        $passages = $query->getResult();
        return $passages;
    }

}