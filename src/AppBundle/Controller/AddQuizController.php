<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Question;
use AppBundle\Entity\Quiz;
use AppBundle\Form\QuizForm;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AddQuizController extends Controller
{
    /**
     * @Route("/control/add_quiz", name="add_quiz")
     */
    public function profileAction(Request $request)
    {
        $questions = $this->getDoctrine()
            ->getRepository(Question::class)
            ->findAll();

        $em = $this->getDoctrine()->getManager();
        $quiz = new Quiz();

        $form = $this->createForm(QuizForm::class, $quiz);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            foreach ($quiz->getQuestions() as $question) {
                $true_question = $this->getDoctrine()
                    ->getRepository(Question::class)
                    ->find($question->getIdQuestion());

                $quiz->removeQuestion($question);
                $quiz->addQuestion($true_question);
                //$em->persist($question);
            }

            $em->persist($quiz);
            $em->flush();
        }
        //var_dump($quiz->getQuestions());die();
        $array = [];
        for($i = 0; $i< sizeof($questions); $i++) {
            $array [$i] = [];
            $array [$i]['id'] = $questions[$i]->getIdQuestion();
            $array [$i]['text'] = $questions[$i]->getQuestionText();
        }
        $json = json_encode($array, JSON_UNESCAPED_UNICODE);

        return $this->render('admin_control/admin_add_quiz.html.twig', array(
            'form'                  => $form->createView(),
            'questions'             => $questions,
            'json_questions'        => $json,
        ));
    }
}
