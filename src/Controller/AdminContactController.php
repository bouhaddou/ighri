<?php

namespace App\Controller;

use App\Service\Pagination;
use App\Entity\Contact;
use App\Repository\ContactRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


class AdminContactController extends AbstractController
{
   
    /**
     * @Route("/admin/contact/{page<\d+>?1}", name="admin_contact")
     */
    public function index($page, Pagination $pagination,ObjectManager $manger)
    {

        $pagination->setEntityClass(Contact::class)
            ->setPage($page)
            ->setRoute('admin_contact')
            ->setLimit(10);
        $contact = $manger->createQuery(" SELECT count(c) FROM App\Entity\Contact c WHERE c.valide = false ")->getSingleScalarResult();
        

        return $this->render('admin/contact/index.html.twig', [
            'stats'  => compact('contact'),
            'pagination' => $pagination
        ]);
    }

     /**
     * @Route("/admin/contact/{id}/delete", name="deletecontact")
     * @IsGranted("ROLE_USER")
     */
    public function deletecontact(Request $request, ContactRepository $repo , $id)
    {
     
    $result = $repo->findOneById($id);
    $em= $this->getDoctrine()->getManager();

        $em->remove($result);
        $em->flush();
        $this->addFlash(
            'danger',
            ' le Message à été bien supprimer correctement '
        );

        return  $this->redirectToRoute("admin_contact");
    }
}
