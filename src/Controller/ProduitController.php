<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\CategorieRepository;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/produit")
 */
class ProduitController extends AbstractController
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
     * @Route("/", name="produit.index")
     * @param ProduitRepository $repository
     * @return Response
     */
    public function index(ProduitRepository $produitRepository, CategorieRepository $categorieRepository): Response
    {
//        $produit = New Produit();
//        $produit->setTitle('PS4')
//            ->setDescription('Voici le premier produit de mon shop')
//            ->setReference('AHUV64ZQF')
//            ->setPrice('249.99')
//            ->setImage('https://www.cdiscount.com/pdt2/2/1/6/1/700x700/711719753216/rw/console-ps4-pro-1to-noire-jet-black-playstation.jpg');
//
//        $em = $this->getDoctrine()->getManager();
//        $em->persist($produit);
//        $em->flush();


        return $this->render('produit/index.html.twig', [
            'categories' => $categorieRepository->findAll(),
            'produit' => $produitRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="produit.new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($produit);
            $entityManager->flush();

            return $this->redirectToRoute('produit.index');
        }

        return $this->render('produit/new.html.twig', [
            'produit' => $produit,

            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{slug}-{id}", name="produit.show", requirements={"slug": "[a-z0-9\-]*" })
     * @return Response
     */
    public function show(Produit $produit, string $slug, CategorieRepository $categorieRepository, ProduitRepository $produitRepository): Response
    {
        //grâce au paramètre "Produit $produit" de notre function cette ligne n'est plus obligatoire
        //$produit = $this->repository->find($id);

        // grâce à cette partie de code -> toujours redirigé vers le lien canonique
        if ($produit->getSlug() !== $slug) {
            return $this->redirectToRoute('produit.show', [
                'id' => $produit->getId(),
                'slug' => $produit->getSlug(),


            ], 301);
        }
        return $this->render('produit/show.html.twig', [
            'produit' => $produit,

            'categories' => $categorieRepository->findAll()
        ]);
    }

    /**
     * @Route("/{id}/edit", name="produit.edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Produit $produit, CategorieRepository $categorieRepository): Response
    {
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('produit.index');
        }

        return $this->render('produit/edit.html.twig', [
            'produit' => $produit,
            'form' => $form->createView(),
            'categories' => $categorieRepository->findAll()
        ]);
    }

    /**
     * @Route("/{id}", name="produit.delete", methods={"DELETE"})
     */
    public function delete(Request $request, Produit $produit): Response
    {
        if ($this->isCsrfTokenValid('delete' . $produit->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($produit);
            $entityManager->flush();
        }

        return $this->redirectToRoute('produit.index');
    }



}
