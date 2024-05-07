<?php

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\TransactionRequiredException;

class CommentRecord
{
    /**
     * @var string[]
     */
    protected array $status = [
        'NEW' => 'NOT_ANSWERED',
        'PROCESS' => 'WAIT'
    ];

    /**
     * @var Comment
     */
    protected Comment $doctrine;

    /**
     * @var EntityManager
     */
    protected EntityManager $entity;

    /**
     * CommentRecord constructor.
     * @throws \Doctrine\DBAL\Exception
     * @throws ORMException
     */
    public function __construct()
    {
        $this->entity = Entity::getEntityManager();
        $this->doctrine = new Comment();
    }

    /**
     * @param $id
     * @return false|object
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws TransactionRequiredException
     */
    public function getComment($id)
    {
        $comment = $this->entity->find('Comment', $id);

        if ($comment) {
            return $comment;
        } else {
            return false;
        }
    }

    /**
     * @param $id
     * @return false|object
     */
    public function getCommentByParent($id)
    {
        $comment = $this->entity
            ->getRepository('Comment')
            ->findOneBy(['comment_id' => $id]);

        if ($comment) {
            return $comment;
        } else {
            return false;
        }
    }

    /**
     * @param $data
     * @return int
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function createComment($data): int
    {
        // записываем данные из массива
        $this->doctrine->setCommentId($data['id']);
        $this->doctrine->setDelete($data['callback']['delete']);
        $this->doctrine->setAnswerUrl($data['callback']['answer']);

        // присваиваем первичный статус
        $this->doctrine->setStatus($this->status['NEW']);

        // создаём запись в БД
        $this->entity->persist($this->doctrine);
        $this->entity->flush();

        // возвращаем id записи из БД
        return $this->doctrine->getId();
    }

    /**
     * @return false|object|null
     */
    public function checkComment()
    {
        // получаем репозиторий сущности
        $repository = $this->entity->getRepository('Comment');

        // ищем записи со статусом WAIT
        $comment = $repository->findBy(['status' => $this->status['PROCESS']]);

        // возвращаем запись или возвращаем false
        if ($comment) {
            return array_shift($comment);
        } else {
            return false;
        }
    }

    /**
     * @param $id
     * @param string $status
     * @return bool
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws TransactionRequiredException
     */
    public function updateComment($id, $status = 'PROCESS'): bool
    {
        $comment = $this->entity->find('Comment', $id);

        if ($comment) {
            $comment->setStatus($this->status[$status]);
        } else {
            return false;
        }

        $this->entity->flush();

        return true;
    }

    /**
     * @param $id
     * @return bool
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws TransactionRequiredException
     */
    public function deleteComment($id): bool
    {
        $comment = $this->entity->find('Comment', $id);

        if ($comment) {
            $this->entity->remove($comment);
            $this->entity->flush();
            return true;
        } else {
            return false;
        }
    }
}