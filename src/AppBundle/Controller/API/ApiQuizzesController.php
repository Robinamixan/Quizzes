<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\Quiz;
use AppBundle\Service\ListQuizzes;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\UserBundle\Event\UserEvent;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class ApiQuizzesController extends FOSRestController
{
    /**
     * @Route("/api/quizzes", name="apiQuizzes")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function quizzesListAction(Request $request, ListQuizzes $listsEntities)
    {
        $data = [];
        if ($request->getMethod() === 'GET') {
            $page = $request->query->get('page');
            $limit = $request->query->get('limit');
            $quizzes = $listsEntities->getListQuizzes($limit, $page);
            $quizzesAmount = $listsEntities->getCountQuizzes([]);
            $data = [
                "quizzes" => $quizzes,
                'quizzesAmount' => $quizzesAmount[0]['all_notes'],
                ];
        }

        return $this->json($data);
    }

    /**
     * @Route("/api/quizzes/{id_quiz}", name="apiQuizzes")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function quizByIdAction(Request $request, $id_quiz, EntityManagerInterface $em)
    {
        $data = [];
        if ($request->getMethod() === 'GET') {
            $quiz = $em->getRepository(Quiz::class)->find($id_quiz);
            $data = [
                "quiz" => $quiz,
            ];
        }

        return $this->json($data);
    }
}