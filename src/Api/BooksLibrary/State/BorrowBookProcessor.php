<?php
declare(strict_types=1);

namespace App\Api\BooksLibrary\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Api\BooksLibrary\ResourceDto\BorrowBookDto;
use App\BookLibrary\Application\BookBorrower;
use App\BookLibrary\Application\Exception\BookNotFoundException;
use App\BookLibrary\Application\Exception\InvalidBookSerialNumberException;
use App\BookLibrary\Application\Exception\InvalidLibraryMemberNumberException;
use App\BookLibrary\Application\Exception\LibraryMemberNotFoundException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @implements ProcessorInterface<BorrowBookDto, void>
 */
final readonly class BorrowBookProcessor implements ProcessorInterface
{
    public function __construct(
        private BookBorrower $bookBorrower,
    ) {
    }

    /**
     * @param BorrowBookDto $data
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): void
    {
        try {
            $this->bookBorrower->borrow($data->bookSerialNumber, $data->libraryMemberNumber);
        } catch (InvalidBookSerialNumberException|InvalidLibraryMemberNumberException $exception) {
            throw new BadRequestHttpException($exception->getMessage());
        } catch (BookNotFoundException|LibraryMemberNotFoundException $exception) {
            throw new NotFoundHttpException($exception->getMessage());
        }
    }
}
