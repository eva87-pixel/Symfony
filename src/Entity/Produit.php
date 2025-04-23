<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use App\Validator\Antispam;
use App\Entity\Category;

/**
 * Class Produit
 *
 * Représente un produit avec ses caractéristiques et ses relations.
 *
 * @package App\Entity
 */
#[ORM\Entity(repositoryClass: ProduitRepository::class)]
#[UniqueEntity(fields: "nom", message: "erreur produit déjà existant dans la base", groups: ["registration"])]
class Produit
{
    /**
     * Méthode de callback pour vérifier si le contenu du nom est valide.
     *
     * Vérifie que le nom ne contient pas de mots interdits (arme, médicament, drogue).
     * Si c'est le cas, une violation est ajoutée.
     *
     * @param ExecutionContextInterface $context Le contexte d'exécution de la validation
     *
     * @return void
     */
    #[Assert\Callback()]
    public function isContentValid(ExecutionContextInterface $context): void
    {
        $forbiddenWords = ['arme', 'médicament', 'drogue'];

        if (preg_match('#' . implode('|', $forbiddenWords) . '#i', $this->getNom())) {
            // Ajoute une violation si un mot interdit est trouvé
            $context->buildViolation('Le produit est interdit à la vente')
                ->atPath('produit')
                ->addViolation();
        }
    }

    /**
     * Vérifie que le prix et la quantité sont strictement positifs.
     *
     * @return bool Retourne true si les deux valeurs sont positifs, sinon false.
     */
    #[Assert\IsTrue(message: "Erreur valeurs négatives sur le prix ou la quantité")]
    public function isPrixQuantiteValid(): bool
    {
        if (
            is_float($this->getPrix()) &&
            is_int($this->getQuantite()) &&
            ($this->getPrix() > 0) &&
            ($this->getQuantite() > 0)
        ) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * L'identifiant unique du produit.
     *
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Le nom du produit.
     *
     * @var string|null
     */
    #[ORM\Column(length: 200)]
    #[Assert\Length(
        min: 2,
        max: 50,
        minMessage: "Votre nom doit faire au moins {{ limit }} caractères",
        maxMessage: "Votre nom ne doit pas dépasser {{ limit }} caractères",
        groups: ["all"]
    )]
    #[Antispam(groups: ["all"])]
    private ?string $nom = null;

    /**
     * Le prix du produit.
     *
     * @var float|null
     */
    #[ORM\Column(type: "decimal", precision: 10, scale: 2)]
    private ?float $prix = null;

    /**
     * La quantité disponible du produit.
     *
     * @var int|null
     */
    #[ORM\Column]
    private ?int $quantite = null;

    /**
     * Indique si le produit est en rupture de stock.
     *
     * @var bool|null
     */
    #[ORM\Column]
    private ?bool $rupture = null;

    /**
     * Lien vers l'image du produit.
     *
     * @var string|null
     */
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $lienImage = null;

    /**
     * Référence associée au produit (relation one-to-one).
     *
     * @var Reference|null
     */
    #[ORM\OneToOne(targetEntity: Reference::class, cascade: ["persist"])]
    private ?Reference $reference = null;
    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'produits', cascade: ['persist'])]
    #[ORM\JoinTable(name: 'produit_category')]
    private Collection $categories;

    /**
     * Collection des distributeurs associés au produit (relation many-to-many).
     *
     * @var Collection<int, Distributeur>
     */
    #[ORM\ManyToMany(targetEntity: Distributeur::class, inversedBy: 'produits', cascade: ["persist", "remove"])]
    #[ORM\JoinTable(name: "produit_distributeur")]
    private Collection $distributeurs;

    /**
     * Constructeur.
     *
     * Initialise la collection de distributeurs.
     */
    public function __construct()
    {
        $this->distributeurs = new ArrayCollection();
        $this->categories = new ArrayCollection();
    }

    /**
     * Retourne la collection des catégories associées au produit.
     *
     * @return Collection<int, Category>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }
    /**
     * Ajoute une catégorie à la collection du produit.
     *
     * @param Category $category La catégorie à ajouter.
     *
     * @return static
     */
    public function addCategory(Category $category): static
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
            $category->addProduit($this); // Associer ce produit à la catégorie
        }
        return $this;
    }
    /**
     * Supprime une catégorie de la collection du produit.
     *
     * @param Category $category La catégorie à retirer.
     *
     * @return static
     */
    public function removeCategory(Category $category): static
    {
        if ($this->categories->removeElement($category)) {
            $category->removeProduit($this); // Dissocier le produit de la catégorie
        }
        return $this;
    }

    /**
     * Retourne l'identifiant du produit.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Retourne le nom du produit.
     *
     * @return string|null
     */
    public function getNom(): ?string
    {
        return $this->nom;
    }

    /**
     * Définit le nom du produit.
     *
     * @param string $nom Le nom du produit.
     *
     * @return static
     */
    public function setNom(string $nom): static
    {
        $this->nom = $nom;
        return $this;
    }

    /**
     * Retourne le prix du produit.
     *
     * @return float|null
     */
    public function getPrix(): ?float
    {
        return $this->prix;
    }

    /**
     * Définit le prix du produit.
     *
     * @param float $prix Le prix du produit.
     *
     * @return static
     */
    public function setPrix(float $prix): static
    {
        $this->prix = $prix;
        return $this;
    }

    /**
     * Retourne la quantité disponible du produit.
     *
     * @return int|null
     */
    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    /**
     * Définit la quantité disponible du produit.
     *
     * @param int $quantite La quantité disponible.
     *
     * @return static
     */
    public function setQuantite(int $quantite): static
    {
        $this->quantite = $quantite;
        return $this;
    }

    /**
     * Indique si le produit est en rupture de stock.
     *
     * @return bool|null
     */
    public function isRupture(): ?bool
    {
        return $this->rupture;
    }

    /**
     * Définit si le produit est en rupture de stock.
     *
     * @param bool $rupture L'état de rupture.
     *
     * @return static
     */
    public function setRupture(bool $rupture): static
    {
        $this->rupture = $rupture;
        return $this;
    }

    /**
     * Retourne le lien vers l'image du produit.
     *
     * @return string|null
     */
    public function getLienImage(): ?string
    {
        return $this->lienImage;
    }

    /**
     * Définit le lien vers l'image du produit.
     *
     * @param string|null $lienImage Le lien ou le nom du fichier image.
     *
     * @return static
     */
    public function setLienImage(?string $lienImage): static
    {
        $this->lienImage = $lienImage;
        return $this;
    }

    /**
     * Retourne la référence associée au produit.
     *
     * @return Reference|null
     */
    public function getReference(): ?Reference
    {
        return $this->reference;
    }

    /**
     * Définit la référence du produit.
     *
     * @param Reference|null $reference La référence à associer.
     *
     * @return static
     */
    public function setReference(?Reference $reference): static
    {
        $this->reference = $reference;
        return $this;
    }

    /**
     * Retourne la collection des distributeurs associés.
     *
     * @return Collection<int, Distributeur>
     */
    public function getDistributeurs(): Collection
    {
        return $this->distributeurs;
    }

    /**
     * Ajoute un distributeur à la collection du produit.
     *
     * @param Distributeur $distributeur Le distributeur à ajouter.
     *
     * @return static
     */
    public function addDistributeur(Distributeur $distributeur): static
    {
        if (!$this->distributeurs->contains($distributeur)) {
            $this->distributeurs->add($distributeur);
            $distributeur->addProduit($this); // Associer ce produit au distributeur
        }
        return $this;
    }

    /**
     * Supprime un distributeur de la collection du produit.
     *
     * @param Distributeur $distributeur Le distributeur à retirer.
     *
     * @return static
     */
    public function removeDistributeur(Distributeur $distributeur): static
    {
        if ($this->distributeurs->removeElement($distributeur)) {
            $distributeur->removeProduit($this); // Dissocier le produit du distributeur
        }
        return $this;
    }
}