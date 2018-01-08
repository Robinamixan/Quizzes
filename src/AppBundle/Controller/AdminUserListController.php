<?php

namespace AppBundle\Controller;


use AppBundle\Service\ListUsers;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class AdminUserListController extends Controller
{
    /**
     * @Route("/control/user/list", name="admin_user_list")
     */
    public function quizListAction()
    {
        return $this->render('admin_control/admin_users_list(plugin).html.twig', array(

        ));
    }

    /**
     * @Route("/control/user/ajax", name="user_list_ajax")
     */
    public function ajaxUserAction(Request $request, ListUsers $listUsers)
    {
        $notesPerPage = $request->request->get('notes_per_page');
        $page = $request->request->get('page');
        $sortDirection = $request->request->get('sort_direction');
        $sortField = $request->request->get('sort_field');
        $filters = $request->request->get('filters');

        if ($filters == null) {
            $filters = [];
        }

        $allNotes = $listUsers->getCountUsers($filters);
        $users = $listUsers->getListUsers($notesPerPage, $page, $sortDirection, $sortField, $filters);

        $resultArray = [];

        foreach ($users as $user) {
            $temp_array = [];
            $temp_array['ID'] = $user[0]->getIdUser();
            $temp_array['Username'] = $user[0]->getUsername();
            $temp_array['Email'] = $user[0]->getEmail();
            $temp_array['Full Name'] = $user[0]->getFullName();
            $temp_array['Access'] = $user['access_name'];
            $temp_array['Condition'] = $user['condition_name'];
            $resultArray[] = $temp_array;
        }

        $arrData = ['result' => $resultArray, 'all_notes' => $allNotes];
        return new JsonResponse($arrData);
    }


}
