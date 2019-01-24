<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Intl\Locale;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PostRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Post
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="array")
     */
    private $status = [];

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateCreated;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Tag", inversedBy="post")
     */
    private $tags;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="post",  orphanRemoval=true)
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\LikeCounter", mappedBy="post", orphanRemoval=true)
     */
    private $likeCounters;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Favorite", mappedBy="post", orphanRemoval=true)
     */
    private $favorite;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\PostTranslation", mappedBy="post", cascade={"persist", "remove"})
     * @var PostTranslation
     */
    private $postTranslation;

    private $title;
    private $content;
    private $slug;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->likeCounters = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): ?array
    {
        return $this->status;
    }

    public function setStatus(array $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getDateCreated(): ?\DateTimeInterface
    {
        return $this->dateCreated;
    }

    /**
     * @ORM\PrePersist
     */
    public function setDateCreated(): self
    {
        $this->dateCreated = new \DateTime();

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection|Tag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
            $tag->addPost($this);
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
            $tag->removePost($this);
        }

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setPost($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getPost() === $this) {
                $comment->setPost(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|LikeCounter[]
     */
    public function getLikeCounters(): Collection
    {
        return $this->likeCounters;
    }

    public function addLikeCounter(LikeCounter $likeCounter): self
    {
        if (!$this->likeCounters->contains($likeCounter)) {
            $this->likeCounters[] = $likeCounter;
            $likeCounter->setPost($this);
        }

        return $this;
    }

    public function removeLikeCounter(LikeCounter $likeCounter): self
    {
        if ($this->likeCounters->contains($likeCounter)) {
            $this->likeCounters->removeElement($likeCounter);
            // set the owning side to null (unless already changed)
            if ($likeCounter->getPost() === $this) {
                $likeCounter->setPost(null);
            }
        }

        return $this;
    }

    public function getPostTranslation(): ?PostTranslation
    {
        return $this->postTranslation;
    }

    public function setPostTranslation(PostTranslation $postTranslation): self
    {
        $this->postTranslation = $postTranslation;

        // set the owning side of the relation if necessary
        if ($this !== $postTranslation->getPost()) {
            $postTranslation->setPost($this);
        }

        return $this;
    }

    public function getTitle()
    {
        return $this->postTranslation->{'getTitle'.Locale::getDefault()}();
    }

    public function getContent()
    {
        return $this->postTranslation->{'getContent'.Locale::getDefault()}();
    }

    public function getSlug()
    {
        return $this->postTranslation->{'getSlug'.Locale::getDefault()}();
    }

}
