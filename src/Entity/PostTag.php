<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PostTagRepository")
 */
class PostTag
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Post")
     */
    private $post;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Tag")
     */
    private $tag;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(?Post $post): self
    {
        $this->post = $post;

        return $this;
    }

    public function getTag(): ?Tag
    {
        return $this->tag;
    }

    public function setTag(?Tag $tag): self
    {
        $this->tag = $tag;

        return $this;
    }
}
