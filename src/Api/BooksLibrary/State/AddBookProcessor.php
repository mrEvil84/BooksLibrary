<?php
declare(strict_types = 1);

namespace App\Api\BooksLibrary\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Api\BooksLibrary\Resource\Book;
use App\Api\BooksLibrary\ResourceDto\InputBookDto;
use App\Api\BooksLibrary\ResourceDto\OutputBookDto;
use App\Book\Application\BookCreator;
use App\Book\Application\Exception\BookSerialNumberAlreadyExists;
use App\Book\Application\Exception\InvalidBookSerialNumberException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Exception;

/**
 * @implements ProcessorInterface<InputBookDto, OutputBookDto>
 */
final readonly class AddBookProcessor implements ProcessorInterface
{
    public function __construct(
        private BookCreator $bookCreator,
    ) {
    }

    /**
     * @param Book $data
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): OutputBookDto
    {
        if (!is_int($data->serialNumber)) {
            throw new ConflictHttpException('Serial number must be an integer');
        }

        try {
            $book = $this->bookCreator->create($data->serialNumber, $data->title, $data->author);
        } catch (BookSerialNumberAlreadyExists|InvalidBookSerialNumberException $exception) {
            throw new BadRequestHttpException($exception->getMessage());
        } catch (Exception $exception) {
           throw new BadRequestHttpException($exception->getMessage());
        }

        return new OutputBookDto($book->getSerialNumber(), $book->getTitle(), $book->getAuthor());
    }
}
