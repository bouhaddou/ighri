<?php

namespace App\Controller;

use App\Entity\Vedio;
use App\Form\VedioType;
use App\Form\VedioEditType;
use App\Repository\VedioRepository;
use App\Repository\CategorieRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin")
 */
class VedioController extends AbstractController
{
    /**
     * @Route("/vedios", name="vedio_index", methods={"GET"})
     */
    public function index(VedioRepository $vedioRepository,ObjectManager $manger,CategorieRepository $categorie): Response
    {
        $contact = $manger->createQuery(" SELECT count(c) FROM App\Entity\Contact c WHERE c.valide = false ")->getSingleScalarResult();
        
        return $this->render('admin/vedio/index.html.twig', [
            'vedios' => $vedioRepository->findAll(),
            'categories' => $categorie->findAll(),
            'stats'  => compact('contact'),
        ]);
    }



    /**
     * @Route("/vedios/{id}/show", name="vedio_show", methods={"GET"})
     */
    public function show(Vedio $vedio,ObjectManager $manger,CategorieRepository $categorie): Response
    {
        $contact = $manger->createQuery(" SELECT count(c) FROM App\Entity\Contact c WHERE c.valide = false ")->getSingleScalarResult();
        
        return $this->render('admin/vedio/show.html.twig', [
            'vedio' => $vedio,
            'categories' => $categorie->findAll(),
            'stats'  => compact('contact'),
        ]);
    }

    /**
     * @Route("/vedios/{id}/edit", name="vedio_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Vedio $vedio,ObjectManager $manger,CategorieRepository $categorie): Response
    {
        $contact = $manger->createQuery(" SELECT count(c) FROM App\Entity\Contact c WHERE c.valide = false ")->getSingleScalarResult();
        
        $form = $this->createForm(VedioEditType::class, $vedio);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $type = $request->get("typeName");
            $vedio->setType($type);
            $entityManager->persist($vedio);
            $entityManager->flush();
            return $this->redirectToRoute('vedio_index');
        }


        return $this->render('admin/vedio/edit.html.twig', [
            'vedio' => $vedio,
            'stats'  => compact('contact'),
            'categories' => $categorie->findAll(),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/vedios/{id}", name="vedio_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Vedio $vedio): Response
    {
        if ($this->isCsrfTokenValid('delete'.$vedio->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($vedio);
            $entityManager->flush();
        }

        return $this->redirectToRoute('vedio_index');
    }
}
