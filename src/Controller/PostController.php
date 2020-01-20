<?php

namespace App\Controller;

use App\Entity\Posts;
use App\Entity\Vedio;
use App\Form\PostType;
use App\Entity\Comments;
use App\Form\CommentType;
use App\Service\Pagination;
use App\Form\CommentHomeType;
use App\Repository\PostsRepository;
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
    public function index(Pagination $pagination,$page,ObjectManager $manger,Request $request)
    {
        $pagination->setEntityClass(Posts::class)
                    ->setPage($page)
                    ->setLimit(10);

        $forms = [];
        foreach ( $pagination->getData() as $post ){
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
        return $this->render('post/ArticlePost.html.twig',[
            'pagination' => $pagination,
            'form' => $forms
        ]);
}













    /**
     * @Route("/post/image/{page<\d+>?1}", name="imagepage")
     */
    public function indeximage(Pagination $pagination, $page)
    {
        $pagination->setEntityClass(Posts::class)
            ->setPage($page)
            ->setLimit(12);

        return $this->render('home/image.html.twig', [
            'pagination' => $pagination,
            
        ]);
    }

     /**
     * @Route("/post/vedio/{page<\d+>?1}", name="vediopage")
     */
    public function indexvedio(Pagination $pagination, $page)
    {
        $pagination->setEntityClass(Vedio::class)
            ->setPage($page)
            ->setLimit(12);

        return $this->render('home/vedio.html.twig', [
            'pagination' => $pagination,
            
        ]);
    }

    /**
     * @Route("/annonce/{id}", name="showpostpage")
     */
    public function showpost(Posts $post,$id,Request $request,ObjectManager $manager)
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
                ' vous avez commenter avec success merci bien'
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
            'users' => $resultUsers
        ]);
    }

    /**
     * @Route("/posts/new", name="newpostpage")
     * @IsGranted("ROLE_USER")
     */
    public function newpost(Request $request)
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
                ' vous avez publier une annonce  avec success merci bien :) '
            );
            return  $this->redirectToRoute("showpostpage", array('id' => $post->getId()));

        }

        return $this->render('post/AdPost.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/posts/{id}/edit", name="editpostpage")
     * @IsGranted("ROLE_USER")
     */
    public function editpost(Request $request,Posts $post)
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
                ' votre  annonces de ighri à été  modifier  avec success   '
            );
           return  $this->redirectToRoute("showpostpage",array('id'=> $post->getId()));
        }

        return $this->render('post/edit.html.twig', [
            'form' => $form->createView()
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
            ' votre annonce  à été bien supprimer  '
        );

        return  $this->redirectToRoute("postpage");
    }
}
