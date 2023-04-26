<?php

namespace App\Entity;

use App\Entity\Traits\Timestampable;
use App\Repository\NewsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=NewsRepository::class)
 * @HasLifecycleCallbacks 
 */
class News
{
    use Timestampable;

    public const NUM_ITEMS_PER_PAGE = 20;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $title;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $dateLimitInscription;

    /**
     * @ORM\ManyToOne(targetEntity=Etablissement::class, inversedBy="news")
     */
    private $etablissement;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     */
    private $publishedBy;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     */
    private $updatedBy;

    /**
     * @ORM\OneToMany(targetEntity=DocNews::class, mappedBy="news")
     */
    private $docNews;

    public function __construct()
    {
        $this->docNews = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getDateLimitInscription(): ?\DateTimeImmutable
    {
        return $this->dateLimitInscription;
    }

    public function setDateLimitInscription($dateLimitInscription): self
    {
        $this->dateLimitInscription = $dateLimitInscription;

        return $this;
    }

    public function getEtablissement(): ?Etablissement
    {
        return $this->etablissement;
    }

    public function setEtablissement(?Etablissement $etablissement): self
    {
        $this->etablissement = $etablissement;

        return $this;
    }

    public function getPublishedBy(): ?User
    {
        return $this->publishedBy;
    }

    public function setPublishedBy(?User $publishedBy): self
    {
        $this->publishedBy = $publishedBy;

        return $this;
    }

    public function getUpdatedBy(): ?User
    {
        return $this->updatedBy;
    }

    public function setUpdatedBy(?User $updatedBy): self
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }

    /**
     * @return Collection<int, DocNews>
     */
    public function getDocNews(): Collection
    {
        return $this->docNews;
    }

    public function addDocNews(DocNews $docNews): self
    {
        if (!$this->docNews->contains($docNews)) {
            $this->docNews[] = $docNews;
            $docNews->setNews($this);
        }

        return $this;
    }

    public function removeDocNews(DocNews $docNews): self
    {
        if ($this->docNews->removeElement($docNews)) {
            // set the owning side to null (unless already changed)
            if ($docNews->getNews() === $this) {
                $docNews->setNews(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->title;
    }
}
