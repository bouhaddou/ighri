<?php

namespace App\Controller;

use App\Entity\Clients;
use App\Entity\Ventes;
use App\Form\ClientsType;
use App\Repository\CategorieRepository;
use App\Repository\ProduitsRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class VenteController extends AbstractController
{
 
    /**
     * @Route("/chekout/{montant}", name="chekout_page")
     */
    public function index($montant,SessionInterface $session,ProduitsRepository $produits,CategorieRepository $categorie,ObjectManager $manager,Request $request)
    { 
        $client = new Clients();
        $form = $this->createForm(ClientsType::class,$client);
        $form->handleRequest($request);
        $panier = $session->get('panier',[]);
        
        if($form->isSubmitted() && $form->isValid())
        {
            $pays = $request->get('pays');
            $client->setPays($pays); 
            $manager->persist($client); 
            $mode = $request->get('optradio');
            $valider = $request->get('valider');  
                foreach( $panier as $id => $quantete)
                { 
                $vente = new Ventes();
                $produit = $produits->findOneBy(['id' => $id]);
                $vente->setClient($client)
                        ->setProduit($produit)
                        ->setPoids($quantete)
                        ->setModePaiement($mode)
                        ->setValider(true)
                        ->setPrix(100); 
                $manager->persist($vente); 
                }
                $manager->flush();
        }
        return $this->render('produits/pages/chekout.html.twig', [ 
            'categories' => $categorie->findAll(),
            'count' => count($panier),
            'form' => $form->createView(),
            'montant' => $montant,
        ]);
    }
}
