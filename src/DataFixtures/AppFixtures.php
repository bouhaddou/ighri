<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Image;
use App\Entity\Posts;
use App\Entity\Produits;
use App\Entity\Categorie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;
    public function __construct(UserPasswordEncoderInterface $encoder)
     {
       $this->encoder=$encoder;
     }

    public function load(ObjectManager $manager)
    {
       $faker =Factory::create('FR-fr');
       for( $i=1; $i<=1; $i++)
        { 
            $categorie =new Categorie();
            $designation= $faker->sentence();
            $avatar= "http://lorempixel.com/640/480/";
            $content ='<p>' . join('</p><p>',$faker->paragraphs(3)) .'</p>';
            $titre= $faker->sentence();
            $categorie->setDesignation($titre)
                        ->setImage($avatar)
                 ->setContent($content);
            $manager->persist($categorie);

            for( $k=1; $k<=1; $k++)
            {
                $content ='<p>' . join('</p><p>',$faker->paragraphs(2)) .'</p>';
                $avatar= "http://lorempixel.com/640/480/";
                $produit = new Produits();
                $produit->setReference("prodtuit")
                        ->setSlug($faker->slug)
                        ->setContent($content)
                        ->setPoids(mt_rand(20,200))
                        ->setPrix(mt_rand(5,20))
                        ->setImage($avatar)
                        ->setCategories($categorie);
                        $manager->persist($produit);
            } 
           
        }
        $manager->flush();

    }
}