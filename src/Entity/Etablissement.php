<?php

namespace App\Entity;

use App\Entity\Traits\Timestampable;
use App\Repository\EtablissementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=EtablissementRepository::class)
 * @HasLifecycleCallbacks 
 * @UniqueEntity("name")
 * @ORM\Table(name="etablissement", indexes={
 *  @ORM\Index(
 *      columns={"name", "sigle", "tags_text", "secteurs_text", "villes_text"},
 *      flags={"fulltext"}
 * )
 * })
 */
class Etablissement
{
    use Timestampable;

    public const NUM_ITEMS_PER_PAGE = 16;

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
     * @ORM\Column(type="string", length=255)
     */
    private $sigle;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Url
     */
    private $video;

    /**
     * @ORM\Column(type="json")
     */
    private $typeBac = [];

    /**
     * @ORM\Column(type="integer")
     * @Assert\Positive
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
     * @ORM\Column(type="decimal", nullable=true, precision=12, scale=2, options={"default":0})
     * @Assert\PositiveOrZero
     */
    private $seuilSM = 0;

    /**
     * @ORM\Column(type="decimal", nullable=true, precision=12, scale=2, options={"default":0})
     * @Assert\PositiveOrZero
     */
    private $seuilSP = 0;

    /**
     * @ORM\Column(type="decimal", nullable=true, precision=12, scale=2, options={"default":0})
     * @Assert\PositiveOrZero
     */
    private $seuilSVT = 0;

    /**
     * @ORM\Column(type="decimal", nullable=true, precision=12, scale=2, options={"default":0})
     * @Assert\PositiveOrZero
     */
    private $seuilSAgro = 0;

    /**
     * @ORM\Column(type="decimal", nullable=true, precision=12, scale=2, options={"default":0})
     * @Assert\PositiveOrZero
     */
    private $seuilEco = 0;

    /**
     * @ORM\Column(type="decimal", nullable=true, precision=12, scale=2, options={"default":0})
     * @Assert\PositiveOrZero
     */
    private $seuilSGC = 0;

    /**
     * @ORM\Column(type="decimal", nullable=true, precision=12, scale=2, options={"default":0})
     * @Assert\PositiveOrZero
     */
    private $seuilSTM = 0;

    /**
     * @ORM\Column(type="decimal", nullable=true, precision=12, scale=2, options={"default":0})
     * @Assert\PositiveOrZero
     */
    private $seuilSTE = 0;

    /**
     * @ORM\Column(type="decimal", nullable=true, precision=12, scale=2, options={"default":0})
     * @Assert\PositiveOrZero
     */
    private $seuilLSH = 0;

    /**
     * @ORM\Column(type="decimal", nullable=true, precision=12, scale=2, options={"default": "0"})
     * @Assert\PositiveOrZero
     */
    private $seuilChariaa = 0;

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
     * @Assert\Url
     */
    private $website;

    /**
     * @ORM\ManyToMany(targetEntity=Tags::class, inversedBy="etablissements")
     */
    private $tags;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Url
     */
    private $fb;

    /**
     * @ORM\ManyToMany(targetEntity=Secteur::class, inversedBy="etablissements")
     */
    private $secteurs;

    /**
     * @ORM\OneToMany(targetEntity=DocumentEtablissement::class, mappedBy="etablissement")
     */
    private $documentEtablissements;

    /**
     * @ORM\ManyToMany(targetEntity=Ville::class)
     */
    private $villes;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $tagsText;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $secteursText;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $villesText;

    /**
     * @ORM\OneToOne(targetEntity=News::class, cascade={"persist"})
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $news;

    /**
     * @ORM\Column(type="boolean", options={"default": "false"})
     */
    private $isEtranger = false;


    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->secteurs = new ArrayCollection();
        $this->documentEtablissements = new ArrayCollection();
        $this->villes = new ArrayCollection();
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

    public function getTypeBac(): array
    {
        $typeBac = $this->typeBac;
        return array_unique($typeBac);
    }

    public function setTypeBac(array $typeBac): self
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

    public function getSeuilSGC(): ?float
    {
        return $this->seuilSGC;
    }

    public function setSeuilSGC(float $seuilSGC): self
    {
        $this->seuilSGC = $seuilSGC;

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

    public function getSeuilLSH(): ?float
    {
        return $this->seuilLSH;
    }

    public function setSeuilLSH(float $seuilLSH): self
    {
        $this->seuilLSH = $seuilLSH;

        return $this;
    }

    public function getSeuilChariaa(): ?float
    {
        return $this->seuilChariaa;
    }

    public function setSeuilChariaa(float $seuilChariaa): self
    {
        $this->seuilChariaa = $seuilChariaa;

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

    public function __toString()
    {
        return sprintf('%s (%s)', $this->name, $this->sigle);
    }

    /**
     * @return Collection<int, Tags>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tags $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    public function removeTag(Tags $tag): self
    {
        $this->tags->removeElement($tag);

        return $this;
    }

    public function getFb(): ?string
    {
        return $this->fb;
    }

    public function setFb(?string $fb): self
    {
        $this->fb = $fb;

        return $this;
    }

    /**
     * @return Collection<int, Secteur>
     */
    public function getSecteurs(): Collection
    {
        return $this->secteurs;
    }

    public function addSecteur(Secteur $secteur): self
    {
        if (!$this->secteurs->contains($secteur)) {
            $this->secteurs[] = $secteur;
        }

        return $this;
    }

    public function removeSecteur(Secteur $secteur): self
    {
        $this->secteurs->removeElement($secteur);

        return $this;
    }

    /**
     * @return Collection<int, DocumentEtablissement>
     */
    public function getDocumentEtablissements(): Collection
    {
        return $this->documentEtablissements;
    }

    public function addDocumentEtablissement(DocumentEtablissement $documentEtablissement): self
    {
        if (!$this->documentEtablissements->contains($documentEtablissement)) {
            $this->documentEtablissements[] = $documentEtablissement;
            $documentEtablissement->setEtablissement($this);
        }

        return $this;
    }

    public function removeDocumentEtablissement(DocumentEtablissement $documentEtablissement): self
    {
        if ($this->documentEtablissements->removeElement($documentEtablissement)) {
            // set the owning side to null (unless already changed)
            if ($documentEtablissement->getEtablissement() === $this) {
                $documentEtablissement->setEtablissement(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Ville>
     */
    public function getVilles(): Collection
    {
        return $this->villes;
    }

    public function addVille(Ville $ville): self
    {
        if (!$this->villes->contains($ville)) {
            $this->villes[] = $ville;
        }

        return $this;
    }

    public function removeVille(Ville $ville): self
    {
        $this->villes->removeElement($ville);

        return $this;
    }

    public function getTagsText(): ?string
    {
        return $this->tagsText;
    }

    public function setTagsText(?string $tagsText): self
    {
        $this->tagsText = $tagsText;

        return $this;
    }

    public function getSecteursText(): ?string
    {
        return $this->secteursText;
    }

    public function setSecteursText(?string $secteursText): self
    {
        $this->secteursText = $secteursText;

        return $this;
    }

    public function getVillesText(): ?string
    {
        return $this->villesText;
    }

    public function setVillesText(?string $villesText): self
    {
        $this->villesText = $villesText;

        return $this;
    }

    public function getNews(): ?News
    {
        return $this->news;
    }

    public function setNews(?News $news): self
    {
        $this->news = $news;

        return $this;
    }

    public function isIsEtranger(): ?bool
    {
        return $this->isEtranger;
    }

    public function setIsEtranger(bool $isEtranger): self
    {
        $this->isEtranger = $isEtranger;

        return $this;
    }
}
