<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class QuizzesListController extends Controller
{
    /**
     * @Route("/quizzes", name="quizzes_list")
     */
    public function quizzesListAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();


        $connection = $em->getConnection();
        $sql_query = 'SELECT q.*, z1.users_amount
                      FROM Quizzes q LEFT JOIN
                        (SELECT pas.id_quiz AS id_quiz, count(id_user) AS users_amount
                        FROM Passages pas
                        WHERE pas.id_condition = 3
                        GROUP BY pas.id_quiz) z1
                      ON z1.id_quiz = q.id_quiz';
        $statement = $connection->prepare($sql_query);
        $statement->execute();

        $results = $statement->fetchAll();


//        $qb1 = $em->createQueryBuilder();
//        $qb1->select()
//            ->from(Passage::class, 'p')
//            ->leftJoin("p.quiz", "q")
//            ->leftJoin("p.user", "u")
//            ->addSelect('q.name')
//            ->addSelect('q.date_of_create')
//            ->addSelect('q.description')
//            ->addSelect('q.flag_active')
//            ->addSelect($qb1->expr()->count('u.id_user') . 'AS users_amount')
//            ->groupBy('q.id_quiz')
//        ;
////        $dqlquery = $qb1->getDQL();
////
////        $qb2 = $em->createQueryBuilder();
////        $qb2->select('q')
////            ->from(Quiz::class, 'q')
////            ->leftJoin(sprintf('(%s)', $dqlquery), 'z1', 'q.id_quiz=z1.id_quiz')
////            ->addSelect('z1')
////        ;
//        $query = $qb1->getQuery();
//
//        $results = $query->getResult();


        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $results, /* query NOT result */
            $request->query->getInt('page', 1),
            5/*limit per page*/
        );

        return $this->render('quizzes_pages/quizzes_list.html.twig', array(
            'pagination'        => $pagination,
        ));
    }
}

