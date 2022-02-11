<?php

namespace App\Entity;

use App\Repository\MovieRepository;
use App\Traits\Timestampable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MovieRepository::class)
 */
class Movie
{
    use Timestampable;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $length;

    /**
     * @ORM\Column(type="datetime")
     */
    private $releaseDate;

    /**
     * @ORM\Column(type="text")
     */
    private $overview;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $posterUrl;

    /**
     * @ORM\OneToOne(targetEntity=TMDB::class, cascade={"persist", "remove"})
     */
    private $TMDB;

    /**
     * @ORM\OneToMany(targetEntity=Director::class, mappedBy="movie")
     */
    private $Director;

    /**
     * @ORM\ManyToMany(targetEntity=Genre::class, inversedBy="movies")
     */
    private $Genre;

    public function __construct()
    {
        $this->Director = new ArrayCollection();
        $this->Genre = new ArrayCollection();
        $this->setCreatedAt();
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

    public function getLength(): ?string
    {
        return $this->length;
    }

    public function setLength(string $length): self
    {
        $this->length = $length;

        return $this;
    }

    public function getReleaseDate(): ?\DateTimeInterface
    {
        return $this->releaseDate;
    }

    public function setReleaseDate(\DateTimeInterface $releaseDate): self
    {
        $this->releaseDate = $releaseDate;

        return $this;
    }

    public function getOverview(): ?string
    {
        return $this->overview;
    }

    public function setOverview(string $overview): self
    {
        $this->overview = $overview;

        return $this;
    }

    public function getPosterUrl(): ?string
    {
        return $this->posterUrl;
    }

    public function setPosterUrl(?string $posterUrl): self
    {
        $this->posterUrl = $posterUrl;

        return $this;
    }

    public function getTMDB(): ?TMDB
    {
        return $this->TMDB;
    }

    public function setTMDB(?TMDB $TMDB): self
    {
        $this->TMDB = $TMDB;

        return $this;
    }

    /**
     * @return Collection|Director[]
     */
    public function getDirector(): Collection
    {
        return $this->Director;
    }

    public function addDirector(Director $director): self
    {
        if (!$this->Director->contains($director)) {
            $this->Director[] = $director;
            $director->setMovie($this);
        }

        return $this;
    }

    public function removeDirector(Director $director): self
    {
        if ($this->Director->removeElement($director)) {
            // set the owning side to null (unless already changed)
            if ($director->getMovie() === $this) {
                $director->setMovie(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Genre[]
     */
    public function getGenre(): Collection
    {
        return $this->Genre;
    }

    public function addGenre(Genre $genre): self
    {
        if (!$this->Genre->contains($genre)) {
            $this->Genre[] = $genre;
        }

        return $this;
    }

    public function removeGenre(Genre $genre): self
    {
        $this->Genre->removeElement($genre);

        return $this;
    }
}
