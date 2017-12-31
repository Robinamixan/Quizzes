<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminPanelController extends Controller
{
    /**
     * @Route("/control", name="admin_control")
     */
    public function adminPanelAction(Request $request)
    {
        return $this->render('admin_control/admin_panel.html.twig', array(

        ));
    }


}
