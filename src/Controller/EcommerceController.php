<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class EcommerceController extends AbstractController
{
    /**
     * @Route("/ecommerce", name="ecommercepage")
     */
    public function index()
    {
        return $this->render('ecommerce/index.html.twig', [
            'controller_name' => 'EcommerceController',
        ]);
    }
    /**
     * @Route("/ecommerce/festival", name="festivalpage")
     */
    public function indexfestival()
    {
        return $this->render('ecommerce/festival.html.twig', [
            'controller_name' => 'EcommerceController',
        ]);
    }
}
