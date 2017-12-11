<?php
/**
 * Created by PhpStorm.
 * User: robinam
 * Date: 10.12.17
 * Time: 21:51
 */

namespace AppBundle\Controller;



use AppBundle\Entity\Product;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;

class MySQLController extends Controller
{

    /**
     * @param Request $request
     *
     * @Route("/connect_db", name="connect_db")
     */
    public function createAction()
    {
        // you can fetch the EntityManager via $this->getDoctrine()
        // or you can add an argument to your action: createAction(EntityManagerInterface $em)
        $em = $this->getDoctrine()->getManager();

        $product = new User();
        $product->setUsername('n.usian');
        $product->setPassword('22222');
        $product->setEmail('n.usian@test.loc');
        $product->setFullName('Nikola Usian');
        $product->setAccess(2);

        // tells Doctrine you want to (eventually) save the Product (no queries yet)
        $em->persist($product);

        // actually executes the queries (i.e. the INSERT query)
        $em->flush();

        return new Response('Saved new product with id '.$product->getId_user());
    }

    // if you have multiple entity managers, use the registry to fetch them
    public function editAction()
    {
        $doctrine = $this->getDoctrine();
        $em = $doctrine->getManager();
        $em2 = $doctrine->getManager('other_connection');
    }

    /**
     * @param Request $request
     *
     * @Route("/show_db", name="show_db")
     */
    public function showAction()
    {
        $id_user = 2;

        $product = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($id_user);

        if (!$product) {
            throw $this->createNotFoundException(
                'No user found for id '.$id_user
            );
        }

        return new Response('user with id '.$product->getLogin());
    }
}