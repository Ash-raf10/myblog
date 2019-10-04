<?php


namespace App\Controller;




use App\Entity\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Response;


class loginController extends AbstractController
{

    public function log_in_form()
    {
        $user = $this->getUser();
        return $this->render('LogIn\log_in_form.html.twig', ['user' => $user]);
    }

    public function log_submit()
    {
        return new Response("okay");
    }

}