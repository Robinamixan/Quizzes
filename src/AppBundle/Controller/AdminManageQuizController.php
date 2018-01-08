<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Passage;
use AppBundle\Entity\Question;
use AppBundle\Entity\Quiz;
use AppBundle\Form\QuizForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AdminManageQuizController extends Controller
{
    /**
     * @Route("/control/quiz/add", name="add_quiz")
     */
    public function addQuizAction(Request $request)
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
            }
            $em->persist($quiz);
            $em->flush();

            $url = $this->generateUrl('admin_quiz_list');
            return $this->redirect($url);
        }

        $array = [];
        for($i = 0; $i< sizeof($questions); $i++) {
            $array [$i] = [];
            $array [$i]['id'] = $questions[$i]->getIdQuestion();
            $array [$i]['text'] = $questions[$i]->getQuestionText();
        }
        $json = json_encode($array, JSON_UNESCAPED_UNICODE);

        return $this->render('admin_control/admin_add_quiz.html.twig', array(
            'button_text'          => 'Create new quiz',
            'form'                  => $form->createView(),
            'questions'             => $questions,
            'json_questions'        => $json,
        ));
    }

    /**
     * @Route("/control/quiz/edit/{id_quiz}", name="edit_quiz", requirements={"id"="\d+"}, defaults={"id_quiz" = 0})
     */
    public function editQuizAction(Request $request, int $id_quiz)
    {
        if ($id_quiz == 0) {
            $url = $this->generateUrl('admin_quiz_list');
            return $this->redirect($url);
        }
        $questions = $this->getDoctrine()
            ->getRepository(Question::class)
            ->findAll();

        $em = $this->getDoctrine()->getManager();

        $quiz = $this->getDoctrine()
            ->getRepository(Quiz::class)
            ->find($id_quiz);

        $form = $this->createForm(QuizForm::class, $quiz);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            foreach ($quiz->getQuestions() as $question) {
                $true_question = $this->getDoctrine()
                    ->getRepository(Question::class)
                    ->find($question->getIdQuestion());

                $quiz->removeQuestion($question);
                $quiz->addQuestion($true_question);
            }
            $em->persist($quiz);
            $em->flush();

            $url = $this->generateUrl('admin_quiz_list');
            return $this->redirect($url);
        }

        $array = [];
        for($i = 0; $i< sizeof($questions); $i++) {
            $array [$i] = [];
            $array [$i]['id'] = $questions[$i]->getIdQuestion();
            $array [$i]['text'] = $questions[$i]->getQuestionText();
        }
        $json = json_encode($array, JSON_UNESCAPED_UNICODE);

        return $this->render('admin_control/admin_add_quiz.html.twig', array(
            'button_text'          => 'Edit quiz',
            'form'                  => $form->createView(),
            'questions'             => $questions,
            'json_questions'        => $json,
        ));
    }

    /**
     * @Route("/control/quiz/delete/{id_quiz}", name="delete_quiz", requirements={"id"="\d+"}, defaults={"id_quiz" = 0})
     */
    public function deleteQuizAction(Request $request, int $id_quiz)
    {
        if ($id_quiz == 0) {
            $url = $this->generateUrl('admin_quiz_list');
            return $this->redirect($url);
        }
        $em = $this->getDoctrine()->getManager();
        $quiz = $this->getDoctrine()
            ->getRepository(Quiz::class)
            ->find($id_quiz);

        $passages = $this->getDoctrine()
            ->getRepository(Passage::class)
            ->findByQuiz($id_quiz);

        foreach ($passages as $passage) {
            foreach ($passage->getResults() as $result) {
                $em->remove($result);
            }
            $em->remove($passage);
        }

        foreach ($quiz->getQuestions() as $question) {
            $quiz->removeQuestion($question);
        }



        $em->remove($quiz);
        $em->flush();

        $url = $this->generateUrl('admin_quiz_list');
        return $this->redirect($url);
    }
}
