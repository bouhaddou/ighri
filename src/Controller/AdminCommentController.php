<?php

namespace App\Controller;

use App\Entity\Comments;
use App\Service\Pagination;
use App\Repository\CommentsRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class AdminCommentController extends AbstractController
{
    /**
     * @Route("/admin/comment/{page<\d+>?1}", name="admin_comment")
     */
    public function index($page, Pagination $pagination,ObjectManager $manger)
    {

        $pagination->setEntityClass(Comments::class)
            ->setPage($page)
            ->setRoute('admin_comment')
            ->setLimit(10);
            $contact = $manger->createQuery(" SELECT count(c) FROM App\Entity\Contact c WHERE c.valide = false ")->getSingleScalarResult();
            
        return $this->render('admin/comment/index.html.twig', [
            'stats'  => compact('contact'),
            'pagination' => $pagination
        ]);
    }

    /**
     * @Route("/admin/comment/{id}/delete", name="deletecomment")
     * @IsGranted("ROLE_USER")
     */
    public function deletecontact(Request $request, CommentsRepository $repo , $id)
    {
     
    $result = $repo->findOneById($id);
    $em= $this->getDoctrine()->getManager();

        $em->remove($result);
        $em->flush();

        return  $this->redirectToRoute("admin_comment");
    }
}
