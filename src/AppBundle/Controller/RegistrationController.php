<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Access;
use AppBundle\Entity\User;
use AppBundle\Form\RegistrationForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends Controller
{
    /**
     * @param Request $request
     *
     * @Route("/registration", name="registration")
     */
    public function registrationAction(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = new User();

        $form = $this->createForm(RegistrationForm::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();

            $user = $form->getData();

            $password = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);

            $access = $this->getDoctrine()
                ->getRepository(Access::class)
                ->findOneByAccessName('ROLE_USER');

            $user->setAccess($access);

            $em->persist($user);

            $em->flush();
        }

        return $this->render('security/registration.html.twig', array(
            'form' => $form->createView()
        ));
    }


}
