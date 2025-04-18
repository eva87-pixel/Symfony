<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Distributeur
 *
 * Représente un distributeur pouvant être associé à plusieurs produits.
 *
 * @package App\Entity
 */
#[ORM\Entity]
class Distributeur
{
    /**
     * L'identifiant unique du distributeur.
     *
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Le nom du distributeur.
     *
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    /**
     * La collection de produits associés à ce distributeur.
     *
     * @var Collection<int, Produit>
     */
    #[ORM\ManyToMany(targetEntity: Produit::class, mappedBy: 'distributeurs')]
    private Collection $produits;

    /**
     * Constructeur.
     *
     * Initialise la collection des produits.
     */
    public function __construct()
    {
        $this->produits = new ArrayCollection();
    }

    /**
     * Retourne l'identifiant du distributeur.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Retourne le nom du distributeur.
     *
     * @return string|null
     */
    public function getNom(): ?string
    {
        return $this->nom;
    }

    /**
     * Définit le nom du distributeur.
     *
     * @param string $nom Le nom du distributeur.
     *
     * @return self
     */
    public function setNom(string $nom): static
    {
        $this->nom = $nom;
        return $this;
    }

    /**
     * Retourne la collection des produits associés au distributeur.
     *
     * @return Collection<int, Produit>
     */
    public function getProduits(): Collection
    {
        return $this->produits;
    }

    /**
     * Ajoute un produit à la collection du distributeur.
     *
     * @param Produit $produit Le produit à ajouter.
     *
     * @return self
     */
    public function addProduit(Produit $produit): static
    {
        if (!$this->produits->contains($produit)) {
            $this->produits->add($produit);
        }
        return $this;
    }

    /**
     * Supprime un produit de la collection du distributeur.
     *
     * @param Produit $produit Le produit à supprimer.
     *
     * @return self
     */
    public function removeProduit(Produit $produit): static
    {
        $this->produits->removeElement($produit);
        return $this;
    }
}