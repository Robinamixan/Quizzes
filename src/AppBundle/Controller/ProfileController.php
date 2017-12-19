<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProfileController extends Controller
{
    /**
     * @Route("/profile", name="profile")
     */
    public function profileAction(Request $request)
    {
        return $this->render('security/profile.html.twig', array(

        ));
    }


}
