<?php

namespace App\Controller;

use App\Entity\Clients;
use App\Form\Clients1Type;
use App\Service\Pagination;
use App\Repository\ClientsRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin")
 */
class AdminClientsController extends AbstractController
{
    /**
     * @Route("/clients/{page<\d+>?1}", name="clients_index", methods={"GET"})
     */
    public function index(ClientsRepository $clientsRepository,$page,Pagination $pagination,ObjectManager $manger): Response
    {
        $pagination->setEntityClass(Clients::class)
        ->setPage($page)
        ->setRoute('clients_index')
        ->setLimit(6);
        $contact = $manger->createQuery(" SELECT count(c) FROM App\Entity\Contact c WHERE c.valide = false ")->getSingleScalarResult();
        return $this->render('admin/clients/index.html.twig', [
            'paginations' => $pagination,
            'stats'  => compact('contact'),
        ]);
    }


    /**
     * @Route("/clients/show/{id}", name="clients_show", methods={"GET"})
     */
    public function show(Clients $client,ObjectManager $manger): Response
    {
        $contact = $manger->createQuery(" SELECT count(c) FROM App\Entity\Contact c WHERE c.valide = false ")->getSingleScalarResult();
        return $this->render('admin/clients/show.html.twig', [
            'client' => $client,
            'stats'  => compact('contact'),

        ]);
    }

    /**
     * @Route("/clients/{id}/edit", name="clients_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Clients $client): Response
    {
        $form = $this->createForm(Clients1Type::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('clients_index');
        }

        return $this->render('admin/clients/edit.html.twig', [
            'client' => $client,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/clients/{id}", name="clients_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Clients $client): Response
    {
        if ($this->isCsrfTokenValid('delete'.$client->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($client);
            $entityManager->flush();
        }

        return $this->redirectToRoute('clients_index');
    }
}
