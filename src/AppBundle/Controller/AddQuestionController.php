<?php

namespace AppBundle\Controller;

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
        return $this->render('security/profile.html.twig', array(

        ));
    }


}
