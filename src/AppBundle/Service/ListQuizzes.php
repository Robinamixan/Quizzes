<?php
/**
 * Created by PhpStorm.
 * User: robinam
 * Date: 08.01.18
 * Time: 12:58
 */

namespace AppBundle\Service;

use AppBundle\Entity\Quiz;
use Doctrine\ORM\EntityManagerInterface;

class ListQuizzes
{
    private $em;

    function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getListQuizzes(int $notesPerPage, int $page, string $sortDirection, string $sortField, array $filters)
    {

        switch ($sortField) {
            case 'ID': $sortField = 'id_quiz'; break;
            case 'Name': $sortField = 'name'; break;
            case 'Date of create': $sortField = 'date_of_create'; break;
            case 'Description': $sortField = 'description'; break;
            case 'Status': $sortField = 'flag_active'; break;
            default: $sortField = 'id_quiz';
        }

        $qb1 = $this->em->createQueryBuilder();
        $qb1->select('q')
            ->from(Quiz::class, 'q')
            ->orderBy('q.'.$sortField, $sortDirection)
            ->setFirstResult( ($page-1)*$notesPerPage )
            ->setMaxResults( $notesPerPage );
        ;

        if ($filters) {
            foreach ($filters as $key => $value) {
                switch ($key) {
                    case 'ID':
                        $qb1->andWhere($qb1->expr()->like('q.id_quiz', ':' . $key))
                            ->setParameter($key,'%' . $value . '%');
                        break;
                    case 'Name':
                        $qb1->andWhere($qb1->expr()->like('q.name', ':' . $key))
                            ->setParameter($key,'%' . $value . '%');
                        break;
                    case 'Date of create with':
                        $qb1->andWhere('q.date_of_create >= :date_with')
                            ->setParameter('date_with', $value);
                        break;
                    case 'Date of create until':
                        $qb1->andWhere('q.date_of_create <= :date_until')
                            ->setParameter('date_until', $value);
                        break;
                }
            }
        }
        return $qb1->getQuery()->getResult();
    }

    public function getCountQuizzes(array $filters)
    {
        $qb2 = $this->em->createQueryBuilder();
        $qb2->select('q')
            ->from(Quiz::class, 'q')
            ->addSelect($qb2->expr()->count('q.id_quiz') . 'AS all_notes')
        ;

        if ($filters) {
            foreach ($filters as $key => $value) {
                switch ($key) {
                    case 'ID':
                        $qb2->andWhere($qb2->expr()->like('q.id_quiz', ':' . $key))
                            ->setParameter($key,'%' . $value . '%');
                        break;
                    case 'Name':
                        $qb2->andWhere($qb2->expr()->like('q.name', ':' . $key))
                            ->setParameter($key,'%' . $value . '%');
                        break;
                    case 'Date of create with':
                        $qb2->andWhere('q.date_of_create >= :date_with')
                            ->setParameter('date_with', $value);
                        break;
                    case 'Date of create until':
                        $qb2->andWhere('q.date_of_create <= :date_until')
                            ->setParameter('date_until', $value);
                        break;
                }
            }
        }
        return $qb2->getQuery()->getResult();
    }

}