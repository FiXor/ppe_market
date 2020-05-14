<?php


namespace App\Controller;


use App\Entity\Utilisateur;
use App\Form\InscriptionType;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;


class WebserviceController extends AbstractController
{

    /**
     * @Route("/ws_inscription", name="ws_inscription")
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @return Response
     */
    public function inscription(Request $request,  UserPasswordEncoderInterface $encoder)
    {

        $data = $request->request;

        $utilisateur = new Utilisateur();

        $utilisateur->setNom($data->get("nom"));
        $utilisateur->setPrenom($data->get("prenom"));
        $utilisateur->setCourriel($data->get("courriel"));
        $utilisateur->setUsername($data->get("username"));
        $hash = $encoder->encodePassword($utilisateur, $data->get("password"));

        $utilisateur->setPassword($hash);

        $utilisateur->setTelephone($data->get("telephone"));
        $utilisateur->setDateNaissance($data->get("dateNaissance"));



        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($utilisateur);
        $entityManager->flush();

        $array['success'] = true;
         return new Response(json_encode($array));
         //die();


    }

    /**
     * @Route("/ws_connexion", name="ws_connexion")
     * @param Request $request
     * @return Response
     */
    public function connexion(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $data = $request->request;

        $utilisateur = new Utilisateur();

        $utilisateur->setCourriel($data->get("courriel"));
        $hash = $encoder->encodePassword($utilisateur, $data->get("password"));
        $utilisateur->setPassword($hash);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($utilisateur);
        $entityManager->flush();

        $array['success'] = true;
        return new Response(json_encode($array));

        die();
    }
}