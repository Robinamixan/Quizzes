<?php
/**
 * Created by PhpStorm.
 * User: robinam
 * Date: 08.01.18
 * Time: 12:58
 */

namespace AppBundle\Service;

use AppBundle\Entity\Question;
use Doctrine\ORM\EntityManagerInterface;

class ListQuestions
{
    private $em;

    function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getListQuestions(int $notesPerPage, int $page, string $sortDirection, string $sortField, array $filters)
    {
        switch ($sortField) {
            case 'ID': $sortField = 'id_quetion'; break;
            case 'Question Text': $sortField = 'question_text'; break;
            default: $sortField = 'id_quetion';
        }

        $qb1 = $this->em->createQueryBuilder();
        $qb1->select('q')
            ->from(Question::class, 'q')
            ->orderBy('q.'.$sortField, $sortDirection)
            ->setFirstResult( ($page-1)*$notesPerPage )
            ->setMaxResults( $notesPerPage )
        ;

        if ($filters) {
            foreach ($filters as $key => $value) {
                switch ($key) {
                    case 'ID':
                        $qb1->andWhere($qb1->expr()->like('q.id_quetion', ':' . $key))
                            ->setParameter($key,'%' . $value . '%');
                        break;
                    case 'Question Text':
                        $qb1->andWhere($qb1->expr()->like('q.question_text', ':' . $key))
                            ->setParameter($key,'%' . $value . '%');
                        break;
                }
            }
        }
        return $qb1->getQuery()->getResult();
    }

    public function getCountQuestions(array $filters)
    {
        $qb2 = $this->em->createQueryBuilder();
        $qb2->select('q')
            ->from(Question::class, 'q')
            ->addSelect($qb2->expr()->count('q.id_quetion') . 'AS all_notes')
        ;

        if ($filters) {
            foreach ($filters as $key => $value) {
                switch ($key) {
                    case 'ID':
                        $qb2->andWhere($qb2->expr()->like('q.id_quetion', ':' . $key))
                            ->setParameter($key,'%' . $value . '%');
                        break;
                    case 'Question Text':
                        $qb2->andWhere($qb2->expr()->like('q.question_text', ':' . $key))
                            ->setParameter($key,'%' . $value . '%');
                        break;
                }
            }
        }
        return $qb2->getQuery()->getResult();
    }
}