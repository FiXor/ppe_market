<?php
namespace App\Controller;

use App\Entity\LigneReservation;
use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Produit;
use App\Form\LigneReservationType;
use App\Repository\CategorieRepository;
use App\Repository\LigneReservationRepository;
use App\Repository\ProduitRepository;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;


/**
 * @Route("/panier")
 */
class LigneReservationController extends AbstractController
{
    /**
     * @var ProduitRepository
     */
    private $repository;
    /**
     * @var ObjectManager
     */
    private $em;

    public function __construct(ProduitRepository $repository, EntityManagerInterface $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * @Route("/a", name="ligne_reservation_index", methods={"GET"})
     */
    public function index(LigneReservationRepository $ligneReservationRepository, CategorieRepository $categorieRepository): Response
    {
        return $this->render('ligne_reservation/index.html.twig', [
            'ligne_reservations' => $ligneReservationRepository->findAll(),
            'categories' => $categorieRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="ligne_reservation_new", methods={"GET","POST"})
     */
    public function new(Request $request, CategorieRepository $categorieRepository): Response
    {
        $ligneReservation = new LigneReservation();
        $form = $this->createForm(LigneReservationType::class, $ligneReservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($ligneReservation);
            $entityManager->flush();

            return $this->redirectToRoute('ligne_reservation_index');
        }

        return $this->render('ligne_reservation/new.html.twig', [
            'ligne_reservation' => $ligneReservation,
            'form' => $form->createView(),
             'categories' => $categorieRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}", name="ligne_reservation_show", methods={"GET"})
     */
    public function show(LigneReservation $ligneReservation): Response
    {
        return $this->render('ligne_reservation/show.html.twig', [
            'ligne_reservation' => $ligneReservation,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="ligne_reservation_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, LigneReservation $ligneReservation): Response
    {
        $form = $this->createForm(LigneReservationType::class, $ligneReservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('ligne_reservation_index');
        }

        return $this->render('ligne_reservation/edit.html.twig', [
            'ligne_reservation' => $ligneReservation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="ligne_reservation_delete", methods={"DELETE"})
     */
    public function delete(Request $request, LigneReservation $ligneReservation): Response
    {
        if ($this->isCsrfTokenValid('delete' . $ligneReservation->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($ligneReservation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('ligne_reservation_index');
    }


// code rajouté

    /**
     * @Route("/add/{id}", name="panier_add")
     */
    public function add($id, SessionInterface $session, ProduitRepository $produitRepository, Produit $idProduit): Response
    {

        $panier = $session->get('panier', []);

        if (!empty($panier[$id])) {
            $panier[$id]++;
        } else {
            $panier[$id] = 1;
        }

        $session->set('panier', $panier);


        $panierWithData = [];
        foreach ($panier as $id => $quantite) {
            $panierWithData[] = [
                'produit' => $produitRepository->find($id),
                'quantite' => $quantite
            ];


        }
        $lr = New LigneReservation();
        $lr->setIdProduit($idProduit)
            ->setQuantite($quantite);


        $em = $this->getDoctrine()->getManager();
        $em->persist($lr);
        $em->flush();

        //dd($session->get('panier'));
        return $this->redirectToRoute("panier_index");

    }

    /**
     * @Route("/", name="panier_index")
     */
    public function panier(SessionInterface $session, ProduitRepository $produitRepository, CategorieRepository $categorieRepository): Response
    {
        $panier = $session->get('panier', []);

        $panierWithData = [];
        foreach ($panier as $id => $quantite) {
            $panierWithData[] = [
                'produit' => $produitRepository->find($id),
                'quantite' => $quantite
            ];

        }
        //dd($panierWithData);

        $total = 0;
        foreach ($panierWithData as $item) {
            $totalItem = $item['produit']->getPrixht() * $item['quantite'];
            $total += $totalItem;
        }


        return $this->render('ligne_reservation/panier.html.twig', [
            'categories' => $categorieRepository->findAll(),
            'items' => $panierWithData,
            'total' => $total,
        ]);

    }


    /**
     * @Route("/remove/{id}", name="panier_remove", methods={"DELETE", "GET"})
     */
    public function remove($id, SessionInterface $session)
    {

        $panier = $session->get('panier', []);

        if (!empty($panier[$id])){
            unset($panier[$id]);
        }
        $session->set('panier', $panier);

        return $this->redirectToRoute("panier_index");

    }
}
