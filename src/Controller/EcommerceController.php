<?php

namespace App\Controller;

use App\Entity\Produits;
use App\Service\Pagination;
use App\Repository\ProduitsRepository;
use App\Repository\CategorieRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
     * @Route("/All/{page<\d+>?1}", name="AllProduits")
     */
    public function AllProduits(CategorieRepository $categorie,Pagination $pagination,$page,ObjectManager $manger,Request $request)
    { 
        $pagination->setEntityClass(Produits::class)
                    ->setPage($page)
                    ->setLimit(30);

        return $this->render('produits/pages/produits.html.twig', [ 
            'categories' => $categorie->findAll(),
            'pagination' => $pagination
        ]);
    }
    
    /**
     * @Route("/details/{id}", name="DetailsProduits")
     */
    public function DetailsProduits(ProduitsRepository $produit,$id,ObjectManager $manger,Request $request)
    { 
        return $this->render('produits/pages/produit-single.html.twig', [ 
            'produit' => $produit->findOneBy([ 'id' => $id])
            
        ]);
    }

    
}
