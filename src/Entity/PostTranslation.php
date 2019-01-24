<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PostTranslationRepository")
 * @UniqueEntity(
 *     message="Post with this title already exist",
 *     fields={"title_en", "title_hr"}
 * )
 */
class PostTranslation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Post", inversedBy="postTranslation", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Type(type="App\Entity\Post")
     * @Assert\Valid
     */
    private $post;

    /**
     * @ORM\Column(type="string", length=255, unique=true, name="title_en")
     * @Assert\Length(
     *      max = 255,
     *      maxMessage = "Title cannot be longer than {{ limit }} characters"
     * )
     * @Assert\NotBlank
     */
    private $title_en;

    /**
     * @ORM\Column(type="string", length=255, unique=true, name="title_hr")
     * @Assert\Length(
     *      max = 255,
     *      maxMessage = "Title cannot be longer than {{ limit }} characters"
     * )
     * @Assert\NotBlank
     *
     */
    private $title_hr;

    /**
     * @ORM\Column(type="text", nullable=false)
     */
    private $content_en;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $content_hr;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $slug_en;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $slug_hr;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(Post $post): self
    {
        $this->post = $post;

        return $this;
    }

    public function getTitleEn(): ?string
    {
        return $this->title_en;
    }

    public function setTitleEn(?string $title_en): self
    {
        $this->title_en = $title_en;

        return $this;
    }

    public function getTitleHr(): ?string
    {
        return $this->title_hr;
    }

    public function setTitleHr(?string $title_hr): self
    {
        $this->title_hr = $title_hr;

        return $this;
    }

    public function getContentEn(): ?string
    {
        return $this->content_en;
    }

    public function setContentEn(?string $content_en): self
    {
        $this->content_en = $content_en;

        return $this;
    }

    public function getContentHr(): ?string
    {
        return $this->content_hr;
    }

    public function setContentHr(?string $content_hr): self
    {
        $this->content_hr = $content_hr;

        return $this;
    }

    public function getSlugEn(): ?string
    {
        return $this->slug_en;
    }

    public function setSlugEn(?string $slug_en): self
    {
        $this->slug_en = $slug_en;

        return $this;
    }

    public function getSlugHr(): ?string
    {
        return $this->slug_hr;
    }

    public function setSlugHr(?string $slug_hr): self
    {
        $this->slug_hr = $slug_hr;

        return $this;
    }
}
