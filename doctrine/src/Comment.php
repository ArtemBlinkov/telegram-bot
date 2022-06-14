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
    private $answer_url;

    /**
     * @ORM\Column(type="string")
     */
    private $delete_url;

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

    public function getAnswerUrl(): string
    {
        return $this->answer_url;
    }

    public function setAnswerUrl(string $answer_url): void
    {
        $this->answer_url = $answer_url;
    }

    public function getDeleteUrl(): string
    {
        return $this->delete_url;
    }

    public function setDelete(string $delete_url): void
    {
        $this->delete_url = $delete_url;
    }
}