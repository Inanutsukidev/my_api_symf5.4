<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use App\Repository\FactureRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass=FactureRepository::class)
 */
class Facture
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $num_facture;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $etat;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date_intervention;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_creation;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date_facturation;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date_paiement;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $montant_total_ttc;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $devise;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $doc_type;

    /**
     * @ORM\ManyToOne(targetEntity=Client::class, inversedBy="factures")
     */
    private $client;

    public function __construct()
    {
        $this->produits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumFacture(): ?string
    {
        return $this->num_facture;
    }

    public function setNumFacture(string $num_facture): self
    {
        $this->num_facture = $num_facture;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getDateIntervention(): ?\DateTimeInterface
    {
        return $this->date_intervention;
    }

    public function setDateIntervention(?\DateTimeInterface $date_intervention): self
    {
        $this->date_intervention = $date_intervention;

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

    public function getDateFacturation(): ?\DateTimeInterface
    {
        return $this->date_facturation;
    }

    public function setDateFacturation(?\DateTimeInterface $date_facturation): self
    {
        $this->date_facturation = $date_facturation;

        return $this;
    }

    public function getDatePaiement(): ?\DateTimeInterface
    {
        return $this->date_paiement;
    }

    public function setDatePaiement(?\DateTimeInterface $date_paiement): self
    {
        $this->date_paiement = $date_paiement;

        return $this;
    }

    public function getMontantTotalTtc(): ?int
    {
        return $this->montant_total_ttc;
    }

    public function setMontantTotalTtc(?int $montant_total_ttc): self
    {
        $this->montant_total_ttc = $montant_total_ttc;

        return $this;
    }

    public function getDevise(): ?string
    {
        return $this->devise;
    }

    public function setDevise(?string $devise): self
    {
        $this->devise = $devise;

        return $this;
    }

    public function getDocType(): ?string
    {
        return $this->doc_type;
    }

    public function setDocType(string $doc_type): self
    {
        $this->doc_type = $doc_type;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }
}
