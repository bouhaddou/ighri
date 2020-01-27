<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Updatepass;
use App\Form\CouvertureType;
use App\Form\EditProfileType;
use App\Form\RegisterType;
use App\Form\UpdatepassType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormError;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountController extends AbstractController
{

    private $encoder;
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }


    /**
     * @Route("/login", name="account_login")
     */
    public function login(AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();

      
        return $this->render('account/login.html.twig', [
            'hasError' => $error !== null,
            'username' => $username
        ]);
    }

    /**
     * @Route("/logout", name="account_logout")
     */
    public function logout(){}

    /**
     * @Route("/register", name="account_register")
     */
    public function register(Request $request)
    {
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
             return $this->redirectToRoute('account_login');
         }
        return $this->render('account/register.html.twig',[
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route("/register/{id}", name="account_registerEdit")
     */
    public function registerEdit(Request $request,User $user)
    {
        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $pass = $this->encoder->encodePassword($user, $user->getPasswordUser());
            $user->setPasswordUser($pass);
            $em->persist($user);
            $em->flush();
            $this->addFlash(
                'success',
                "  l'inscription a été  modifiée avec succès.  "
            );
            return $this->redirectToRoute('account_login');
        }
        return $this->render('account/registerEdit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/profil/{id}", name="showprofileuser")
     * @IsGranted("ROLE_USER")
     */
    public function showprofile($id,Request $request)
    {
        $em = $this->getDoctrine()->getRepository(User::class);
        $user = $em->findOneBy(['id' => $id]);

        
        return $this->render('account/profile.html.twig', [
            'user' => $user
        ]);
    }
    /**
     * @Route("/profil/{id}/couvert", name="edit_couvert")
     * @IsGranted("ROLE_USER")
     */
    public function editcouvert($id, Request $request)
    {
        $em = $this->getDoctrine()->getRepository(User::class);
        $user = $em->findOneBy(['id' => $id]);

        $form = $this->createForm(CouvertureType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $file = $form['couverture']->getData();
            $filename = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move($this->getParameter('upload_directory'), $filename);
            $user->setCouverture($filename);
            $em->persist($user);
            $em->flush();
            $this->addFlash(
                'success',
                ' votre  couverture à été modifiée avec succès  '
            );
        
            return $this->redirectToRoute('showprofileuser', array('id' => $id));
        }
        return $this->render('account/couvert.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/account/editpass", name="edit_password")
     * @IsGranted("ROLE_USER")
     */
    public function editpasse( Request $request)
    {
        $updatepassword = new Updatepass();
        $user= $this->getUser();
        $form = $this->createForm(UpdatepassType::class,$updatepassword);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // 1 verification que oldpassword est correcte
            if(!password_verify($updatepassword->getOldpassword(),$user->getPasswordUser() ))
            {
                //error
                $form->get("oldpassword")->addError(new FormError("Le mot de passe que vous avez tapé  n'est pas votre mot de passe actuel !"));
            }else{
            $em = $this->getDoctrine()->getManager();
               $newPasswod = $updatepassword->getNewpassword();
               $pass = $this->encoder->encodePassword($user, $newPasswod);
               $user->setPasswordUser($pass);
               $em->persist($user);
               $em->flush();
               $this->addFlash(
                    'success',
                    'votre mot de passe à  été modifiée avec succès'
            );
            return $this->redirectToRoute('homepage');
            }
        }
        return $this->render('account/update_password.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }

     /**
     * @Route("/register/Edit/{id}/profil", name="account_ProfileEdit")
     */
    public function registerEditProfil(Request $request,User $user)
    {

        $form = $this->createForm(EditProfileType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) { 
            $em = $this->getDoctrine()->getManager();
            $file = $form['avatar']->getData();
            $filename = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move($this->getParameter('upload_directory'), $filename);

             $user->setAvatar($filename)
                    ->setFirstname($form['firstname']->getData())
                    ->setLastname($form['lastname']->getData())
                    ->setSlug($form['slug']->getData())
                    ->setContent($form['content']->getData());
            $em->persist($user);
            $em->flush();
            $this->addFlash(
                'success',
                ' Votre  profile a été  modifiée avec succès  '
            );
            return $this->redirectToRoute('showprofileuser',['id' => (string) $user->getId()]);
        }
        return $this->render('account/EditRegistration.html.twig', [
            'form' => $form->createView()
        ]);
    }

}
