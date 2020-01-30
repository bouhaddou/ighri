<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class EcommerceController extends AbstractController
{
    /**
     * @Route("/produits", name="produitspage")
     */
    public function index()
    {
        return $this->render('produits/index.html.twig', [ ]);
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
