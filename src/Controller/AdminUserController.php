<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\Pagination;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminUserController extends AbstractController
{
    /**
     * @Route("/admin/user/{page<\d+>?1}", name="admin_user")
     */
    public function index($page, Pagination $pagination,ObjectManager $manger)
    {

        $pagination->setEntityClass(User::class)
            ->setPage($page)
            ->setRoute('admin_user')
            ->setLimit(6);
            $contact = $manger->createQuery(" SELECT count(c) FROM App\Entity\Contact c WHERE c.valide = false ")->getSingleScalarResult();

        return $this->render('admin/users/index.html.twig', [
            'stats'  => compact('contact'),
            'pagination' => $pagination
        ]);
    }
}
