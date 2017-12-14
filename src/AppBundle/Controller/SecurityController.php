<?php
/**
 * Created by PhpStorm.
 * User: robinam
 * Date: 10.12.17
 * Time: 12:53
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Access;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends Controller
{
    /**
     * @param Request $request
     *
     * @Route("/login", name="login")
     */
    public function autorizationAction(Request $request, AuthenticationUtils $authUtils)
    {
        $error = $authUtils->getLastAuthenticationError();

        $lastUsername = $authUtils->getLastUsername();

        return $this->render('security/login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
    }

    /**
     * @param Request $request
     *
     * @Route("/registration", name="registration")
     */
    public function registrationAction(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = new User();

        $form = $this->createFormBuilder($user)
            ->add('username', TextType::class, array('label' => 'Username'))
            ->add('password', PasswordType::class, array('label' => 'Password'))
            ->add('email', EmailType::class, array('label' => 'Email'))
            ->add('full_name', TextType::class, array('label' => 'Full Name'))
            ->add('save', SubmitType::class, array('label' => 'Create Task'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
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

            return $this->render('security/show.html.twig', array(
                'username' => $user->getUsername(),
                'password' => $user->getPassword(),
                'email' => $user->getEmail(),
                'fullname' => $user->getFullName(),
                'role' => $user->getRoles()[0]
            ));
        }

        return $this->render('security/registration.html.twig', array(
            'form' => $form->createView()
        ));
    }

}