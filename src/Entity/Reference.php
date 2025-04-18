<?php

namespace App\Entity;

use App\Repository\ReferenceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Reference
 *
 * Représente la référence associée à un produit.
 *
 * @package App\Entity
 */
#[ORM\Entity(repositoryClass: ReferenceRepository::class)]
class Reference
{
    /**
     * L'identifiant unique de la référence.
     *
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Le numéro de référence.
     *
     * @var int|null
     */
    #[ORM\Column]
    private ?int $numero = null;

    /**
     * Retourne l'identifiant de la référence.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Retourne le numéro de référence.
     *
     * @return int|null
     */
    public function getNumero(): ?int
    {
        return $this->numero;
    }

    /**
     * Définit le numéro de référence.
     *
     * @param int $numero Le numéro à attribuer à la référence.
     *
     * @return static
     */
    public function setNumero(int $numero): static
    {
        $this->numero = $numero;
        return $this;
    }
}