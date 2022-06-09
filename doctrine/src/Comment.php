<?php
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="comment")
 */
class Comment
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $comment_id;

    /**
     * @ORM\Column(type="string")
     */
    private $status;

    /**
     * @ORM\Column(type="string")
     */
    private $callback;

    /**
     * @ORM\Column(type="string")
     */
    private $delete;

    public function getId(): int
    {
        return $this->id;
    }

    public function getCommentId(): int
    {
        return $this->comment_id;
    }

    public function setCommentId(int $id): void
    {
        $this->comment_id = $id;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getCallback(): string
    {
        return $this->callback;
    }

    public function setCallback(string $callback): void
    {
        $this->callback = $callback;
    }

    public function getDelete(): string
    {
        return $this->delete;
    }

    public function setDelete(string $delete_url): void
    {
        $this->delete = $delete_url;
    }
}