<?php

namespace Models;

class Comment
{
    /**
     * Идентификатор комментария в системе domain
     */

    private $comment_id;

    /**
     * Домен сервера,от которого пришёл комментарий
     */

    private $domain;

    /**
     * Статус комментария
     */

    private $status;

    /**
     * Получение домена сервера
     */

    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * Установка домена сервера
     */

    public function setDomain($domain)
    {
        $this->domain = $domain;
    }

    /**
     * Получение идентификатора комментария в системе domain
     */

    public function getCommentId()
    {
        return $this->comment_id;
    }

    /**
     * Установка идентификатора комментария в системе domain
     */

    public function setCommentId($id)
    {
        $this->comment_id = $id;
    }

    /**
     * Получение статуса комментария
     */

    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Установка статуса комментария
     */

    public function setStatus($status)
    {
        $this->status = $status;
    }
}