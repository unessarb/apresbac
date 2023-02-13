<?php

namespace App\Entity;

use App\Entity\Traits\Timestampable;
use App\Repository\EtablissementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=EtablissementRepository::class)
 * @HasLifecycleCallbacks 
 */
class Etablissement
{
    use Timestampable;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Gedmo\Slug(fields={"name"}, updatable=false)
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $sigle;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $video;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $typeBac;

    /**
     * @ORM\Column(type="integer")
     */
    private $dureeFormation;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $diplome;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $modeAdmis;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @ORM\Column(type="decimal")
     */
    private $seuilSM;

    /**
     * @ORM\Column(type="decimal")
     */
    private $seuilSP;

    /**
     * @ORM\Column(type="decimal")
     */
    private $seuilSVT;

    /**
     * @ORM\Column(type="decimal")
     */
    private $seuilSAgro;

    /**
     * @ORM\Column(type="decimal")
     */
    private $seuilEco;

    /**
     * @ORM\Column(type="decimal")
     */
    private $seuilSTM;

    /**
     * @ORM\Column(type="decimal")
     */
    private $seuilSTE;

    /**
     * @ORM\Column(type="boolean", options={"default": "true"})
     */
    private $isPublic = true;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $phone;

    /**
     * @ORM\Column(type="boolean", options={"default": "false"})
     */
    private $isFavorite = false;

    /**
     * @ORM\ManyToOne(targetEntity=Ville::class, inversedBy="etablissements")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ville;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $logo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $phoneWP;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $website;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $tags;

    /**
     * @ORM\ManyToOne(targetEntity=Secteur::class, inversedBy="etablissements")
     */
    private $secteur;

    /**
     * @ORM\OneToMany(targetEntity=News::class, mappedBy="etablissement")
     */
    private $news;

    public function __construct()
    {
        $this->news = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getSigle(): ?string
    {
        return $this->sigle;
    }

    public function setSigle(string $sigle): self
    {
        $this->sigle = $sigle;

        return $this;
    }

    public function getVideo(): ?string
    {
        return $this->video;
    }

    public function setVideo(string $video): self
    {
        $this->video = $video;

        return $this;
    }

    public function getTypeBac(): ?string
    {
        return $this->typeBac;
    }

    public function setTypeBac(string $typeBac): self
    {
        $this->typeBac = $typeBac;

        return $this;
    }

    public function getDureeFormation(): ?int
    {
        return $this->dureeFormation;
    }

    public function setDureeFormation(int $dureeFormation): self
    {
        $this->dureeFormation = $dureeFormation;

        return $this;
    }

    public function getDiplome(): ?string
    {
        return $this->diplome;
    }

    public function setDiplome(string $diplome): self
    {
        $this->diplome = $diplome;

        return $this;
    }

    public function getModeAdmis(): ?string
    {
        return $this->modeAdmis;
    }

    public function setModeAdmis(string $modeAdmis): self
    {
        $this->modeAdmis = $modeAdmis;

        return $this;
    }

    public function isIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getSeuilSM(): ?float
    {
        return $this->seuilSM;
    }

    public function setSeuilSM(float $seuilSM): self
    {
        $this->seuilSM = $seuilSM;

        return $this;
    }

    public function getSeuilSP(): ?float
    {
        return $this->seuilSP;
    }

    public function setSeuilSP(float $seuilSP): self
    {
        $this->seuilSP = $seuilSP;

        return $this;
    }

    public function getSeuilSVT(): ?float
    {
        return $this->seuilSVT;
    }

    public function setSeuilSVT(float $seuilSVT): self
    {
        $this->seuilSVT = $seuilSVT;

        return $this;
    }

    public function getSeuilSAgro(): ?float
    {
        return $this->seuilSAgro;
    }

    public function setSeuilSAgro(float $seuilSAgro): self
    {
        $this->seuilSAgro = $seuilSAgro;

        return $this;
    }

    public function getSeuilEco(): ?float
    {
        return $this->seuilEco;
    }

    public function setSeuilEco(float $seuilEco): self
    {
        $this->seuilEco = $seuilEco;

        return $this;
    }

    public function getSeuilSTM(): ?float
    {
        return $this->seuilSTM;
    }

    public function setSeuilSTM(float $seuilSTM): self
    {
        $this->seuilSTM = $seuilSTM;

        return $this;
    }

    public function getSeuilSTE(): ?float
    {
        return $this->seuilSTE;
    }

    public function setSeuilSTE(float $seuilSTE): self
    {
        $this->seuilSTE = $seuilSTE;

        return $this;
    }

    public function isIsPublic(): ?bool
    {
        return $this->isPublic;
    }

    public function setIsPublic(bool $isPublic): self
    {
        $this->isPublic = $isPublic;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function isIsFavorite(): ?bool
    {
        return $this->isFavorite;
    }

    public function setIsFavorite(bool $isFavorite): self
    {
        $this->isFavorite = $isFavorite;

        return $this;
    }

    public function getVille(): ?Ville
    {
        return $this->ville;
    }

    public function setVille(?Ville $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): self
    {
        $this->logo = $logo;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getPhoneWP(): ?string
    {
        return $this->phoneWP;
    }

    public function setPhoneWP(?string $phoneWP): self
    {
        $this->phoneWP = $phoneWP;

        return $this;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(string $website): self
    {
        $this->website = $website;

        return $this;
    }

    public function getTags(): ?string
    {
        return $this->tags;
    }

    public function setTags(?string $tags): self
    {
        $this->tags = $tags;

        return $this;
    }

    public function getSecteur(): ?secteur
    {
        return $this->secteur;
    }

    public function setSecteur(?secteur $secteur): self
    {
        $this->secteur = $secteur;

        return $this;
    }

    /**
     * @return Collection<int, News>
     */
    public function getNews(): Collection
    {
        return $this->news;
    }

    public function addNews(News $news): self
    {
        if (!$this->news->contains($news)) {
            $this->news[] = $news;
            $news->setEtablissement($this);
        }

        return $this;
    }

    public function removeNews(News $news): self
    {
        if ($this->news->removeElement($news)) {
            // set the owning side to null (unless already changed)
            if ($news->getEtablissement() === $this) {
                $news->setEtablissement(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return sprintf('%s (%s)', $this->name, $this->sigle);
    }
}
