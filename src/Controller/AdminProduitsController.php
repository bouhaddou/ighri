<?php

namespace App\Controller;

use App\Entity\Produits;
use App\Form\ProduitsType;
use App\Service\Pagination;
use App\Repository\ProduitsRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin")
 */
class AdminProduitsController extends AbstractController
{
    /**
     * @Route("/produits/{page<\d+>?1}", name="produits_index", methods={"GET"})
     */
    public function index(ProduitsRepository $produitsRepository,$page,Pagination $pagination,ObjectManager $manger): Response
    {
        $pagination->setEntityClass(Produits::class)
        ->setPage($page)
        ->setRoute('produits_index')
        ->setLimit(6);
        $contact = $manger->createQuery(" SELECT count(c) FROM App\Entity\Contact c WHERE c.valide = false ")->getSingleScalarResult();
        return $this->render('admin/produits/index.html.twig', [
            'paginations' => $pagination,
            'stats'  => compact('contact'),
        ]);
    }

    /**
     * @Route("/produits/new", name="produits_new", methods={"GET","POST"})
     */
    public function new(Request $request,ObjectManager $manger): Response
    {
        $contact = $manger->createQuery(" SELECT count(c) FROM App\Entity\Contact c WHERE c.valide = false ")->getSingleScalarResult();

        $produit = new Produits();
        $form = $this->createForm(ProduitsType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form['image']->getData();
            $filename = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move($this->getParameter('upload_directory_produit'), $filename);
            $produit->setImage($filename);
            $manger->persist($produit);
            $manger->flush();
            $this->addFlash(
                'success',
                ' votre  Produit à été Ajouter avec succès  '
            );
            return $this->redirectToRoute('produits_index');
        }
        return $this->render('admin/produits/new.html.twig', [
            'produit' => $produit,
            'form' => $form->createView(),
            'stats'  => compact('contact'),

        ]);
    }

    /**
     * @Route("/produits/show/{id}", name="produits_show", methods={"GET"})
     */
    public function show(Produits $produit,ObjectManager $manger): Response
    {
        $contact = $manger->createQuery(" SELECT count(c) FROM App\Entity\Contact c WHERE c.valide = false ")->getSingleScalarResult();
        return $this->render('admin/produits/show.html.twig', [
            'produit' => $produit,
            'stats'  => compact('contact'),

        ]);
    }

    /**
     * @Route("/produits/{id}/edit", name="produits_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Produits $produit,ObjectManager $manger): Response
    {
        $contact = $manger->createQuery(" SELECT count(c) FROM App\Entity\Contact c WHERE c.valide = false ")->getSingleScalarResult();
        $form = $this->createForm(ProduitsType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form['image']->getData();
            $filename = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move($this->getParameter('upload_directory_produit'), $filename);
            $produit->setImage($filename);
            $manger->persist($produit);
            $manger->flush();
            $this->addFlash(
                'success',
                ' votre  Produit à été Modifier avec succès  '
            );

            return $this->redirectToRoute('produits_index');
        }

        return $this->render('admin/produits/edit.html.twig', [
            'produit' => $produit,
            'form' => $form->createView(),
            'stats'  => compact('contact'),
        ]);
    }

    /**
     * @Route("/produits/{id}", name="produits_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Produits $produit,ObjectManager $manger): Response
    {
        $contact = $manger->createQuery(" SELECT count(c) FROM App\Entity\Contact c WHERE c.valide = false ")->getSingleScalarResult();

        if ($this->isCsrfTokenValid('delete'.$produit->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($produit);
            $entityManager->flush();
        }

        return $this->redirectToRoute('produits_index');
    }
}
