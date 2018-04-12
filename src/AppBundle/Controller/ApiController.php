<?php

// src/AppBundle/Controller/ApiController.php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Route;

class ApiController extends FOSRestController
{
    /**
     * @Route("/api/hello", name="apihello")
     */
    public function indexAction()
    {
        $data = array("hello" => "world");
        return $this->json($data);
    }
}