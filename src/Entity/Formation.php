<?php

namespace App\Entity;

use App\Repository\FormationRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Entité Formation.
 */
#[ORM\Entity(repositoryClass: FormationRepository::class)]
class Formation
{

    /**
     * Début de chemin vers les images
     */
    private const CHEMINIMAGE = "https://i.ytimg.com/vi/";
    
    /**
     * L'id de l'entité.
     * @var int|null L'entier qui est l'id.
     */    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * La date de publication de la formation.
     * @var DateTimeInterface|null La DateTime qui correspond à la date de publication.
     */
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Assert\LessThanOrEqual('today')]
    private ?DateTimeInterface $publishedAt = null;

    /**
     * Le titre de la formation.
     * @var string|null Le str qui correspond au titre.
     */
    #[ORM\Column(length: 100, nullable: true)]
    private ?string $title = null;

    /**
     * La description de la formation
     * @var string|null Le str qui correspond à la description.
     */
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    /**
     * L'id de la vidéo de cette formation.
     * @var string|null Le str qui correspond à l'id de la vidéo.
     */
    #[ORM\Column(length: 20, nullable: true)]
    private ?string $videoId = null;

    /**
     * La playlist de la formation.
     * @var Playlist|null La playlist en question.
     */
    #[ORM\ManyToOne(inversedBy: 'formations')]
    private ?Playlist $playlist = null;

    /**
     * Les catégories de cette formation.
     * @var Collection<int, Categorie> La colelction qui correspond aux catégories.
     */
    #[ORM\ManyToMany(targetEntity: Categorie::class, inversedBy: 'formations')]
    private Collection $categories;

    /**
     * Le constructeur de l'entité Formation.
     */
    public function __construct()
    {
        $this->categories = new ArrayCollection();
    }

    /**
     * Retourne l'id de la formation.
     * @return int|null L'id de la formation, peut être null.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Retourne la date de publication de la formation.
     * @return DateTimeInterface|null La date de publication de la formation, peut être null.
     */
    public function getPublishedAt(): ?DateTimeInterface
    {
        return $this->publishedAt;
    }

    /**
     * Permet de définir la variable $publishedAt.
     * @param DateTimeInterface|null $publishedAt La nouvelle date de publication de la formation.
     * @return static La formation.
     */
    public function setPublishedAt(?DateTimeInterface $publishedAt): static
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    /**
     * Retourne la date de publication de la formation en str.
     * @return string La date de publication de la formation formaté et sous forme de str.
     */
    public function getPublishedAtString(): string
    {
        if ($this->publishedAt == null) {
            return "";
        }
        return $this->publishedAt->format('d/m/Y');
    }

    /**
     * Retourne le titre de la formation.
     * @return string|null Le titre de la formation, peut être null.
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Permet de définir la variable $title.
     * @param string|null $title Le nouveau titre.
     * @return static La formation.
     */
    public function setTitle(?string $title): static
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Retourne la description de la formation.
     * @return string|null La description de la formation, peut être null.
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Permet de définir la variable $description.
     * @param string|null $description La nouvelle description.
     * @return static La formation.
     */
    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Retourne l'id de la vidéo de la formation.
     * @return string|null L'id de la vidéo de la formation, peut être null.
     */
    public function getVideoId(): ?string
    {
        return $this->videoId;
    }

    /**
     * Permet de définir la variable $videoId.
     * @param string|null $videoId Le nouveau id de la vidéo de la formation.
     * @return static La formation.
     */
    public function setVideoId(?string $videoId): static
    {
        $this->videoId = $videoId;

        return $this;
    }

    /**
     * Retourne le chemin de la miniature de la vidéo de la formation.
     * @return string|null Le chemin de la miniature de la vidéo de la formation, peut être null.
     */
    public function getMiniature(): ?string
    {
        return self::CHEMINIMAGE.$this->videoId."/default.jpg";
    }

    /**
     * Retourne le chemin de l'image de la formation.
     * @return string|null Le chemin de l'image de la formation, peut être null.
     */
    public function getPicture(): ?string
    {
        return self::CHEMINIMAGE.$this->videoId."/hqdefault.jpg";
    }
    
    /**
     * Retourne la playlist de la formation.
     * @return Playlist|null La playlist de la formation, peut être null.
     */
    public function getPlaylist(): ?playlist
    {
        return $this->playlist;
    }

    /**
     * Permet de définir la variable $playlist.
     * @param Playlist|null $playlist La nouvelle playlist de la formation.
     * @return static La formation.
     */
    public function setPlaylist(?Playlist $playlist): static
    {
        $this->playlist = $playlist;

        return $this;
    }

    /**
     * Retourne les catégories de la formation.
     * @return Collection<int, Categorie> Les catégories de la formation.
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    /**
     * Ajoute une catégorie dans la collection $categories de la formation.
     * @param Categorie $category La catégorie à ajouter dans $categories.
     * @return static La formation.
     */
    public function addCategory(Categorie $category): static
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
        }

        return $this;
    }

    /**
     * Supprime une catégorie de la collection $categories de la formation.
     * @param Categorie $category La catégorie à supprimer dans $categories.
     * @return static La formation.
     */
    public function removeCategory(Categorie $category): static
    {
        $this->categories->removeElement($category);

        return $this;
    }
    
    /**
     * On override la fonction __toString() pour faire en sorte de retourner le titre de la formation, c'est utile pour le formBuilder par exemple.
     * @return string Le nom de la playlist.
     */
    public function __toString(): string
    {
        if($this->getTitle() != null){
            return $this->getTitle();
        }
        return "";
    }
}
