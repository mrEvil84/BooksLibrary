<?php
declare(strict_types=1);

namespace App\BookLibrary\Application;

use App\BookLibrary\Application\Exception\BookIsBorrowedException;
use App\BookLibrary\Application\Exception\BookNotFoundException;
use App\BookLibrary\Application\Exception\InvalidBookSerialNumberException;
use App\BookLibrary\Domain\Entity\Book;
use App\BookLibrary\Domain\Repository\BookRepositoryInterface;

readonly class BookRemover
{
    public function __construct(
        private BookRepositoryInterface $bookRepository,
    ) {
    }

    /**
     * @throws InvalidBookSerialNumberException
     * @throws BookNotFoundException
     * @throws BookIsBorrowedException
     */
    public function remove(int $serialNumber): void
    {
        $this->assertBookSerialNumber($serialNumber);

        $book = $this->assertBookExist($serialNumber);

        $this->assertBookIsNotBorrowed($book);

        $this->bookRepository->remove($book);
    }

    /**
     * @throws InvalidBookSerialNumberException
     */
    private function assertBookSerialNumber(int $serialNumber): void
    {
        if ($serialNumber < 100000 || $serialNumber > 999999) {
            throw new InvalidBookSerialNumberException('Serial number ' . $serialNumber . ' is not valid');
        }
    }

    /**
     * @throws BookNotFoundException
     */
    private function assertBookExist(int $serialNumber): Book
    {
        $book = $this->bookRepository->findBySerialNumber($serialNumber);

        if (!$book instanceof Book) {
            throw new BookNotFoundException('BookLibrary serial number ' . $serialNumber . ' does not exist');
        }

        return $book;
    }

    /**
     * @throws BookIsBorrowedException
     */
    private function assertBookIsNotBorrowed(Book $book): void
    {
        if ($book->isBorrowed()) {
            throw new BookIsBorrowedException('BookLibrary serial number ' . $book->getSerialNumber() . ' is currently borrowed and cannot be removed');
        }
    }
}
