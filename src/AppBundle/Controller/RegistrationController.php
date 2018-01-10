<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Access;
use AppBundle\Entity\User;
use AppBundle\Form\RegistrationForm;
use AppBundle\Service\SecurityMailer;
use Proxies\__CG__\AppBundle\Entity\UserCondition;
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
    public function createAction(Request $request, UserPasswordEncoderInterface $passwordEncoder, SecurityMailer $securityMailer)
    {
        $em   = $this->getDoctrine()->getManager();
        $form = $this->createForm(RegistrationForm::class, new User());

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $user = $form->getData();
            $token = md5($user->getEmail().rand().time());
            $user->setToken($token);

            $password = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);

            $access = $this->getDoctrine()
                ->getRepository(Access::class)
                ->findOneByName('ROLE_USER');

            $condition = $this->getDoctrine()
                ->getRepository(UserCondition::class)
                ->findOneByName('Not confirmed');

            $user->setAccess($access);
            $user->setCondition($condition);

            $em->persist($user);
            $em->flush();

            $securityMailer->sendMailConfirmRegistration($user);

            $url = $this->generateUrl('login');
            return $this->redirect($url);
        }


        return $this->render('security/registration.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @param Request $request
     *
     * @Route("/registration_confirm", name="registration_confirm")
     */
    public function registrationConfirmAction(Request $request)
    {
        $em   = $this->getDoctrine()->getManager();
        $token = $request->query->get('token');
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->findOneByToken($token);

        if ($user) {
            $condition = $this->getDoctrine()
                ->getRepository(UserCondition::class)
                ->findOneByName('Active');
            $user->setCondition($condition);
            $em->persist($user);
            $em->flush();
        }

        return $this->render('security/registration_confirmed.html.twig', array());
    }
}
