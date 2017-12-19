<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Quiz;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class QuizzesListController extends Controller
{
    /**
     * @Route("/quizzes/{page}", name="quizzes_list", requirements={"page"="\d+"})
     */
    public function quizzesListAction(Request $request, int $page = 1)
    {
        $size_of_list = 10;

        $quizzes = $this->getDoctrine()
            ->getRepository(Quiz::class)
            ->findAll();

        $array_of_quizzes = array_chunk($quizzes, $size_of_list);

        return $this->render('test/quizzes_list.html.twig', array(
            'quizzes_list'      => $array_of_quizzes[$page-1],
            'page'              => $page
        ));
    }
}
