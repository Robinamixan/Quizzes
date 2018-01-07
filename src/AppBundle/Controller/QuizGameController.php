<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Answer;
use AppBundle\Entity\Passage;
use AppBundle\Entity\PassageCondition;
use AppBundle\Entity\Quiz;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class QuizGameController extends Controller
{
    /**
     * @Route("/game/quiz/{id_quiz}", name="game", requirements={"id"="\d+"})
     */
    public function gameAction(Request $request, int $id_quiz)
    {
        $em = $this->getDoctrine()->getManager();
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
     * @Route("/game/ajax_request", name="game_ajax")
     */
    public function ajaxAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
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
     * @Route("/game/result/{id_passage}", name="game_result", requirements={"id"="\d+"})
     */
    public function resultAction(Request $request, int $id_passage)
    {
        $em = $this->getDoctrine()->getManager();

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

        $qb3 = $em->createQueryBuilder();
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
            ->andWhere('q.id_quiz=' . $passage->getQuiz()->getIdQuiz())
            ->addGroupBy('p.id_passage')
            ->orderBy('right_amount', 'DESC')

        ;
        $query = $qb3->getQuery();
        $passages = $query->getResult();

        //var_dump($passeges); die();

        return $this->render('quizzes_pages/game_results_quiz.html.twig', [
            'passage'               => $passage,
            'results'               => $results,
            'passages'               => $passages,
        ]);
    }
}
