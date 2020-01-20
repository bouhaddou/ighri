<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Image;
use App\Entity\Posts;
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
       $users= [];
       $genres=['male','female'];

       for( $k=0;$k<=10;$k++)
       {
           $user = new User();

           $genre=$faker->randomElement($genres);
           $picture = 'http://randomuser.me/api/portraits/';
           $picturesID =$faker->numberBetween(1,99). '.jpg';
           $picture = $picture.($genre =='male' ? 'men/' : 'women/').$picturesID;
           $content ='<p>' . join('</p> <p>',$faker->paragraphs(3)) .'</p>';
           $pass= $this->encoder->encodePassword($user,'password');
           $user->setFirstname($faker->username)
                ->setLastname($faker->lastname)
                ->setEmail($faker->email)
                ->setSlug($faker->sentence())
                ->setContent($content)
                ->setAvatar($picture)
                ->setPasswordUser($pass);
            $manager->persist($user);
           $users[]=$user;
       }
        for( $i=1; $i<=30; $i++)
        { 
        $post =new Posts();

        $titre= $faker->sentence();
        $avatar= $faker->imageUrl(1000,300);
        $content ='<p>' . join('</p><p>',$faker->paragraphs(3)) .'</p>';
        $date= $faker->dateTime($max = 'now', $timezone = null);
        $user=$users[mt_rand(0,count($users) - 1 )];

        $post->setTitre($titre)
              ->setAvatar($avatar)
              ->setDatepub($date)
              ->setAuthor($user)
              ->setContent($content);
        for($j=1; $j<=mt_rand(2,5); $j++)
        {
            $image =new Image();

            $image->setUrl($faker->imageUrl())
                    ->setCaption($faker->sentence())
                    ->setPosts($post);
            $manager->persist($image);

        }

        $manager->persist($post);
        }
        $manager->flush();

    }
}
