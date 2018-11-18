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

    /**
     * @Route("/page{page}", name="page_route", requirements={"page": "1|2|3"})
     */
    public function pageAction($page)
    {
        return $this->render('pages/page.html.twig', [
            'page' => $page
        ]);
    }
}
