<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\RegisterType;
use App\Service\Pagination;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminUserController extends AbstractController
{

    private $encoder;
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * @Route("/admin/user/{page<\d+>?1}", name="user_index")
     */
    public function index($page, Pagination $pagination,ObjectManager $manger)
    {

        $pagination->setEntityClass(User::class)
            ->setPage($page)
            ->setRoute('admin_user')
            ->setLimit(15);
            $contact = $manger->createQuery(" SELECT count(c) FROM App\Entity\Contact c WHERE c.valide = false ")->getSingleScalarResult();

        return $this->render('admin/user/index.html.twig', [
            'stats'  => compact('contact'),
            'users' => $pagination
        ]);
    }


    /**
     * @Route("/admin/user/new", name="user_new", methods={"GET","POST"})
     */
    public function new(Request $request,ObjectManager $manger): Response
    {
        $contact = $manger->createQuery(" SELECT count(c) FROM App\Entity\Contact c WHERE c.valide = false ")->getSingleScalarResult();
        $user = new User();
         $form = $this->createForm(RegisterType::class,$user);
         $form->handleRequest($request);

         if($form->isSubmitted() && $form->isValid())
         {
             
             $em = $this->getDoctrine()->getManager();
             $pass=$this->encoder->encodePassword($user,$user->getPasswordUser());
             $user->setPasswordUser($pass);
             $file= $user->getAvatar();
            if(!is_null($file))
            {
                $filename=md5(uniqid()).'.'.$file->guessExtension();
             $file->move($this->getParameter('upload_directory'),$filename);
            }else{
                $filename ='userIghri.jpg';
            }
             $user->setAvatar($filename);
             $em->persist($user);
             $em->flush();
             $this->addFlash(
                'success',
                "  l'inscription a été  effectuée avec succès.  "
            );
             return $this->redirectToRoute('user_index');
         }

        return $this->render('admin/user/new.html.twig', [
            'user' => $user,
            'stats'  => compact('contact'),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/user/{id}/show", name="user_show", methods={"GET"})
     */
    public function show(User $user,ObjectManager $manger): Response
    {
        $contact = $manger->createQuery(" SELECT count(c) FROM App\Entity\Contact c WHERE c.valide = false ")->getSingleScalarResult();

        return $this->render('admin/user/show.html.twig', [
            'stats'  => compact('contact'),
            'user' => $user
        ]);
    }

    /**
     * @Route("/admin/user/{id}/edit", name="user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user,ObjectManager $manger): Response
    {
        $contact = $manger->createQuery(" SELECT count(c) FROM App\Entity\Contact c WHERE c.valide = false ")->getSingleScalarResult();

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('admin/user/edit.html.twig', [
            'user' => $user,
            'stats'  => compact('contact'),

            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/user/{id}/delete", name="user_delete", methods={"DELETE"})
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_index');
    }
}
