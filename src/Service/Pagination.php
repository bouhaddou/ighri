<?php

namespace App\Service;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Environment;

class Pagination{


    private $entityClass;
    private $limit = 6;
    private $currentPage;
    private $manager;
    private $twig;
    private $route;

    public function __construct(ObjectManager $manager, Environment $twig, RequestStack $request)
    {
        $this->manager=$manager;
        $this->twig=$twig;
        $this->route=$request->getCurrentRequest()->attributes->get('_route');
    }

    public function display(){
        $this->twig->display('partials/pagination.html.twig',[
            'page' => $this->currentPage,
            'pages' => $this->getPages(),
            'route' => $this->route
        ]);
    }
    public function  getPages(){
        $repo=$this->manager->getRepository($this->entityClass);
        $total = count($repo->findAll());
        $pages = ceil($total / $this->limit);
        return $pages;
    }

    public function getData(){
        // calcule offset
        $start = $this->currentPage * $this->limit - $this->limit;
        $fin = $start + $this->limit;
        // demande au repository de trouver les élements
        $repo=$this->manager->getRepository($this->entityClass);
        $data=$repo->findBy([],['id' => 'DESC'],$fin,$start);
        // renvoyer les elements en question
        
        return $data;
    }
    public function setRoute($route)
    {
        $this->route = $route;
        return $this;
    }

    public function getRoute()
    {
        return $this->route;
    }

    public function setEntityClass($entityClass)
    {
        $this->entityClass= $entityClass;
        return $this;
    }

    public function getEntityClass()
    {
        return $this->entityClass;
    }
    public function setLimit($limit)
    {
        $this->limit=$limit;
        return $this;
    }

    public function getLimit()
    {
        return $this->limit;
    }
    public function setPage($currentPage)
    {
        $this->currentPage = $currentPage;
        return $this;
    }

    public function getPage()
    {
        return $this->currentPage;
    }

}









?>