<?php

namespace App\Controller;
use App\Entity\Categorie;
use App\Entity\Utilisateur;
use App\Form\CategorieType;
use App\Entity\Produit;
use App\Repository\CategorieRepository;
use App\Repository\ProduitRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;


class HomeController extends AbstractController
{
    /**
     * @var ProduitRepository
     */
    private $repository;
//    /**
//     * @var ObjectManager
//     */
//    private $em;


    public function __construct(ProduitRepository $repository)
//  public function __construct(ProduitRepository $repository, ObjectManager $em)
    {
        $this->repository = $repository;
//              $this->em = $em;
    }


    /**
     * @Route("/", name="home", methods={"GET"})
     * @param ProduitRepository $repository
     * @return Response
     */
    public function index(ProduitRepository $produitRepository, CategorieRepository $categorieRepository): Response
{
   // $produit = $repository->findBest();
    return $this->render('home/home.html.twig', [
        'produits' => $produitRepository->findBest(),
        'categories' => $categorieRepository->findAll(),
    ]);

   // var_dump($stmt);
   // die;

}

    /**
     * @Route("/categorie/{id}", name="categorie_show_produits", methods={"GET"})
     */
    public function show(Categorie $Categorie, ProduitRepository $produitRepository, CategorieRepository $categorieRepository): Response
    {
        return $this->render('categorie/showproduitcategorie.html.twig', [
            'produits' => $produitRepository->findCategorie1($Categorie),
            'categories' => $categorieRepository->findAll()
        ]);
    }
}
