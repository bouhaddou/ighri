<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Posts;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UsersController extends AbstractController
{


    /**
     * @Route("/users/{id}", name="showprofile")
     * 
     */
    public function showprofile( $id)
    {
        $em = $this->getDoctrine()->getRepository(User::class);
        $users = $em->findOneBy(['id' => $id]);
        
        return $this->render('user/profile.html.twig', [
            'user' => $users
        ]);
    }
}
