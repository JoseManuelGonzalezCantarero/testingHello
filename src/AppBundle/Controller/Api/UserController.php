<?php

namespace AppBundle\Controller\Api;

use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends Controller
{
    /**
     * @Route("/api/user", methods={"POST"})
     */
    public function newAction(Request $request)
    {
        $username = $request->get('username');
        $plainPassword = $request->get('plainPassword');
        $roles = [];
        $roles[] = $request->get('roles');

        if (empty($username) || empty($plainPassword) || empty($roles)) {
            $response = new JsonResponse('Null values are not allowed', Response::HTTP_NOT_ACCEPTABLE);
            $response->headers->set('Content-Type', 'application/problem+json');

            return $response;
        }
        $user = New User();
        $user->setUsername($username);
        $user->setPlainPassword($plainPassword);
        $user->setRoles($roles);

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        $response = new JsonResponse('User created', Response::HTTP_CREATED);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}