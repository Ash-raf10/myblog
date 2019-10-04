<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Generator\UrlGenerator;


class AboutController extends AbstractController
{


    public function about(){

        $user = $this->getUser();
        return $this->render("about/about.html.twig",['user'=>$user]);
    }


}