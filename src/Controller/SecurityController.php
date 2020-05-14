<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\InscriptionType;

use App\Repository\CategorieRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/inscription", name="security_inscription")
     */
    public function inscription(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder, CategorieRepository $categorieRepository)
    {
        $user = new Utilisateur();
        $form = $this->createForm(InscriptionType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $hash = $encoder->encodePassword($user, $user->getPassword());

            $user->setPassword($hash);

            $manager->persist($user);
            $manager->flush();
           // $result["success"] = "1";
           // $result["message"] = "success";
           // echo json_encode($result);

      //  } else {
          //  $result["success"] = "0";
         //   $result["message"] = "error";
          //  echo json_encode($result);

        }



        return $this->render('security/inscription.html.twig', [
            'form' => $form->createView(),
            'categories' => $categorieRepository->findAll()
        ]);
    }

    /**
     * @Route("/connexion", name="security_login")
     */
    public function login(CategorieRepository $categorieRepository)
    {
        return $this->render('security/login.html.twig', [
            'categories' => $categorieRepository->findAll(),

        ]);
    }

    /**
     * @Route("/deconnexion", name="security_logout")
     */
    public function logout()
    {}
}
