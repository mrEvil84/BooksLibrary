<?php
declare(strict_types=1);

namespace App\Api\BooksLibrary\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Api\BooksLibrary\ResourceDto\OutputBookDto;
use App\Book\Application\BookLister;
use App\Book\Domain\Entity\Book;

/**
 * @implements ProviderInterface<OutputBookDto>
 */
final readonly class BookCollectionProvider implements ProviderInterface
{
    public function __construct(
        private BookLister $bookLister,
    ) {
    }

    /**
     * @return OutputBookDto[]
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): array
    {
        return array_map(
            static fn (Book $book): OutputBookDto => new OutputBookDto($book->getSerialNumber(), $book->getTitle(), $book->getAuthor()),
            $this->bookLister->list(),
        );
    }
}
