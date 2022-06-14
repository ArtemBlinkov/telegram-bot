<?php

class CommentRecord
{

    /**
     * @var string[]
     */
    protected $status = [
        'NEW' => 'NOT_ANSWERED',
        'PROCESS' => 'WAIT'
    ];

    /**
     * @var Comment
     */
    protected $doctrine;
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $entity;

    /**
     * CommentRecord constructor.
     * @throws \Doctrine\DBAL\Exception
     * @throws \Doctrine\ORM\Exception\ORMException
     */
    public function __construct()
    {
        // помещаем модели БД в класс
        $this->entity = Entity::getEntityManager();
        $this->doctrine = new Comment();
    }

    /**
     * @param $id
     * @return false|object
     * @throws \Doctrine\ORM\Exception\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     */
    public function getComment($id)
    {
        // получаем запись по id
        $comment = $this->entity->find('Comment', $id);

        // возвращаем запись или возвращаем false
        if ($comment) {
            return $comment;
        }
        else {
            return false;
        }
    }

    /**
     * @param $data
     * @return int
     * @throws \Doctrine\ORM\Exception\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function createComment($data) : int
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
     * @throws \Doctrine\ORM\Exception\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     */
    public function updateComment($id, $status = 'PROCESS') : bool
    {
        // получаем запись по id
        $comment = $this->entity->find('Comment', $id);

        // меняем статус или возвращаем false
        if ($comment) {
            $comment->setStatus($this->status[$status]);
        } else {
            return false;
        }

        // исполняем запрос
        $this->entity->flush();

        // возвращаем ответ
        return true;
    }

    /**
     * @param $id
     * @return bool
     * @throws \Doctrine\ORM\Exception\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     */
    public function deleteComment($id) : bool
    {
        // получаем запись по id
        $comment = $this->entity->find('Comment', $id);

        if ($comment) {
            // удаляем запись
            $this->entity->remove($comment);

            // исполняем запрос
            $this->entity->flush();

            // возвращаем ответ
            return true;
        }
        else {
            return false;
        }
    }
}