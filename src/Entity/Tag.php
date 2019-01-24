<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Intl\Locale;
use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TagRepository")
 * @UniqueEntity("nameEn")
 */
class Tag
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\Length(
     *      min = 2,
     *      max = 30,
     *      minMessage = "Tag name must be at least {{ limit }} characters long",
     *      maxMessage = "Tag name cannot be longer than {{ limit }} characters"
     * )
     * @Assert\NotBlank
     * @ORM\Column(type="string", length=30)
     */
    private $nameEn;

    /**
     * @Assert\Length(
     *      min = 2,
     *      max = 30,
     *      minMessage = "Tag name must be at least {{ limit }} characters long",
     *      maxMessage = "Tag name cannot be longer than {{ limit }} characters"
     * )
     * @Assert\NotBlank
     * @ORM\Column(name="name_hr", type="string", length=30, unique=true)
     */
    private $nameHr;


    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Post", mappedBy="tags")
     */
    private $post;

    public function __construct()
    {
        $this->post = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->{'getName'.Locale::getDefault()}();
    }


    public function getNameEn(): ?string
    {
        return $this->nameEn;
    }

    public function setNameEn(string $name): self
    {
        $this->nameEn = $name;

        return $this;
    }

    public function getNameHr(): ?string
    {
        return $this->nameHr;
    }

    public function setNameHr(string $name): self
    {
        $this->nameHr = $name;

        return $this;
    }

    /**
     * @return Collection|Post[]
     */
    public function getPost(): Collection
    {
        return $this->post;
    }

    public function addPost(Post $post): self
    {
        if (!$this->post->contains($post)) {
            $this->post[] = $post;
        }

        return $this;
    }

    public function removePost(Post $post): self
    {
        if ($this->post->contains($post)) {
            $this->post->removeElement($post);
        }

        return $this;
    }
}
