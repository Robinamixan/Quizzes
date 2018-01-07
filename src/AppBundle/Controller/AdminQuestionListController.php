<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Question;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class AdminQuestionListController extends Controller
{
    /**
     * @Route("/control/question_list", name="admin_question_list")
     */
    public function quizListAction(Request $request)
    {
        return $this->render('admin_control/admin_questions_list(plugin).html.twig', array(

        ));
    }

    /**
     * @Route("/control/question_ajax", name="question_list_ajax")
     */
    public function ajaxAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $notes_per_page = $request->request->get('notes_per_page');
        $page = $request->request->get('page');
        $sort_direction = $request->request->get('sort_direction');
        $sort_field = $request->request->get('sort_field');
        $filters = $request->request->get('filters');

        $qb2 = $em->createQueryBuilder();
        $qb2->select('q')
            ->from(Question::class, 'q')
            ->addSelect($qb2->expr()->count('q.id_quetion') . 'AS all_notes');

        switch ($sort_field) {
            case 'ID': $sort_field = 'id_quetion'; break;
            case 'Text': $sort_field = 'question_text'; break;
            default: $sort_field = 'id_quetion';
        }

        $qb1 = $em->createQueryBuilder();
        $qb1->select('q')
            ->from(Question::class, 'q')
            ->orderBy('q.'.$sort_field, $sort_direction)
            ->setFirstResult( ($page-1)*$notes_per_page )
            ->setMaxResults( $notes_per_page );
        ;

        if ($filters) {
            foreach ($filters as $key => $value) {
                switch ($key) {
                    case 'ID':
                        $qb1->andWhere($qb1->expr()->like('q.id_quetion', ':' . $key))
                            ->setParameter($key,'%' . $value . '%');
                        $qb2->andWhere($qb2->expr()->like('q.id_quetion', ':' . $key))
                            ->setParameter($key,'%' . $value . '%');
                        break;
                    case 'Text':
                        $qb1->andWhere($qb1->expr()->like('q.question_text', ':' . $key))
                            ->setParameter($key,'%' . $value . '%');
                        $qb2->andWhere($qb2->expr()->like('q.question_text', ':' . $key))
                            ->setParameter($key,'%' . $value . '%');
                        break;
                }
            }
        }

        $query = $qb2->getQuery();
        $all_notes = $query->getResult();

        $query = $qb1->getQuery();
        $questions = $query->getResult();

        $result_array = [];

        foreach ($questions as $question) {
            $temp_array = [];
            $temp_array['id'] = $question->getIdQuestion();
            $temp_array['text'] = $question->getQuestionText();
            $result_array[] = $temp_array;
        }

        $arrData = ['result' => $result_array, 'all_notes' => $all_notes];
        return new JsonResponse($arrData);
    }


}
