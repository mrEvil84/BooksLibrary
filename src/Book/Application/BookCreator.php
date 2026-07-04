<?php
declare(strict_types=1);

namespace App\Book\Application;

use App\Book\Application\Exception\BookSerialNumberAlreadyExists;
use App\Book\Application\Exception\InvalidBookSerialNumberException;
use App\Book\Domain\Entity\Book;
use App\Book\Domain\Repository\BookRepositoryInterface;

readonly class BookCreator
{
    public function __construct(
        private BookRepositoryInterface $bookRepository,
    ) {
    }

    /**
     * @throws BookSerialNumberAlreadyExists
     * @throws InvalidBookSerialNumberException
     */
    public function create(int $serialNumber, string $title, string $author): Book
    {
        $this->assertBookSerialNumber($serialNumber);
        $this->assertBookSerialNumberNotExists($serialNumber);

        $book = Book::create($serialNumber, $title, $author);
        $this->bookRepository->save($book);

        return $book;
    }

    /**
     * @throws BookSerialNumberAlreadyExists
     * @throws InvalidBookSerialNumberException
     */
    private function assertBookSerialNumberNotExists(int $serialNumber): void
    {
        $book = $this->bookRepository->findBySerialNumber($serialNumber);

        if ($book instanceof Book) {
            throw new BookSerialNumberAlreadyExists('Book serial number ' . $serialNumber . ' already exists');
        }
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
}
