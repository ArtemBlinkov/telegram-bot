<?php

require_once __DIR__ . "/doctrine/bootstrap.php";
require_once __DIR__ . "/classes/Logger.php";

global $entityManager;

try {

    /**
     * Create new comment
     */
    /*$comment = new Comment();

    $comment->setDomain('razor-cut');
    $comment->setCommentId(1);
    $comment->setStatus('NOT_ANSWERED');
    $comment->setCallback('https:/razor-cut/api/new-comment');

    $entityManager->persist($comment);
    $entityManager->flush();

    echo "Created Product with ID " . $comment->getId() . "\n";*/

    /**
     * Get all comment
     */
    /*$productRepository = $entityManager->getRepository('Comment');
    $products = $productRepository->findAll();

    foreach ($products as $product) {
        echo sprintf("-%s\n", $product->getId());
    }*/

    /**
     * Get one comment
     */
    /*$product = $entityManager->find('Comment', 1);

    if ($product === null) {
        echo "No product found.\n";
        exit(1);
    }

    echo sprintf("-%s\n", $product->getDomain());*/

    /**
     * Update comment
     */
    /*$product = $entityManager->find('Comment', 1);

    if ($product === null) {
        echo "Product 1 does not exist.\n";
        exit(1);
    }

    $product->setStatus('WAIT');

    $entityManager->flush();*/

}
catch (Exception $e)
{
    Logger::Exception($e);
}
