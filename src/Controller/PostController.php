<?php

namespace App\Controller;

use App\Entity\Posts;
use App\Entity\Vedio;
use App\Form\PostType;
use App\Form\VedioType;
use App\Entity\Comments;
use App\Form\CommentType;
use App\Service\Pagination;
use App\Form\CommentHomeType;
use App\Repository\PostsRepository;
use App\Repository\CategorieRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PostController extends AbstractController
{
    
    /**
     * @Route("/post/{page<\d+>?1}", name="postpage")
     */
    public function index(Pagination $pagination,$page,ObjectManager $manger,Request $request,CategorieRepository $categorie)
    {
        $pagination->setEntityClass(Posts::class)
                    ->setPage($page)
                    ->setLimit(30);
                    $posts =$manger->createQuery(
                        'SELECT  p
                        FROM App\Entity\Posts p
                        ORDER BY p.id DESC '
                    )->setMaxResults(30)->getResult();

        $forms = [];
        foreach ( $pagination->getData() as $post ){
            $entity = new Comments();
            array_push($forms,$this->createForm(CommentHomeType::class, $entity)->createView());
        }
           //---------------------------------------------users
           $rows = $manger->createQuery('SELECT COUNT(u.id) 
           FROM App\Entity\User u')
           ->getSingleScalarResult();
   
           // calculate a random offset
           $offs = max(0, rand(0, $rows - 15 - 1));
           //Get the first $n rows(users) starting from a random point
           $queryUser = $manger->createQuery('
           SELECT DISTINCT u
           FROM App\Entity\User u')
           ->setMaxResults(15)
           ->setFirstResult($offs);
           $resultUsers = $queryUser->getResult(); 

           $rows = $manger->createQuery('SELECT COUNT(p.id) 
           FROM App\Entity\Posts p')
           ->getSingleScalarResult();
   
           // calculate a random offset
           $offset = max(0, rand(0, $rows - 15 - 1));
           //Get the first $n rows(users) starting from a random point
           $query = $manger->createQuery('
           SELECT DISTINCT p
           FROM App\Entity\Posts p ')
           ->setMaxResults(15)
           ->setFirstResult($offset);
           $result = $query->getResult(); 

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
        return $this->render('post/ArticlePost.html.twig',[
            'pagination' => $pagination,
            'posts' => $posts,
            'form' => $forms,
            'TopPosts' => $result,
            'users' => $resultUsers,
            'categories' => $categorie->findAll(),

        ]);
}

    /**
     * @Route("/post/image/{page<\d+>?1}", name="imagepage")
     */
    public function indeximage(Pagination $pagination, $page,CategorieRepository $categorie)
    {
        $pagination->setEntityClass(Posts::class)
            ->setPage($page)
            ->setLimit(12);

        return $this->render('home/image.html.twig', [
            'pagination' => $pagination,
            'categories' => $categorie->findAll(),
            
        ]);
    }

    /**
     * @Route("/post/vedio/new", name="postVedioNew")
     */
    public function indexvedionew(Request $request,CategorieRepository $categorie)
    {
            $vedio = new Vedio();
            $form = $this->createForm(VedioType::class, $vedio);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $vedio->setAuthor($this->getUser());
                $type = $request->get("typeName");
                $vedio->setType($type);
                $entityManager->persist($vedio);
                $entityManager->flush();
                $this->addFlash(
                    'success',
                    " votre publication a été enregistré en attendant l'approbation de l'administration <br>
                     تم حفظ المنشور في انتظار موافقة الإدارة"
                );
                 
                return $this->redirectToRoute('vediopage');
            }
        return $this->render('home/newVedio.html.twig', [
            'form' => $form->createView(),
            'categories' => $categorie->findAll(),

        ]);
    }
     /**
     * @Route("/post/vedio/{page<\d+>?1}", name="vediopage")
     */
    public function indexvedio(Pagination $pagination, $page,Request $request,CategorieRepository $categorie)
    {
        $pagination->setEntityClass(Vedio::class)
            ->setPage($page)
            ->setLimit(20);
        
        return $this->render('home/vedio.html.twig', [
            'categories' => $categorie->findAll(),
            'pagination' => $pagination,
           
        ]);
    }

    /**
     * @Route("/publication/{id}", name="showpostpage")
     */
    public function showpost(Posts $post,$id,Request $request,ObjectManager $manager,CategorieRepository $categorie)
    {
        $coment = new Comments();
        $form = $this->createForm(CommentType::class,$coment); 
        $em = $this->getDoctrine()->getRepository(Posts::class);
        $posts = $em->findOneBy(['id' => $id]);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid() )
        {
            $coment->setPost($post);
            $coment->setAuthor($this->getUser());
            $manager->persist($coment);
            $manager->flush();
            $this->addFlash(
                'success',
                ' vous avez commenter avec succès merci bien'
            );
            return  $this->redirectToRoute("showpostpage", array('id' => $post->getId()));

        }

        $rows = $manager->createQuery('SELECT COUNT(p.id) 
        FROM App\Entity\Posts p')
        ->getSingleScalarResult();

        // calculate a random offset
        $offset = max(0, rand(0, $rows - 3 - 1));
        //Get the first $n rows(users) starting from a random point
        $query = $manager->createQuery('
        SELECT DISTINCT p
        FROM App\Entity\Posts p ')
        ->setMaxResults(3)
        ->setFirstResult($offset);
        $result = $query->getResult(); 
        //---------------------------------------------users
        $rows = $manager->createQuery('SELECT COUNT(u.id) 
        FROM App\Entity\User u')
        ->getSingleScalarResult();

        // calculate a random offset
        $offs = max(0, rand(0, $rows - 6 - 1));
        //Get the first $n rows(users) starting from a random point
        $queryUser = $manager->createQuery('
        SELECT DISTINCT u
        FROM App\Entity\User u')
        ->setMaxResults(6)
        ->setFirstResult($offs);
        $resultUsers = $queryUser->getResult(); 
        return $this->render('post/show.html.twig', [
            'posts' => $posts,
            'form' => $form->createView(),
            'TopPosts' => $result,
            'categories' => $categorie->findAll(),
            'users' => $resultUsers,
        ]);
    }

    /**
     * @Route("/posts/new", name="newpostpage")
     * @IsGranted("ROLE_USER")
     */
    public function newpost(Request $request,CategorieRepository $categorie)
    {
        $post = new Posts();
       
        $form = $this->createForm(PostType::class,$post);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            foreach( $post->getImages() as $image)
            {
                $file = $image->getUrl();
                $filename = md5(uniqid()) . '.' . $file->guessExtension();
                $file->move($this->getParameter('upload_directory_image'), $filename);
                $image->setUrl($filename);

                $image->setPosts($post);
                $em->persist($image);
            }
            $file = $post->getAvatar();
            $filename = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move($this->getParameter('upload_directory_post'), $filename);
            $post->setAvatar($filename);

            $post->setAuthor($this->getUser());
            $em->persist($post);
            $em->flush();
            $this->addFlash(
                'success',
                '  votre  publication a été  enregisterer  avec succès merci  '
            );
            return  $this->redirectToRoute("showpostpage", array('id' => $post->getId()));

        }

        return $this->render('post/AdPost.html.twig', [
            'categories' => $categorie->findAll(),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/posts/{id}/edit", name="editpostpage")
     * @IsGranted("ROLE_USER")
     */
    public function editpost(Request $request,Posts $post,CategorieRepository $categorie)
    {

        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) 
        {
            $em = $this->getDoctrine()->getManager();
            foreach ($post->getImages() as $image) {
                $file = $image->getUrl();
                $filename = md5(uniqid()) . '.' . $file->guessExtension();
                $file->move($this->getParameter('upload_directory_image'), $filename);
                $image->setUrl($filename);

                $image->setPosts($post);
                $em->persist($image);
            }
            $file = $post->getAvatar();
            $filename = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move($this->getParameter('upload_directory_post'), $filename);
            $post->setAvatar($filename);

            $post->setAuthor($this->getUser());
            $em->persist($post);
            $em->flush();
            $this->addFlash(
                'success',
                ' votre  publication de ighri à été  modifiée  avec succès   '
            );
           return  $this->redirectToRoute("showpostpage",array('id'=> $post->getId()));
        }

        return $this->render('post/edit.html.twig', [
            'form' => $form->createView(),
            'categories' => $categorie->findAll(),

        ]);
    }

    /**
     * @Route("/posts/{id}/delete", name="deletepostpage")
     * @IsGranted("ROLE_USER")
     */
    public function deletepost(Request $request, PostsRepository $repo , $id)
    {
     
    $result = $repo->findOneById($id);
    $em= $this->getDoctrine()->getManager();

        $em->remove($result);
        $em->flush();
        $this->addFlash(
            'danger',
            ' votre publication  à été bien supprimée  '
        );

        return  $this->redirectToRoute("postpage");
    }
}
