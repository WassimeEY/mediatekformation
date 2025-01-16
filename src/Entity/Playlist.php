<?php

namespace App\Entity;

use App\Repository\PlaylistRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Entité Playlist.
 */
#[ORM\Entity(repositoryClass: PlaylistRepository::class)]
class Playlist
{
    /**
     * L'id de l'entité.
     * @var int|null L'entier qui est l'id.
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Le nom de la playlist.
     * @var string|null Le str qui correspond au nom.
     */
    #[ORM\Column(length: 100, nullable: true)]
    private ?string $name = null;

    /**
     * La description de la playlist.
     * @var string|null Le str qui correspond à la description.
     */
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    /**
     * Les formations qui sont dans cette playlist.
     * @var Collection<int, Formation> La collection de formation en question.
     */
    #[ORM\OneToMany(targetEntity: Formation::class, mappedBy: 'playlist')]
    private Collection $formations;

    /**
     * Le constructeur de l'entité Playlist.
     */
    public function __construct()
    {
        $this->formations = new ArrayCollection();
    }

    /**
     * Retourne l'id de la playlist.
     * @return int|null L'id de la playlist, peut être null.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Retourne le nom de la playlist.
     * @return string|null Le nom de la playlist, peut être null.
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Permet de définir la variable $name.
     * @param string|null $name Le nouveau nom.
     * @return static La playlist.
     */
    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Retourne la description de la playlist.
     * @return string|null La description de la playlist, peut être null.
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Permet de définir la variable $description.
     * @param string|null $description La nouvelle description
     * @return static La playlist.
     */
    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Retourne les formations de la playlist.
     * @return Collection<int, Formation> Les formations de la playlist.
     */
    public function getFormations(): Collection
    {
        return $this->formations;
    }

    /**
     * Ajoute une formation dans la collection $formations de la playlist.
     * @param Formation $formation La formation à ajouter dans $formations.
     * @return static La playlist.
     */
    public function addFormation(Formation $formation): static
    {
        if (!$this->formations->contains($formation)) {
            $this->formations->add($formation);
            $formation->setPlaylist($this);
        }

        return $this;
    }

    /**
     * Supprime une formation de la collection $formations de la playlist.
     * @param Formation $formation La formation à supprimer dans $formations.
     * @return static La playlist.
     */
    public function removeFormation(Formation $formation): static
    {
        if ($this->formations->removeElement($formation) && $formation->getPlaylist() === $this) {
            // set the owning side to null (unless already changed)
            $formation->setPlaylist(null);
        }
        return $this;
    }

    /**
     * Retourne les catégories de la playlist.
     * @return Collection<int, string> Les catégories de la playlist.
     */
    public function getCategoriesPlaylist(): Collection
    {
        $categories = new ArrayCollection();
        foreach ($this->formations as $formation) {
            $categoriesFormation = $formation->getCategories();
            foreach ($categoriesFormation as $categorieFormation) {
                if (!$categories->contains($categorieFormation->getName())) {
                    $categories[] = $categorieFormation->getName();
                }
            }
        }
        return $categories;
    }
    
    /**
     * On override la fonction __toString() pour faire en sorte de retourner le nom de la playlist, c'est utile pour le formBuilder par exemple.
     * @return string Le nom de la playlist.
     */
    public function __toString(): string
    {
        if($this->getName() != null){
            return $this->getName();
        }
        return "";
    }
}
