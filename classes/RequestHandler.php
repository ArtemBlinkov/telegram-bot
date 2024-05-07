<?php

use TelegramBot\Api\BotApi;
use TelegramBot\Api\InvalidJsonException;

class RequestHandler
{
    /**
     * @var array|object
     */
    protected array $post = [];

    /**
     * Init api handler
     * @throws InvalidJsonException
     */
    public function __construct()
    {
        $this::setHeaders();
        $this->post = BotApi::jsonValidate(file_get_contents('php://input'), true);
        $this->validateRequest();
    }

    /**
     * Set headers
     */
    public static function setHeaders()
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Methods: POST");
        header("Access-Control-Max-Age: 3600");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    }

    /**
     * Validate post request
     */
    protected function validateRequest()
    {
        if (!$this->post) {
            $this->badRequest('Empty data!', 400);
        }

        if ($this->post['pass'] != PASS) {
            $this->badRequest('Authorization failed!', 403);
        }
    }

    /**
     * Return bad request
     * @param $message
     * @param $code
     */
    public function badRequest($message, $code)
    {
        http_response_code($code);
        echo json_encode(["message" => $message]);
        die();
    }

    /**
     * Return response
     * @param $data
     */
    public function response($data)
    {
        http_response_code(200);
        echo json_encode($data);
    }

    /**
     * Get post data
     * @return array|object
     */
    public function getData()
    {
        return $this->post;
    }
}