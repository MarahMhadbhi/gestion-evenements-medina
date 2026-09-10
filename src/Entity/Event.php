<?php
// src/Entity/Event.php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Entity\Inscription;


#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_evenement')]
    private ?int $id = null;

     // Nouveau champ titre en remplacement de timeEvent
    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(name: 'date_debut', type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateDebut = null;

    #[ORM\Column(name: 'date_fin', type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateFin = null;

    #[ORM\Column(length: 255)]
    private ?string $lieu = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $logo = null;

    #[ORM\Column( name: 'logoSponsor',type: Types::JSON, nullable: true)]
    private ?array $logoSponsor = null; // Stocke les chemins/noms des fichiers


    #[ORM\OneToMany(
        mappedBy: 'event', 
        targetEntity: Inscription::class,
        orphanRemoval: true 
    )]

    private Collection $inscriptions;

    public function __construct()
    {
        $this->inscriptions = new ArrayCollection();
        $this->inscriptions = new ArrayCollection();
        $this->logoSponsor = []; // Initialise comme un tableau vide
    }

     
   

    // Getters et Setters
    public function getId(): ?int
    {
        return $this->id;
    }

    // Nouveaux getter/setter pour le titre
    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): static
    {
        $this->dateDebut = $dateDebut;
        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(?\DateTimeInterface $dateFin): static
    {
        $this->dateFin = $dateFin;
        return $this;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(string $lieu): static
    {
        $this->lieu = $lieu;
        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): static
    {
        $this->logo = $logo;
        return $this;
    }

     public function getInscriptions(): Collection
    {
        return $this->inscriptions;
    }

    public function addInscription(Inscription $inscription): static
    {
        if (!$this->inscriptions->contains($inscription)) {
            $this->inscriptions->add($inscription);
            $inscription->setEvent($this);
        }
        return $this;
    }

     public function removeInscription(Inscription $inscription): static
    {
        if ($this->inscriptions->removeElement($inscription)) {
            // Définir le côté propriétaire à null (sauf si orphanRemoval est activé)
            if ($inscription->getEvent() === $this) {
                $inscription->setEvent(null);
            }
        }
        return $this;
    }

     public function getLogoSponsor(): ?array
    {
        return $this->logoSponsor ?? [];
    }

     public function setLogoSponsor(?array $logoSponsor): static
    {
        $this->logoSponsor = $logoSponsor;
        return $this;
    }

    public function addLogoSponsor(string $filename): void
    {
        $this->logoSponsor[] = $filename;
    }

     public function removeLogoSponsor(string $filename): void
    {
        $key = array_search($filename, $this->logoSponsor, true);
        if ($key !== false) {
            unset($this->logoSponsor[$key]);
            $this->logoSponsor = array_values($this->logoSponsor); // Réindexe le tableau
        }
    }
}