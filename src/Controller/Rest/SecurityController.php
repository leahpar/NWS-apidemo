<?php

namespace App\Controller\Rest;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\View\View;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use FOS\RestBundle\Controller\Annotations as REST;

class SecurityController extends AbstractController
{
    /**
     * @REST\Post("/login", name="login")
     */
    public function login()
    {
        $user = $this->getUser();
        return View::create($user, Response::HTTP_OK);
    }

    /**
     * @REST\Post("/logout", name="logout")
     */
    public function logout(TokenStorageInterface $tokenStorage)
    {
        $tokenStorage->setToken(null);
        return View::create(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @REST\Post("/register", name="register")
     */
    public function register(Request $request,
                             UserPasswordEncoderInterface $passwordEncoder,
                             EntityManagerInterface $em)
    {

        $user = new User();

        // TODO: vérifier que les champs username & password sont renseignée
        //return View::create($user, Response::HTTP_BAD_REQUEST);

        // TODO: vérifier que l'utilisateur n'existe pas déjà
        //return View::create($user, Response::HTTP_BAD_REQUEST);

        $user->setUsername($request->request->get("username"));
        $password = $passwordEncoder->encodePassword($user, $request->request->get("password"));
        $user->setPassword($password);

        $em->persist($user);
        $em->flush();

        return View::create($user, Response::HTTP_OK);

    }

}
