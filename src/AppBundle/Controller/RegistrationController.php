<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Access;
use AppBundle\Entity\User;
use AppBundle\Form\RegistrationForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends Controller
{
    /**
     * @param Request $request
     *
     * @Route("/registration", name="registration")
     */
    public function registrationAction(Request $request)
    {
        $user = new User();

        $form = $this->createForm(RegistrationForm::class, $user);

        $form->handleRequest($request);

        return $this->render('security/registration.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @param Request $req
     * @param UserPasswordEncoderInterface $passwordEncoder
     *
     * @Route("/create", name="create")
     */
    public function createAction(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $em   = $this->getDoctrine()->getManager();
        $form = $this->createForm(RegistrationForm::class, new User());


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $user = $form->getData();

            $password = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);

            $access = $this->getDoctrine()
                ->getRepository(Access::class)
                ->findOneByName('ROLE_USER');

            $user->setAccess($access);

            $em->persist($user);
            $em->flush();

            $url = $this->generateUrl('login');
            return $this->redirect($url);
        }


        return $this->render('security/registration.html.twig', array(
            'form' => $form->createView()
        ));
    }
}
