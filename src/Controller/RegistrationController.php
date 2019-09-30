<?php


namespace App\Controller;


use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{

    public function Reg_form()
    {
        $user = $this->getUser();
        return $this->render('Registration\reg_home.html.twig',['user'=>$user]);
    }

    public function reg_submit(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {

        $user = new User;

        $email = $request->get("email");
        $username = $request->get("username");
        $password = $request->get("password");
        $confirmed_pass = $request->get("confirm");

        $pass = $passwordEncoder->encodePassword($user, "$password");

        $user_repo = $this->getDoctrine()->getRepository(User::class);

        $user_name = $user_repo->findOneBy(['username' => $username]);
        $user_email = $user_repo->findOneBy(['email' => $email]);

        if ($user_name) {
            return new Response(
                '<html><body>Sorry' . $username . 'is already taken</body></html>'
            );
        }
        if ($user_email) {
            return new Response(
                '<html><body>Sorry' . $email . 'is already taken</body></html>'
            );
        }
        if ($password != $confirmed_pass) {
            return new Response(
                '<html><body>Sorry password does not match</body></html>'
            );
        } else {
            $user->setEmail($email);
            $user->setUsername($username);
            $user->setPassword($pass);
            $user->setRoles(array('ROLE_Author'));

            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($user);

            $entityManager->flush();

            return new Response(
                '<html><body>Registration Completed</body></html>'
            );
        }
    }
}