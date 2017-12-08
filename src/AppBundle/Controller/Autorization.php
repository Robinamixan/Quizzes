<?php
/**
 * Created by PhpStorm.
 * User: robinam
 * Date: 08.12.17
 * Time: 18:21
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Autorization extends Controller
{

    /**
     * @param Request $request
     *
     * @Route("/admin", name="test_admin")
     */
    public function autorizationAction(Request $request)
    {
        $first = 3;
        $second = 7;
        $sum = $first + $second;

        return $this->render("test/test.html.twig",
            array(
                'first' => $first,
                'second' => $second,
                'sum' => $sum
            )
        );
    }
}