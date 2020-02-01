<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Service\Pagination;
use App\Repository\CategorieRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin")
 */
class AdminCategorieController extends AbstractController
{
    /**
     * @Route("/categorie/{page<\d+>?1}", name="categorie_index", methods={"GET"})
     */
    public function index(CategorieRepository $categorieRepository,$page,Pagination $pagination,ObjectManager $manger): Response
    {
        $pagination->setEntityClass(Categorie::class)
        ->setPage($page)
        ->setRoute('categorie_index')
        ->setLimit(6);
        $contact = $manger->createQuery(" SELECT count(c) FROM App\Entity\Contact c WHERE c.valide = false ")->getSingleScalarResult();
        return $this->render('admin/categorie/index.html.twig', [
            'paginations' => $pagination,
            'stats'  => compact('contact'),
        ]);
    }

    /**
     * @Route("/categorie/new", name="categorie_new", methods={"GET","POST"})
     */
    public function new(Request $request,ObjectManager $manger): Response
    {
        $contact = $manger->createQuery(" SELECT count(c) FROM App\Entity\Contact c WHERE c.valide = false ")->getSingleScalarResult();
        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($categorie);
            $entityManager->flush();

            return $this->redirectToRoute('categorie_index');
        }

        return $this->render('admin/categorie/new.html.twig', [
            'categorie' => $categorie,
            'form' => $form->createView(),
            'stats'  => compact('contact'),

        ]);
    }

    /**
     * @Route("/categorie/show/{id}", name="categorie_show", methods={"GET"})
     */
    public function show(Categorie $categorie,ObjectManager $manger): Response
    {
        $contact = $manger->createQuery(" SELECT count(c) FROM App\Entity\Contact c WHERE c.valide = false ")->getSingleScalarResult();
        return $this->render('admin/categorie/show.html.twig', [
            'categorie' => $categorie,
            'stats'  => compact('contact'),
        ]);
    }

    /**
     * @Route("/categorie/{id}/edit", name="categorie_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Categorie $categorie,ObjectManager $manger): Response
    {
        $contact = $manger->createQuery(" SELECT count(c) FROM App\Entity\Contact c WHERE c.valide = false ")->getSingleScalarResult();
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('categorie_index');
        }

        return $this->render('admin/categorie/edit.html.twig', [
            'categorie' => $categorie,
            'form' => $form->createView(),
            'stats'  => compact('contact'),
        ]);
    }

    /**
     * @Route("/categorie/{id}", name="categorie_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Categorie $categorie): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categorie->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($categorie);
            $entityManager->flush();
        }

        return $this->redirectToRoute('categorie_index');
    }
}
