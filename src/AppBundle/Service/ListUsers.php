<?php
/**
 * Created by PhpStorm.
 * User: robinam
 * Date: 08.01.18
 * Time: 12:58
 */

namespace AppBundle\Service;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class ListUsers
{
    private $em;

    function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getListUsers(int $notesPerPage, int $page, string $sortDirection, string $sortField, array $filters)
    {
        switch ($sortField) {
            case 'ID': $sortField = 'u.id_user'; break;
            case 'Username': $sortField = 'u.username'; break;
            case 'Email': $sortField = 'u.email'; break;
            case 'Full Name': $sortField = 'u.full_name'; break;
            case 'Access': $sortField = 'a.name'; break;
            case 'Condition': $sortField = 'c.name'; break;
            default: $sortField = 'u.id_user';
        }

        $qb1 = $this->em->createQueryBuilder();
        $qb1->select('u')
            ->from(User::class, 'u')
            ->leftJoin("u.access", "a")
            ->leftJoin("u.condition", "c")
            ->addSelect('a.name AS access_name')
            ->addSelect('c.name AS condition_name')
            ->orderBy($sortField, $sortDirection)
            ->setFirstResult( ($page-1)*$notesPerPage )
            ->setMaxResults( $notesPerPage );
        ;

        if ($filters) {
            foreach ($filters as $key => $value) {
                switch ($key) {
                    case 'ID':
                        $qb1->andWhere($qb1->expr()->like('u.id_user', ':' . $key))
                            ->setParameter($key,'%' . $value . '%');
                        break;
                    case 'Username':
                        $qb1->andWhere($qb1->expr()->like('u.username', ':' . $key))
                            ->setParameter($key,'%' . $value . '%');
                        break;
                    case 'Email':
                        $qb1->andWhere($qb1->expr()->like('u.email', ':' . $key))
                            ->setParameter($key,'%' . $value . '%');
                        break;
                    case 'Full Name':
                        $qb1->andWhere($qb1->expr()->like('u.full_name', ':' . $key))
                            ->setParameter($key,'%' . $value . '%');
                        break;
                }
            }
        }
        return $qb1->getQuery()->getResult();
    }

    public function getCountUsers(array $filters)
    {
        $qb2 = $this->em->createQueryBuilder();
        $qb2->select('u')
            ->from(User::class, 'u')
            ->addSelect($qb2->expr()->count('u.id_user') . 'AS all_notes');

        if ($filters) {
            foreach ($filters as $key => $value) {
                switch ($key) {
                    case 'ID':
                        $qb2->andWhere($qb2->expr()->like('u.id_user', ':' . $key))
                            ->setParameter($key,'%' . $value . '%');
                        break;
                    case 'Username':
                        $qb2->andWhere($qb2->expr()->like('u.username', ':' . $key))
                            ->setParameter($key,'%' . $value . '%');
                        break;
                    case 'Email':
                        $qb2->andWhere($qb2->expr()->like('u.email', ':' . $key))
                            ->setParameter($key,'%' . $value . '%');
                        break;
                    case 'Full Name':
                        $qb2->andWhere($qb2->expr()->like('u.full_name', ':' . $key))
                            ->setParameter($key,'%' . $value . '%');
                        break;
                }
            }
        }
        return $qb2->getQuery()->getResult();
    }

}