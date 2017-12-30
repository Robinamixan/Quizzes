<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Question;
use AppBundle\Entity\Quiz;
use AppBundle\Form\QuizForm;
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
                $em->persist($question);
            }

            $em->persist($quiz);
            $em->flush();
        }

        return $this->render('admin_lists/admin_add_quiz.html.twig', array(
            'form'          => $form->createView(),
            'questions'     => $questions,
        ));
    }
}
