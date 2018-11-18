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

        if (empty($username) || empty($plainPassword) || empty($roles[0])) {
            return new JsonResponse('Null values are not allowed', Response::HTTP_NOT_ACCEPTABLE);
        }

        $user = New User();
        $user->setUsername($username);
        $user->setPlainPassword($plainPassword);
        $user->setRoles($roles);

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return new JsonResponse('User created', Response::HTTP_CREATED);
    }

    /**
     * @Route("/api/user/{username}", methods={"GET"})
     */
    public function showAction($username)
    {
        $user = $this->getDoctrine()->getRepository('AppBundle:User')
            ->findOneBy(['username' => $username]);

        if (!$user)
        {
            return new JsonResponse('No user found with username '. $username
            , Response::HTTP_NOT_FOUND);
        }

        $data = [
            'username' => $user->getUsername(),
            'roles' => $user->getRoles()
        ];

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/api/user/{name}", methods={"PUT", "PATCH"})
     */
    public function updateAction($name, Request $request)
    {
        $user = $this->getDoctrine()->getRepository('AppBundle:User')
            ->findOneBy(['username' => $name]);

        if (!$user)
        {
            return new JsonResponse('No user found with username '. $name
                , Response::HTTP_NOT_FOUND);
        }

        $username = $request->get('username');
        $plainPassword = $request->get('plainPassword');
        $roles[] = $request->get('roles');

        if (!empty($username)) {
            $user->setUsername($username);
        }

        if (!empty($plainPassword)) {
            $user->setPlainPassword($plainPassword);
        }

        if (!empty($roles[0])) {
            $user->setRoles($roles);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return new JsonResponse('User updated', Response::HTTP_OK);
    }

    /**
     * @Route("/api/user/{username}", methods={"DELETE"})
     */
    public function deleteAction($username)
    {
        $user = $this->getDoctrine()->getRepository('AppBundle:User')
            ->findOneBy(['username' => $username]);

        if ($user)
        {
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();
        }

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}