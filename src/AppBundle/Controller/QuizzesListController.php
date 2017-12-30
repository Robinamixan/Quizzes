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
        $em = $this->getDoctrine()->getManager(); // ...or getEntityManager() prior to Symfony 2.1
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



        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $results, /* query NOT result */
            $request->query->getInt('page', 1),
            5/*limit per page*/
        );

        return $this->render('test/quizzes_list.html.twig', array(
            'pagination'        => $pagination,
        ));
    }
}

