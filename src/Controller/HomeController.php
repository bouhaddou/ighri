<?php

namespace App\Controller;

use App\Entity\Posts;
use App\Entity\Safran;
use App\Form\TestType;
use App\Entity\Comments;
use App\Entity\Compteur;
use App\Form\SafranType;
use App\Form\CommentHomeType;
use App\Repository\CompteurRepository;
use App\Repository\VedioRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Egulias\EmailValidator\Warning\Comment;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index(ObjectManager $manger,Request $request,CompteurRepository $repo,VedioRepository $vedioRepository )
    {
        
        $posts =$manger->createQuery(
            'SELECT  p
            FROM App\Entity\Posts p
            ORDER BY p.id DESC '
        )->setMaxResults(5)->getResult();
        
        $compteur = $repo->findOneBy(['id' => 8 ]);
        
        if(is_null($compteur))
        {
            $cpt = new Compteur();
            $cpt->setCpt(1);
            $manger->persist($cpt);
            $manger->flush();
            $compteur = $repo->findOneBy(['id' => 1 ]);
        }else{
            if($this->getUser()){ $dd=$this->getUser()->getRoles() ; 
                $cc = 0;
                foreach ( $dd as $val) 
                { if($val == "ROLE_ADMIN")
                    {  $cc= $cc + 1 ;  }}
                if( $cc == 0 )
                {
                $zz = $this->getDoctrine()->getManager();
                $compteur->setCpt($compteur->getCpt() + 1 );
                $zz->persist($compteur);
                $zz->flush();
            }
            }else{
                $zz = $this->getDoctrine()->getManager();
                $compteur->setCpt($compteur->getCpt() + 1 );
                $zz->persist($compteur);
                $zz->flush();
            }
        }
        $forms = [];
        foreach ( $posts as $post ){
            $entity = new Comments();
            array_push($forms,$this->createForm(CommentHomeType::class, $entity)->createView());
        }
       

       if($request->isMethod("POST")){
           $comt = new Comments();
           $comment = $request->get("comment_home");
         
           $postid = $request->get("postid");
         $em = $this->getDoctrine()->getRepository(Posts::class);
         $po = $em->findOneBy(['id' => $postid]);
      
         $comt->setComment($comment['comment'])
                ->setPost($po)
                ->setAuthor($this->getUser());
        $manger->persist($comt);
        $manger->flush();
        
       }
       $bestpost = $manger->createQuery(
        'SELECT p
        FROM App\Entity\Posts p
        ORDER BY p.id ASC '

    )->setMaxResults(3)->getResult();

        $rows = $manger->createQuery('SELECT COUNT(p.id) 
        FROM App\Entity\Posts p')
        ->getSingleScalarResult();

        // calculate a random offset
        $offset = max(0, rand(0, $rows - 3 - 1));
        //Get the first $n rows(users) starting from a random point
        $query = $manger->createQuery('
        SELECT DISTINCT p
        FROM App\Entity\Posts p ')
        ->setMaxResults(3)
        ->setFirstResult($offset);
        $result = $query->getResult(); 
        //---------------------------------------------users
        $rows = $manger->createQuery('SELECT COUNT(u.id) 
        FROM App\Entity\User u')
        ->getSingleScalarResult();

        // calculate a random offset
        $offs = max(0, rand(0, $rows - 6 - 1));
        //Get the first $n rows(users) starting from a random point
        $queryUser = $manger->createQuery('
        SELECT DISTINCT u
        FROM App\Entity\User u')
        ->setMaxResults(6)
        ->setFirstResult($offs);
        $resultUsers = $queryUser->getResult(); 

     
    
        return $this->render('home/index.html.twig',[
            'posts' => $posts,
            'compteur' => $compteur,
            'form' => $forms,
            'TopPosts' => $result,
            'users' => $resultUsers,
            'vedios' => $vedioRepository->findAll(),
        ]);
    }











    /**
     * @Route("/presentation", name="presentationpage")
     */
    public function presentation()
    {
        return $this->render('home/presentation.html.twig');
    }
    /**
     * @Route("/agricule", name="agriculepage")
     */
    public function agricule()
    {
        return $this->render('home/agricule.html.twig');
    }
    
    /**
     * @Route("/zafran", name="zafranpage")
     */
    public function zafran(Request $request,ObjectManager $manger)
    {  
        $em = $this->getDoctrine()->getRepository(Safran::class);
        $saf = $em->findAll();

        $safrane = new Safran();
        $form = $this->createForm(SafranType::class,$safrane);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $file = $form['avatar']->getData();
            $filename = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move($this->getParameter('upload_directory_safran'), $filename);
            
            $safrane->setAvatar($filename)
                    ->setAuthor($this->getUser())
                    ->setAvatar($filename)
                    ->setTitre($safrane->getTitre());
            $em->persist($safrane);
            $em->flush();

            $this->addFlash(
                'success',
                'vous avez publier une photos de safran  avec success merci bien'
            );
            return $this->redirectToRoute('zafranpage');

        }
        
        $rows = $manger->createQuery('SELECT COUNT(p.id) 
        FROM App\Entity\Posts p')
        ->getSingleScalarResult();

        // calculate a random offset
        $offset = max(0, rand(0, $rows - 2 - 1));
        //Get the first $n rows(users) starting from a random point
        $query = $manger->createQuery('
        SELECT DISTINCT p
        FROM App\Entity\Posts p ')
        ->setMaxResults(2)
        ->setFirstResult($offset);
        $result = $query->getResult(); 
        //---------------------------------------------users
        $posts =$manger->createQuery(
            'SELECT  p
            FROM App\Entity\Posts p
            ORDER BY p.id DESC '
        )->setMaxResults(5)->getResult();
        return $this->render('home/zafran.html.twig', [
            'safrans' => $saf,
            'posts' => $posts,
            'form' => $form->createView(),
            'TopPosts' => $result,
            ]);
    }

    /**
     * @Route("/association", name="associationpage")
     */
    public function indexassociation()
    {
        return $this->render('home/association.html.twig');
    }
     
}
