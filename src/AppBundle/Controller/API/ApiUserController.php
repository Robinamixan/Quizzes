<?php

namespace AppBundle\Controller\API;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\UserBundle\Event\UserEvent;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class ApiUserController extends FOSRestController
{
    /**
     * @Route("/api/users/{username}/profile", name="user_profile")
     *
     * @param Request $request
     * @param string $username
     * @param UserManagerInterface $userManager
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function userProfileAction(Request $request, string $username, UserManagerInterface $userManager)
    {
        $data = [];
        if ($request->getMethod() === 'GET') {
            $user = $userManager->findUserByUsername($username);
            $data = [
                'username' => $user->getUsername(),
                'email' => $user->getEmail(),
                'full_name' => $user->getFullName(),
            ];
        }

        return $this->json($data);
    }

    /**
     * @Route("/users/registration", name="user_registration")
     *
     * @param Request $request
     * @param UserManagerInterface $userManager
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function registerAction(Request $request, UserManagerInterface $userManager)
    {
        if ($request->getMethod() === 'POST') {
            $username = $request->request->get('username');
            $email = $request->request->get('email');
            $password = $request->request->get('password');
            $fullName = $request->request->get('full_name');

            if ($userManager->findUserByUsername($username)) {
                return $this->json(['error' => 'user with this username already exist']);
            }

            if ($userManager->findUserByEmail($email)) {
                return $this->json(['error' => 'user with this email already exist']);
            }

            $user = $userManager->createUser();
            $user->setUsername($username);
            $user->setEmail($email);
            $user->setPlainPassword($password);
            $user->setFullName($fullName);
            $user->setEnabled((bool) true);
            $user->setSuperAdmin((bool) false);
            $userManager->updateUser($user);

            return $this->json(['user' => $user]);
        }
        return $this->json([]);
    }
}