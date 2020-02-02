<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\Produits;
use App\Form\ContactType;
use App\Service\Pagination;
use App\Repository\ProduitsRepository;
use App\Repository\CategorieRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
     * @Route("/produits")
     */
class EcommerceController extends AbstractController
{
    /**
     * @Route("/", name="produitspage")
     */
    public function index(CategorieRepository $categorie,SessionInterface $session,ObjectManager $manager)
    {
        $panier = $session->get('panier',[]);
        
         //---------------------------------------------categorie
         $rows = $manager->createQuery('SELECT COUNT(c.id) FROM App\Entity\Categorie c')->getSingleScalarResult();
         // calculate a random offset
         $offs = max(0, rand(0, $rows - 5 - 1));
         //Get the first $n rows(users) starting from a random point
         $queryUser = $manager->createQuery('SELECT DISTINCT c FROM App\Entity\Categorie c')
                               ->setMaxResults(5)
                               ->setFirstResult($offs);
         $categorieSpeciale = $queryUser->getResult(); 

        //---------------------------------------------produits
        $rows = $manager->createQuery('SELECT COUNT(p.id) FROM App\Entity\Produits p')->getSingleScalarResult();
        // calculate a random offset
        $offs = max(0, rand(0, $rows - 9 - 1));
        //Get the first $n rows(users) starting from a random point
        $queryUser = $manager->createQuery('SELECT DISTINCT p FROM App\Entity\Produits p')
                                ->setMaxResults(9)
                                ->setFirstResult($offs);
        $produitsSpeciale = $queryUser->getResult(); 
       
        return $this->render('produits/pages/index.html.twig', [ 
            'categories' => $categorie->findAll(),
            'catSpeciale' => $categorieSpeciale,
            'produits' => $produitsSpeciale,
            'count' => count($panier)
        ]);
    }
    
    /**
     * @Route("/All/{page<\d+>?1}", name="AllProduits")
     */
    public function AllProduits(ObjectManager $manager, CategorieRepository $categorie,SessionInterface $session,Pagination $pagination,$page,ObjectManager $manger,Request $request)
    { 
        $panier = $session->get('panier',[]);
        $pagination->setEntityClass(Produits::class)
                    ->setPage($page)
                    ->setLimit(20);
    $cat =$manager->createQuery(
            'SELECT  p
            FROM App\Entity\Categorie p
            ORDER BY p.id DESC '
        )->setMaxResults(3)->getResult();

        return $this->render('produits/pages/produits.html.twig', [ 
            'categories' => $categorie->findAll(),
            'produits' => $pagination->getData(),
            'pagination' => $pagination,
            'count' => count($panier),
            'cats' => $cat
        ]);
    }
    
    /**
     * @Route("/details/{id}", name="DetailsProduits")
     */
    public function DetailsProduits(ObjectManager $manager, ProduitsRepository $produits,SessionInterface $session,$id,CategorieRepository $categorie)
    { 
        $cat =$manager->createQuery(
            'SELECT  p
            FROM App\Entity\Produits p
            ORDER BY p.id DESC '
             )->setMaxResults(12)->getResult();
        $panier = $session->get('panier',[]);
        return $this->render('produits/pages/produit-single.html.twig', [ 
            'produit' => $produits->findOneBy([ 'id' => $id]),
            'categories' => $categorie->findAll(),
            'count' => count($panier),
            'produits' => $cat,
        ]);
    }

     
    /**
     * @Route("/Categorie/{id}/produit", name="SearcheProduits")
     */
    public function SearchByCategorie(ProduitsRepository $produits,SessionInterface $session,$id,CategorieRepository $categorie,ObjectManager $manager)
    { 
        $cat =$manager->createQuery(
            'SELECT  p
            FROM App\Entity\Categorie p
            ORDER BY p.id DESC '
        )->setMaxResults(3)->getResult();

        $panier = $session->get('panier',[]);
        $result=$categorie->findOneBy([ 'id' => $id]);
        return $this->render('produits/pages/produitParCategorie.html.twig', [ 
            'categories' => $categorie->findAll(),
            'produits' => $produits->findProduitByCategorie( $result),
            'count' => count($panier),
            'cats' => $cat
        ]);
    }

    /**
     * @Route("/cart", name="cart_page")
     */
    public function cartpage(SessionInterface $session,ProduitsRepository $produits,CategorieRepository $categorie)
    { 
        $panier = $session->get('panier',[]);
        $panierdata =[];
        foreach($panier as $id => $quantity)
        {
            $panierdata[] = [
                "produit" => $produits->find($id),
                "quantite" => $quantity
            ];
        }
        return $this->render('produits/pages/cart.html.twig', [ 
            'categories' => $categorie->findAll(),
            'produits' => $panierdata,
            'count' => count($panier)
        ]);
    }

     /**
     * @Route("/cart/{id}/produit", name="Cartadd")
     */
    public function add(SessionInterface $session,$id)
    { 
       $panier = $session->get('panier',[]);
       if(empty($panier[$id]))
       {
        $panier[$id] = 1;
       }else{
        $panier[$id]++;
       }
       $session->set('panier',$panier);
      return  $this->redirectToRoute('cart_page');
    }

    /**
     * @Route("/cart/{id}/remove", name="cart_remove")
     */
    public function remove(SessionInterface $session,$id)
    { 
       $panier = $session->get('panier',[]);

       if(!empty($panier[$id]))
       {
       unset($panier[$id]);
       }
       $session->set('panier',$panier);
      return  $this->redirectToRoute('cart_page');
    }
    
    

    /**
     * @Route("/contact_produit", name="produitContact")
     */
    public function produitContact( Request $request,SessionInterface $session,CategorieRepository $categorie)
    {
        $panier = $session->get('panier',[]);
        $contact = new Contact();
        $form=$this->createForm(ContactType::class,$contact);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $contact->setValide(false);
            $em->persist($contact);
            $em->flush();
            $this->addFlash(
                'success',
                ' Votre Message à été envoyé avec succès merci bien pour votre temps   '
            );
            return $this->redirectToRoute("produitContact");
        }
        return $this->render('produits/pages/contact.html.twig', [
            'form' => $form->createView(),
            'categories' => $categorie->findAll(),
            'count' => count($panier)
        ]);
    }
}
