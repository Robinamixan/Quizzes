<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Quiz;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class AdminQuizListController extends Controller
{
    /**
     * @Route("/control/quiz_list", name="admin_quiz_list")
     */
    public function quizListAction(Request $request)
    {
        return $this->render('admin_control/admin_quizzes_list(plugin).html.twig', array(

        ));
    }

    /**
     * @Route("/control/quiz_ajax", name="quiz_list_ajax")
     */
    public function ajaxAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $quizzes_per_page = $request->request->get('notes_per_page');
        $page = $request->request->get('page');
        $sort_direction = $request->request->get('sort_direction');
        $sort_field = $request->request->get('sort_field');
        $filters = $request->request->get('filters');

        $qb2 = $em->createQueryBuilder();
        $qb2->select('q')
            ->from(Quiz::class, 'q')
            ->addSelect($qb2->expr()->count('q.id_quiz') . 'AS all_notes');

        if ($filters) {
            foreach ($filters as $key => $value) {
                switch ($key) {
                    case 'ID':
                        $qb2->andWhere($qb2->expr()->like('q.id_quiz', ':' . $key))
                            ->setParameter($key,'%' . $value . '%');
                        break;
                    case 'Name':
                        $qb2->andWhere($qb2->expr()->like('q.name', ':' . $key))
                            ->setParameter($key,'%' . $value . '%');
                        break;
                    case 'Date of create with':
                        $qb2->andWhere('q.date_of_create >= :date_with')
                            ->setParameter('date_with', $value);
                        break;
                    case 'Date of create until':
                        $qb2->andWhere('q.date_of_create <= :date_until')
                            ->setParameter('date_until', $value);
                        break;
                }
            }
        }

        $query = $qb2->getQuery();
        $all_quizzes = $query->getResult();

        switch ($sort_field) {
            case 'ID': $sort_field = 'id_quiz'; break;
            case 'Name': $sort_field = 'name'; break;
            case 'Date of create': $sort_field = 'date_of_create'; break;
            case 'Description': $sort_field = 'description'; break;
            case 'Status': $sort_field = 'flag_active'; break;
            default: $sort_field = 'id_quiz';
        }

        $qb1 = $em->createQueryBuilder();
        $qb1->select('q')
            ->from(Quiz::class, 'q')
            ->orderBy('q.'.$sort_field, $sort_direction)
            ->setFirstResult( ($page-1)*$quizzes_per_page )
            ->setMaxResults( $quizzes_per_page );
        ;

        if ($filters) {
            foreach ($filters as $key => $value) {
                switch ($key) {
                    case 'ID':
                        $qb1->andWhere($qb1->expr()->like('q.id_quiz', ':' . $key))
                            ->setParameter($key,'%' . $value . '%');
                        break;
                    case 'Name':
                        $qb1->andWhere($qb1->expr()->like('q.name', ':' . $key))
                            ->setParameter($key,'%' . $value . '%');
                        break;
                    case 'Date of create with':
                        $qb1->andWhere('q.date_of_create >= :date_with')
                            ->setParameter('date_with', $value);
                        break;
                    case 'Date of create until':
                        $qb1->andWhere('q.date_of_create <= :date_until')
                            ->setParameter('date_until', $value);
                        break;
                }
            }
        }

        $query = $qb1->getQuery();
        $quizzes = $query->getResult();

        $result_array = [];

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
            $result_array[] = $temp_array;
        }

        $arrData = ['result' => $result_array, 'all_notes' => $all_quizzes];
        return new JsonResponse($arrData);
    }


}
