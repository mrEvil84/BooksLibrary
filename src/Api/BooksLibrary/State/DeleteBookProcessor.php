<?php
declare(strict_types=1);

namespace App\Api\BooksLibrary\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Api\BooksLibrary\Resource\Book;
use App\Book\Application\BookRemover;
use App\Book\Application\Exception\BookNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @implements ProcessorInterface<Book, void>
 */
final readonly class DeleteBookProcessor implements ProcessorInterface
{
    public function __construct(
        private BookRemover $bookRemover,
    ) {
    }

    /**
     * @param Book $data
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): void
    {
        $serialNumber = $uriVariables['serialNumber'];

        try {
            $this->bookRemover->remove($serialNumber);
        } catch (BookNotFoundException $exception) {
            throw new NotFoundHttpException($exception->getMessage());
        }
    }
}
