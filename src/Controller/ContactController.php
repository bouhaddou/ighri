<?php

namespace App\Controller;
use App\Entity\Contact;
use App\Form\ContactType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

use App\Repository\ContactRepository;


class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contactpage")
     */
    public function index( Request $request )
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
                ' vous   Message à été  envoyer  avec success merci bien pour votre temps   '
            );
            return $this->redirectToRoute("contactpage");
        }
        return $this->render('contact/contact.html.twig', [
            'form' => $form->createView()
        ]);
    }

    
}
