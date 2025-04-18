<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class User
 *
 * Représente un utilisateur dans l'application, avec un nom d'utilisateur, un mot de passe haché et des rôles.
 * Cette entité implémente UserInterface et PasswordAuthenticatedUserInterface, ce qui la rend compatible avec le système de sécurité de Symfony.
 *
 * @package App\Entity
 */
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_USERNAME', fields: ['username'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * L'API token de l'utilisateur.
     *
     * @var string|null
     */
    #[ORM\Column(type: "string", unique: true, nullable: true)]
    private $apiToken;

    /**
     * L'identifiant unique de l'utilisateur.
     *
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Le nom d'utilisateur de l'utilisateur.
     *
     * @var string|null
     */
    #[ORM\Column(length: 180)]
    private ?string $username = null;

    /**
     * Les rôles de l'utilisateur.
     *
     * @var list<string> Une liste des rôles attribués à l'utilisateur.
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * Le mot de passe haché de l'utilisateur.
     *
     * @var string|null
     */
    #[ORM\Column]
    private ?string $password = null;

    /**
     * Retourne l'identifiant unique de l'utilisateur.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Retourne le nom d'utilisateur.
     *
     * @return string|null
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * Définit le nom d'utilisateur.
     *
     * @param string $username Le nom d'utilisateur.
     *
     * @return static
     */
    public function setUsername(string $username): static
    {
        $this->username = $username;
        return $this;
    }

    /**
     * Retourne l'identifiant visuel de l'utilisateur.
     *
     * @see UserInterface
     *
     * @return string Le nom d'utilisateur en tant qu'identifiant.
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * Retourne les rôles de l'utilisateur.
     *
     * @see UserInterface
     *
     * @return list<string> Une liste unique de rôles (l'utilisateur possède au minimum ROLE_USER).
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // Garantit que l'utilisateur a au moins le rôle ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * Définit les rôles de l'utilisateur.
     *
     * @param list<string> $roles Les rôles à attribuer à l'utilisateur.
     *
     * @return static
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;
        return $this;
    }

    /**
     * Retourne le mot de passe haché de l'utilisateur.
     *
     * @see PasswordAuthenticatedUserInterface
     *
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * Définit le mot de passe haché de l'utilisateur.
     *
     * @param string $password Le mot de passe haché.
     *
     * @return static
     */
    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    /**
     * Supprime les informations sensibles de l'utilisateur.
     *
     * Cette méthode est appelée pour effacer les données temporaires,
     * comme le mot de passe en clair, si elles étaient stockées temporairement.
     *
     * @see UserInterface
     *
     * @return void
     */
    public function eraseCredentials(): void
    {
        // Si vous stockez des données sensibles temporaires sur l'utilisateur, les effacer ici.
        // $this->plainPassword = null;
    }

    /**
     * Retourne l'API token de l'utilisateur.
     *
     * @return string|null
     */
    public function getApiToken(): ?string
    {
        return $this->apiToken;
    }

    /**
     * Définit l'API token de l'utilisateur.
     *
     * @param string|null $apiToken L'API token.
     *
     * @return static
     */
    public function setApiToken(?string $apiToken): static
    {
        $this->apiToken = $apiToken;
        return $this;
    }
}