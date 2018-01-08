<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Passage;
use AppBundle\Entity\Quiz;
use AppBundle\Service\RatingPlayers;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class QuizzesListController extends Controller
{
    /**
     * @Route("/quizzes/list", name="quizzes_list")
     */
    public function quizzesListAction(Request $request, RatingPlayers $rating_players, EntityManagerInterface $em)
    {
        $user = $this->getUser();

        $qb1 = $em->createQueryBuilder();
        $qb1->select()
            ->from(Passage::class, 'p')
            ->leftJoin("p.quiz", "q")
            ->leftJoin("p.condition", "c")
            ->leftJoin("p.user", "u")
            ->addSelect('q.id_quiz')
            ->addSelect($qb1->expr()->count('u.id_user') . 'AS users_amount')
            ->andWhere('q.flag_active=1')
            ->andWhere('c.id_condition=3')
            ->addGroupBy('q.id_quiz')
        ;
        $query = $qb1->getQuery();
        $users_pas_quiz = $query->getResult();

        $user_results = $rating_players->getListPlayerResults($user->getIdUser());

        $qb2 = $em->createQueryBuilder();
        $qb2->select()
            ->from(Quiz::class, 'q')
            ->addSelect('q.id_quiz')
            ->addSelect('q.name')
            ->addSelect('q.description')
            ->addSelect('q.date_of_create')
            ->andWhere('q.flag_active=1')
        ;
        $query = $qb2->getQuery();
        $quizzes= $query->getResult();

        $results = [];

        foreach ($quizzes as $quiz) {
            $compare_counts = false;
            $compare_user = false;
            foreach ($users_pas_quiz as $pass_quiz) {
                if ($quiz['id_quiz'] == $pass_quiz['id_quiz']) {
                    $quiz = array_merge($quiz, $pass_quiz);
                    $compare_counts = true;
                    break;
                }
            }
            if (!$compare_counts) {
                $quiz['users_amount'] = 0;
            }
            foreach ($user_results as $pass_user) {
                if ($quiz['id_quiz'] == $pass_user['id_quiz']) {
                    $quiz['flag_passed'] = true;
                    $quiz['result'] = $pass_user['right_amount'];
                    $quiz['id_passage'] = $pass_user['id_passage'];
                    $compare_user = true;
                    break;
                }
            }
            if (!$compare_user) {
                $quiz['flag_passed'] = false;
                $quiz['result'] = 0;
                $quiz['id_passage'] = 0;
            }

            $passages = $rating_players->getListQuizRating($quiz['id_quiz']);
            if ($passages) {
                $quiz['best_player'] = $passages[0]['username'];
                $quiz['right_amount'] = $passages[0]['right_amount'];
            }
            $results[] = $quiz;

        }

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $results,
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('quizzes_pages/quizzes_list.html.twig', array(
            'pagination'        => $pagination,
        ));
    }
}

