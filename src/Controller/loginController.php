<?php


namespace App\Controller;




use App\Entity\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Response;


class loginController extends AbstractController
{

    public function log_in_form()
    {
        return $this->render('LogIn\log_in_form.html.twig');
    }

    public function log_submit(){
        return new Response("okay");
    }

    /*

    public function log_submit(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {


        $email = $request->get("email");
        $password = $request->get("password");

        $user_repo = $this->getDoctrine()->getRepository(User::class);

        $user_email = $user_repo->findOneBy(['email' => $email]);

        if ($user_email) {

            $encoder = $this->container->get('security.password_encoder');
            $match = $encoder->isPasswordValid($user_email, $password);

            if ($match==true) {

                return $this->render('LogIn\log_submit.html.twig');

            }

        }

        return $this->render('Error\NotFound.html.twig');
    }
    */
}

