<?php

namespace App\Controller;
use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\ContactRepository;
use App\Repository\CategorieRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contactpage")
     */
    public function index( Request $request,CategorieRepository $categorie )
    {
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
            return $this->redirectToRoute("contactpage");
        }
        return $this->render('contact/contact.html.twig', [
            'form' => $form->createView(),
            'categories' => $categorie->findAll(),

        ]);
    }

    
}
