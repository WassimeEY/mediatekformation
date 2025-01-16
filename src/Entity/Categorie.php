<?php

namespace App\Entity;

use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Entité Categorie.
 */
#[ORM\Entity(repositoryClass: CategorieRepository::class)]
class Categorie
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
     * Le nom de la catégorie.
     * @var string|null Le str qui correspond au nom.
     */
    #[ORM\Column(length: 50, nullable: true)]
    private ?string $name = null;

    /**
     * Les formations qui sont de cette catégorie.
     * @var Collection<int, Formation> La collection qui correspond aux formations.
     */
    #[ORM\ManyToMany(targetEntity: Formation::class, mappedBy: 'categories')]
    private Collection $formations;

    /**
     * Le constructeur de l'entité Categorie.
     */
    public function __construct()
    {
        $this->formations = new ArrayCollection();
    }

    /**
     * Retourne l'id de la catégorie.
     * @return int|null L'id de la catégorie, peut être null.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Retourne le nom de la catégorie.
     * @return string|null Le nom de la catégorie, peut être null.
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Permet de définir la variable $name.
     * @param string|null $name Le nouveau nom.
     * @return static L'entité Categorie.
     */
    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Retourne les formations de la catégorie.
     * @return Collection<int, Formation> Les formations de la catégorie.
     */
    public function getFormations(): Collection
    {
        return $this->formations;
    }

    /**
     * Ajoute une formation dans la collection $formations de la catégorie.
     * @param Formation $formation La formation à ajouter dans $formations.
     * @return static L'entité Categorie.
     */
    public function addFormation(Formation $formation): static
    {
        if (!$this->formations->contains($formation)) {
            $this->formations->add($formation);
            $formation->addCategory($this);
        }

        return $this;
    }

    /**
     * Supprime une formation de la collection $formations de la catégorie.
     * @param Formation $formation La formation à supprimer dans $formations.
     * @return static L'entité Categorie.
     */
    public function removeFormation(Formation $formation): static
    {
        if ($this->formations->removeElement($formation)) {
            $formation->removeCategory($this);
        }

        return $this;
    }
    
}
