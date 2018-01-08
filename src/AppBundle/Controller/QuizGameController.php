<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Answer;
use AppBundle\Entity\Passage;
use AppBundle\Entity\PassageCondition;
use AppBundle\Entity\Quiz;
use AppBundle\Service\RatingPlayers;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class QuizGameController extends Controller
{
    /**
     * @Route("/quizzes/game/quiz/{id_quiz}", name="game", requirements={"id"="\d+"})
     */
    public function gameAction(int $id_quiz, EntityManagerInterface $em)
    {
        $quiz = $this->getDoctrine()
            ->getRepository(Quiz::class)
            ->find($id_quiz);

        $questions = $quiz->getQuestions()->getValues();
        shuffle($questions);

        $condition = $this->getDoctrine()
            ->getRepository(PassageCondition::class)
            ->findOneByName('Active');

        $user = $this->getUser();
        $passage = new Passage($quiz, $user, $condition);
        $em->persist($passage);
        $em->flush();

        return $this->render('quizzes_pages/game_quiz.html.twig', array(
            'quiz'                  => $quiz,
            'questions'             => $questions,
            'questions_count'       => count($questions),
            'passage'               => $passage,
            'user'                  => $user,
        ));
    }


    /**
     * @Route("/quizzes/game/ajax_request", name="game_ajax")
     */
    public function ajaxAction(Request $request, EntityManagerInterface $em)
    {
        $id_passage = $request->request->get('id_passage');
        $id_answer = $request->request->get('id_answer');
        $answer_time = $request->request->get('answer_time');

        $format = 'i:s';
        $time = \DateTime::createFromFormat($format, $answer_time);

        $answer = $this->getDoctrine()
            ->getRepository(Answer::class)
            ->find($id_answer);

        $passage = $this->getDoctrine()
            ->getRepository(Passage::class)
            ->find($id_passage);

        $question = $answer->getQuestion();

        $passage->addResult($question, $answer, $time);
        $em->persist($passage);
        $em->flush();


        if (($answer) && ($answer->getFlagRight())) {
            $arrData = ['result' => "right"];
            return new JsonResponse($arrData);
        } else {
            $arrData = ['result' => "wrong"];
            return new JsonResponse($arrData);
        }
    }


    /**
     * @Route("/quizzes/game/result/{id_passage}", name="game_result", requirements={"id"="\d+"})
     */
    public function resultAction(int $id_passage, RatingPlayers $rating_players, EntityManagerInterface $em)
    {
        $passage = $this->getDoctrine()
            ->getRepository(Passage::class)
            ->find($id_passage);

        $condition = $this->getDoctrine()
            ->getRepository(PassageCondition::class)
            ->findOneByName('Finished');

        $passage->setCondition($condition);
        $em->persist($passage);
        $em->flush();

        $results = $passage->getResults()->getValues();

        $passages = $rating_players->getListQuizRating($passage->getQuiz()->getIdQuiz());

        return $this->render('quizzes_pages/game_results_quiz.html.twig', [
            'passage'               => $passage,
            'results'               => $results,
            'passages'               => $passages,
        ]);
    }
}
