<?php

namespace AppBundle\Controller;

use AppBundle\Service\ListQuestions;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class AdminQuestionListController extends Controller
{
    /**
     * @Route("/control/question/list", name="admin_question_list")
     */
    public function questionListAction()
    {
        return $this->render('admin_control/admin_questions_list(plugin).html.twig', array(

        ));
    }

    /**
     * @Route("/control/question/ajax", name="question_list_ajax" )
     */
    public function ajaxQuestionAction(Request $request, ListQuestions $lists_entities)
    {
        $notesPerPage = $request->request->get('notes_per_page');
        $page = $request->request->get('page');
        $sortDirection = $request->request->get('sort_direction');
        $sortField = $request->request->get('sort_field');
        $filters = $request->request->get('filters');

        if ($filters == null) {
            $filters = [];
        }

        $allNotes = $lists_entities->getCountQuestions($filters);
        $questions = $lists_entities->getListQuestions($notesPerPage, $page, $sortDirection, $sortField, $filters);

        $resultArray = [];

        foreach ($questions as $question) {
            $temp_array = [];
            $temp_array['ID'] = $question->getIdQuestion();
            $temp_array['Question Text'] = $question->getQuestionText();
            $temp_array['Answers'] = '';
            foreach ($question->getAnswers()->getValues() as $answer) {
                if ($answer->getFlagRight()) {
                    $temp_array['Answers'] .= $answer->getAnswerText() . '<span class="glyphicon glyphicon-ok" aria-hidden="true"></span><br>';
                } else {
                    $temp_array['Answers'] .= $answer->getAnswerText() . '<br>';
                }
            }
            $resultArray[] = $temp_array;
        }

        $arrData = ['result' => $resultArray, 'all_notes' => $allNotes];
        return new JsonResponse($arrData);
    }


}
