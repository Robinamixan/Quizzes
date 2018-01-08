<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Question;
use AppBundle\Form\QuestionForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AdminManageQuestionController extends Controller
{
    /**
     * @Route("/control/question/add", name="add_question")
     */
    public function addQuestionAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $question = new Question();

        $form = $this->createForm(QuestionForm::class, $question);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $question = $form->getData();
            foreach ($question->getAnswers() as $answer) {
                $answer->setQuestion($question);

                $em->persist($answer);
            }

            $em->persist($question);
            $em->flush();

            $url = $this->generateUrl('add_question');
            return $this->redirect($url);
        }

        return $this->render('admin_control/admin_add_question.html.twig', array(
            'button_text'          => 'Create new question',
            'form'          => $form->createView(),
        ));
    }

    /**
     * @Route("/control/question/edit/{id_question}", name="edit_question", requirements={"id"="\d+"}, defaults={"id_question" = 0})
     */
    public function editQuestionAction(Request $request, int $id_question)
    {
        if ($id_question == 0) {
            $url = $this->generateUrl('admin_question_list');
            return $this->redirect($url);
        }

        $em = $this->getDoctrine()->getManager();
        $question = $this->getDoctrine()
            ->getRepository(Question::class)
            ->find($id_question);

        $current_answers = $question->getAnswers()->getValues();

        $form = $this->createForm(QuestionForm::class, $question);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $new_answers = $question->getAnswers()->getValues();
            foreach ($current_answers as $answer) {
                if (!in_array($answer, $new_answers)) {
                    $em->remove($answer);
                }
            }
            $em->persist($question);
            $em->flush();

            $url = $this->generateUrl('admin_question_list');
            return $this->redirect($url);
        }

        return $this->render('admin_control/admin_add_question.html.twig', array(
            'button_text'          => 'Edit question',
            'form'          => $form->createView(),
        ));
    }

    /**
     * @Route("/control/question/delete/{id_question}", name="delete_question", requirements={"id"="\d+"}, defaults={"id_question" = 0})
     */
    public function deleteQuestionAction(Request $request, int $id_question)
    {
        if ($id_question == 0) {
            $url = $this->generateUrl('admin_question_list');
            return $this->redirect($url);
        }
        $em = $this->getDoctrine()->getManager();
        $question = $this->getDoctrine()
            ->getRepository(Question::class)
            ->find($id_question);

        $answers = $question->getAnswers()->getValues();
        foreach ($answers as $answer) {
            $em->remove($answer);
        }

        $em->remove($question);
        $em->flush();
        $url = $this->generateUrl('admin_question_list');
        return $this->redirect($url);
    }
}
