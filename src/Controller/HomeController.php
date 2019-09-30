<?php


namespace App\Controller;


use App\Entity\Post;
use App\Security\LoginAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{




    //home page or front page
    public function hello()
    {
        $repository = $this->getDoctrine()->getRepository(Post::class);

        //finding all post
        $all_post = $repository->findAll();

     //   $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        //
        $user= $this->getUser();


        return $this->render('Homepage/welcome.html.twig', ['all' => $all_post,'user'=>$user]);
    }


}
