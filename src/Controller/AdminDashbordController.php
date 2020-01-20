<?php

namespace App\Controller;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

    /**
     * @Route("/admin")
     */
class AdminDashbordController extends AbstractController
{
    /**
     * @Route("/", name="admin_dashbord")
     */
    public function index(ObjectManager $manger)
    {

        $user= $manger->createQuery(" SELECT count(u) FROM App\Entity\User u ")->getSingleScalarResult();
        $post= $manger->createQuery(" SELECT count(p) FROM App\Entity\Posts p ")->getSingleScalarResult();
        $comment = $manger->createQuery(" SELECT count(c) FROM App\Entity\Comments c ")->getSingleScalarResult();
        $contact = $manger->createQuery(" SELECT count(c) FROM App\Entity\Contact c WHERE c.valide = false ")->getSingleScalarResult();

        $bestpost = $manger->createQuery(
            'SELECT AVG(c.rationg) as note, a.titre, u.firstname, u.lastname, u.avatar
            FROM App\Entity\Comments c 
            JOIN c.post a
            JOIN c.author u
            GROUP BY a
            ORDER BY note DESC '

        )->setMaxResults(5)->getResult();

        $badpost = $manger->createQuery(
            'SELECT AVG(c.rationg) as note, a.titre, u.firstname, u.lastname, u.avatar
            FROM App\Entity\Comments c 
            JOIN c.post a
            JOIN c.author u
            GROUP BY a
            ORDER BY note ASC '

        )->setMaxResults(5)->getResult();

        
        return $this->render('admin/dashbord/index.html.twig', [
            'stats'  => compact('user','post','comment','contact'),
            'bestpost' =>$bestpost,
            'badpost' => $badpost
        ]);
    }
}
