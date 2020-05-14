<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Cocur\Slugify\Slugify;
use phpDocumentor\Reflection\Types\Boolean;

/**
 * Produit
 *
 * @ORM\Table(name="produit")
 * @Orm\Entity(repositoryClass="App\Repository\ProduitRepository")
 */
class Produit
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
     * @ORM\Column(name="libelle", type="string", length=255, nullable=true)
     */
    private $libelle;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="string", length=1000, nullable=true)
     */
    private $description;

    /**
     * @var int|null
     *
     * @ORM\Column(name="prixht", type="integer", length=50, nullable=true)
     */
    private $prixht;

    /**
     * @var string|null
     *
     * @ORM\Column(name="stock", type="string", length=50, nullable=true)
     */
    private $stock;

    /**
     * @ORM\Column(type="boolean")
     */
    private $best;


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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrixht(): ?int
    {
        return $this->prixht;
    }

    public function setPrixht(?int $prixht): self
    {
        $this->prixht = $prixht;

        return $this;
    }

    public function getStock(): ?string
    {
        return $this->stock;
    }

    public function setStock(?string $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    public function getSlug(): string
    {
        return (new Slugify())->slugify($this->libelle);
    }

    public function getBest(): ?bool
    {
        return $this->best;
    }

    public function setBest(bool $best): self
    {
        $this->best = $best;

        return $this;
    }

}
