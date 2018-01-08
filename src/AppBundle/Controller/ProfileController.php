<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ProfileController extends Controller
{
    /**
     * @Route("/user/profile", name="profile")
     */
    public function profileAction(Request $request)
    {
        return $this->render('security/profile.html.twig', array(

        ));
    }


}
