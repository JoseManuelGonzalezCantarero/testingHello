<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PagesController extends Controller
{
    /**
     * @Route("/home", name="home_route")
     */
    public function homeAction()
    {
        return $this->render('pages/home.html.twig', [

        ]);
    }
}
