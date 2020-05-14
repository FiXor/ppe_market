<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Categorie
 *
 * @ORM\Table(name="categorie")
 * @Orm\Entity(repositoryClass="App\Repository\CategorieRepository")
 */
class Categorie
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="libelle", type="string", length=50, nullable=true)
     */
    private $libelle;

    /**
     *
     * @ORM\Column(name="chemin_i", type="string", length=255)
     */
    private $cheminI;




    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(?string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getCheminI(): ?string
    {
        return $this->cheminI;
    }

    public function setCheminI(string $cheminI): self
    {
        $this->cheminI = $cheminI;

        return $this;
    }




}
