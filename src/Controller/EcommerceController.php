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

 $php2js = new Produits();
  
   
    
    /**
     * @Route("/produits")
     */
class EcommerceController extends AbstractController
{
     

    /**
     * @Route("/", name="produitspage")
     */
    public function index(CategorieRepository $categorie)
    {
        return $this->render('produits/pages/index.html.twig', [ 
            'categories' => $categorie->findAll(),
        ]);
    }
    
    /**
     * @Route("/All/{page<\d+>?1}", name="AllProduits")
     */
    public function AllProduits(CategorieRepository $categorie,Pagination $pagination,$page,ObjectManager $manger,Request $request)
    { 
        $pagination->setEntityClass(Produits::class)
                    ->setPage($page)
                    ->setLimit(20);

        return $this->render('produits/pages/produits.html.twig', [ 
            'categories' => $categorie->findAll(),
            'produits' => $pagination->getData()
        ]);
    }
    
    /**
     * @Route("/details/{id}", name="DetailsProduits")
     */
    public function DetailsProduits(ProduitsRepository $produit,$id,CategorieRepository $categorie)
    { 
        return $this->render('produits/pages/produit-single.html.twig', [ 
            'produit' => $produit->findOneBy([ 'id' => $id]),
            'categories' => $categorie->findAll(),
        ]);
    }

     
    /**
     * @Route("/Categorie/{id}/produit", name="SearcheProduits")
     */
    public function SearchByCategorie(ProduitsRepository $produits,$id,CategorieRepository $categorie)
    { 
        $result=$categorie->findOneBy([ 'id' => $id]);
            dd($produits->findProduitByCategorie( $result));
        return $this->render('produits/pages/produitParCategorie.html.twig', [ 
            'categories' => $categorie->findAll(),
            'produits' => $produits->findProduitByCategorie( $result)
        ]);
    }

     /**
     * @Route("/cart/{id}/produit", name="CartProduits")
     */
    public function CartProduit(ProduitsRepository $produits,$id,ObjectManager $manager)
    { 
        
        $php2js=$produits->findOneBy([ 'id' => $id]);
        $manager->persist($php2js);
        return $this->redirectToRoute('dd');
    }
    
}
