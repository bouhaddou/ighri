<?php

namespace App\Controller;

use App\Entity\Amis;
use App\Entity\User;
use App\Entity\Posts;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AmisController extends AbstractController
{
    /**
     * @Route("/amis/{id}", name="amis_friend")
     */
    public function index($id)
    {
        $amis= new Amis();
        
        $em = $this->getDoctrine()->getRepository(User::class);
        $user=$em->findOneBy([ 'id' => $id]);
        $amis_envoi= $this->getUser();
        
        $relation='0';
        $zz= $this->getDoctrine()->getManager();
        $amis->setFriendEnvoi($amis_envoi)
             ->setFriendRecoit($user)
             ->setRelation($relation);
        $zz->persist($amis);
        $zz->flush();
       
        $es = $this->getDoctrine()->getRepository(Posts::class);
        $posts = $es->findAll();



        $es = $this->getDoctrine()->getRepository(Amis::class);
        $amiss = $es->findAll();

        return $this->render('post/post.html.twig', [
            'posts' => $posts,
            'amis' => $amiss
        ]);
    }
    /**
     * @Route("/accepter/{id}", name="accepter_invitation")
     */
    public function accepter($id, Amis $amis)
    {
        $zz = $this->getDoctrine()->getManager();

        $amis->setRelation('1');
        $zz->persist($amis);
        $zz->flush();

        $es = $this->getDoctrine()->getRepository(Posts::class);
        $posts = $es->findAll();



        $es = $this->getDoctrine()->getRepository(Amis::class);
        $amiss = $es->findAll();

        return $this->render('post/post.html.twig', [
            'posts' => $posts,
            'amis' => $amiss
        ]);
    }

    /**
     * @Route("/Allfriends/", name="allfriends")
     */
    public function allfriends()
    {
        $es = $this->getDoctrine()->getRepository(Amis::class);
        $amiss = $es->findAll();

        return $this->render('amis/amis.html.twig', [
            'amis' => $amiss
        ]);

    }
}
