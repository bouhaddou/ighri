<?php

namespace App\Controller;

use App\Entity\Posts;
use App\Service\Pagination;
use App\Repository\PostsRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class AdminPostController extends AbstractController
{
    /**
     * @Route("/admin/post/{page<\d+>?1}", name="admin_post_index")
     */
    public function index($page,Pagination $pagination,ObjectManager $manger)
    {
        
        $pagination->setEntityClass(Posts::class)
            ->setPage($page)
            ->setRoute('admin_post_index')
            ->setLimit(6);
            $contact = $manger->createQuery(" SELECT count(c) FROM App\Entity\Contact c WHERE c.valide = false ")->getSingleScalarResult();
        return $this->render('admin/post/index.html.twig', [
            'stats'  => compact('contact'),
            'pagination' => $pagination
        ]);
    }

     /**
     * @Route("/admin/post/{id}/delete", name="deleteadminposte")
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
            ' le poste à été bien supprimée correctement '
        );

        return  $this->redirectToRoute("admin_post_index");
    }
}
