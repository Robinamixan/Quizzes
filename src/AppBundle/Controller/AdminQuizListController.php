<?php

namespace AppBundle\Controller;

use AppBundle\Service\ListQuizzes;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class AdminQuizListController extends Controller
{
    /**
     * @Route("/control/quiz/list", name="admin_quiz_list")
     */
    public function quizListAction()
    {
        return $this->render('admin_control/admin_quizzes_list(plugin).html.twig', array(

        ));
    }

    /**
     * @Route("/control/quiz/ajax", name="quiz_list_ajax")
     */
    public function ajaxQuizAction(Request $request, ListQuizzes $listsEntities)
    {
        $notesPerPage = $request->request->get('notes_per_page');
        $page = $request->request->get('page');
        $sortDirection = $request->request->get('sort_direction');
        $sortField = $request->request->get('sort_field');
        $filters = $request->request->get('filters');

        if ($filters == null) {
            $filters = [];
        }

        $allNotes = $listsEntities->getCountQuizzes($filters);
        $quizzes = $listsEntities->getListQuizzes($notesPerPage, $page, $sortDirection, $sortField, $filters);

        $resultArray = [];

        foreach ($quizzes as $quiz) {
            $temp_array = [];
            $temp_array['ID'] = $quiz->getIdQuiz();
            $temp_array['Name'] = $quiz->getName();
            $temp_array['Date of create'] = $quiz->getDateOfCreate();
            $temp_array['Description'] = $quiz->getDescription();
            if ($quiz->getFlagActive()) {
                $temp_array['Status'] = 'Active';
            } else {
                $temp_array['Status'] = 'Not active';
            }
            $resultArray[] = $temp_array;
        }

        $arrData = ['result' => $resultArray, 'all_notes' => $allNotes];
        return new JsonResponse($arrData);
    }


}
