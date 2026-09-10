<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 30)]
    #[Assert\NotBlank(message: "Le nom est obligatoire")]
     #[Assert\Regex(
        pattern: "/^[\p{L}\s'-]+$/u",
        message: "Le nom ne peut contenir que des lettres, espaces, apostrophes et tirets"
    )]
    private ?string $nom = null;

    #[ORM\Column(length: 30)]
    #[Assert\NotBlank(message: "Le prénom est obligatoire")]
    #[Assert\Regex(
        pattern: "/^[\p{L}\s'-]+$/u",
        message: "Le prénom ne peut contenir que des lettres, espaces, apostrophes et tirets"
    )]
    private ?string $prenom = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank(message: "L'email est obligatoire")]
    #[Assert\Email(message: "L'email '{{ value }}' n'est pas valide")]
    private ?string $email = null;

    #[ORM\Column(length: 20)]
    #[Assert\NotBlank(message: "Le téléphone est obligatoire")]
    #[Assert\Regex(
        pattern: "/^[0-9+\s()\/-]+$/",
        message: "Le téléphone ne peut contenir que des chiffres, +, -, /, () et des espaces"
    )]
    private ?string $telephone = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
     #[Assert\NotNull(message: "La date de naissance est obligatoire")]
    #[Assert\LessThanOrEqual(
        value: "-18 years",
        message: "Vous devez avoir au moins 18 ans"
    )]
    private ?\DateTimeInterface $dateNaissance = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: "Le rôle est obligatoire")]
    private ?string $role = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Le mot de passe est obligatoire")]
    private ?string $password = null;

    public const ROLE_ADMIN = 'ROLE_ADMIN';
    public const ROLE_RESPONSABLE = 'ROLE_RESPONSABLE';
    public const ROLE_AGENT = 'ROLE_AGENT';



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;
        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): static
    {
        $this->telephone = $telephone;
        return $this;
    }

    public function getDateNaissance(): ?\DateTimeInterface
    {
        return $this->dateNaissance;
    }

    public function setDateNaissance(\DateTimeInterface $dateNaissance): static
    {
        $this->dateNaissance = $dateNaissance;
        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): static
    {
        if (!in_array($role, [self::ROLE_ADMIN, 
        self::ROLE_AGENT, 
        self::ROLE_RESPONSABLE,
        // Gardez l'ancienne valeur temporairement pour la migration
        'Administrateur',
        'Agent de saisie',
        'Responsable'
    ])) {
            throw new \InvalidArgumentException("Rôle invalide");
        }
        
        $this->role = $role;
        return $this;
    }

    // Méthodes requises par UserInterface
    public function getRoles(): array
    {
         return [match($this->role) {
        'Administrateur' => self::ROLE_ADMIN,
        'Agent de saisie' => self::ROLE_AGENT,
        'Responsable' => self::ROLE_RESPONSABLE,
        default => $this->role
    }];
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

   

    public function eraseCredentials(): void
    {
        // Nettoyage des données sensibles temporaires
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    // Méthode facultative pour Symfony 6.1+
    public function getSalt(): ?string
    {
        return null; // Non nécessaire avec les algorithmes modernes
    }

    // App\Entity\User
private ?string $plainPassword = null;

public function getPlainPassword(): ?string
{
    return $this->plainPassword;
}

public function setPlainPassword(string $plainPassword): static
{
    $this->plainPassword = $plainPassword;
    return $this;
}
}