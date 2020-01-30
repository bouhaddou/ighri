<?php

namespace App\Controller;

use App\Repository\CategorieRepository;
use App\Repository\ProduitsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

    /**
     * @Route("/produits")
     */
class EcommerceController extends AbstractController
{
    /**
     * @Route("/", name="produitspage")
     */
    public function index()
    {
        return $this->render('produits/pages/index.html.twig', [ ]);
    }
    
    /**
     * @Route("/All", name="AllProduits")
     */
    public function details(CategorieRepository $categorie)
    {
        return $this->render('produits/pages/produits.html.twig', [ 
            'categories' => $categorie->findAll(),
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
