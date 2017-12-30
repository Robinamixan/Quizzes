<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Question;
use AppBundle\Form\QuestionForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AddQuestionController extends Controller
{
    /**
     * @Route("/control/add_question", name="add_question")
     */
    public function profileAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $question = new Question();

        $form = $this->createForm(QuestionForm::class, $question);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            var_dump($question);
            $question = $form->getData();

            foreach ($question->getAnswers() as $answer) {
                $em->persist($answer);
            }

            $em->persist($question);
            $em->flush();
        }

        return $this->render('admin_lists/admin_add_question.html.twig', array(
            'form'          => $form->createView(),
        ));
    }
}
