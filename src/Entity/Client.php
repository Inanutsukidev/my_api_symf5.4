<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ClientRepository::class)
 */
class Client
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $raison_sociale;

    /**
     * @ORM\Column(type="string", length=5, nullable=true)
     */
    private $genre;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $adresse_complete;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $adresse_numero;

    /**
     * @ORM\Column(type="string", length=25, nullable=true)
     */
    private $adresse_prefix;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $adresse_nom;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $adresse_cp;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $adresse_ville;

    /**
     * @ORM\Column(type="date")
     */
    private $date_creation;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $tel_fix;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $tel_pt;

    /**
     * @ORM\OneToMany(targetEntity=Facture::class, mappedBy="client")
     */
    private $factures;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    public function __construct()
    {
        $this->factures = new ArrayCollection();
        $this->date_creation = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRaisonSociale(): ?string
    {
        return $this->raison_sociale;
    }

    public function setRaisonSociale(?string $raison_sociale): self
    {
        $this->raison_sociale = $raison_sociale;

        return $this;
    }

    public function getGenre(): ?string
    {
        return $this->genre;
    }

    public function setGenre(?string $genre): self
    {
        $this->genre = $genre;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getAdresseComplete(): ?string
    {
        return $this->adresse_complete = $this->adresse_numero . " " . $this->adresse_prefix . " " . $this->adresse_nom . " " . $this->adresse_cp . " " . $this->adresse_ville;
    }

    public function getAdresseNumero(): ?string
    {
        return $this->adresse_numero;
    }

    public function setAdresseNumero(?string $adresse_numero): self
    {
        $this->adresse_numero = $adresse_numero;

        return $this;
    }

    public function getAdressePrefix(): ?string
    {
        return $this->adresse_prefix;
    }

    public function setAdressePrefix(?string $adresse_prefix): self
    {
        $this->adresse_prefix = $adresse_prefix;

        return $this;
    }

    public function getAdresseNom(): ?string
    {
        return $this->adresse_nom;
    }

    public function setAdresseNom(?string $adresse_nom): self
    {
        $this->adresse_nom = $adresse_nom;

        return $this;
    }

    public function getAdresseCp(): ?string
    {
        return $this->adresse_cp;
    }

    public function setAdresseCp(?string $adresse_cp): self
    {
        $this->adresse_cp = $adresse_cp;

        return $this;
    }

    public function getAdresseVille(): ?string
    {
        return $this->adresse_ville;
    }

    public function setAdresseVille(?string $adresse_ville): self
    {
        $this->adresse_ville = $adresse_ville;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->date_creation;
    }

    public function setDateCreation(\DateTimeInterface $date_creation): self
    {
        $this->date_creation = $date_creation;

        return $this;
    }

    public function getTelFix(): ?string
    {
        return $this->tel_fix;
    }

    public function setTelFix(?string $tel_fix): self
    {
        $this->tel_fix = $tel_fix;

        return $this;
    }

    public function getTelPt(): ?string
    {
        return $this->tel_pt;
    }

    public function setTelPt(?string $tel_pt): self
    {
        $this->tel_pt = $tel_pt;

        return $this;
    }

    /**
     * @return Collection<int, Facture>
     */
    public function getFactures(): Collection
    {
        return $this->factures;
    }

    public function addFacture(Facture $facture): self
    {
        if (!$this->factures->contains($facture)) {
            $this->factures[] = $facture;
            $facture->setClient($this);
        }

        return $this;
    }

    public function removeFacture(Facture $facture): self
    {
        if ($this->factures->removeElement($facture)) {
            // set the owning side to null (unless already changed)
            if ($facture->getClient() === $this) {
                $facture->setClient(null);
            }
        }

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }
}
