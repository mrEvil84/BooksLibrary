<?php
declare(strict_types=1);

namespace App\BookLibrary\Application;

use App\BookLibrary\Application\Exception\BookNotFoundException;
use App\BookLibrary\Application\Exception\InvalidBookSerialNumberException;
use App\BookLibrary\Domain\Entity\Book;
use App\BookLibrary\Domain\Repository\BookRepositoryInterface;

readonly class BookAvailabilitySetter
{
    public function __construct(
        private BookRepositoryInterface $bookRepository,
    ) {
    }

    /**
     * @throws InvalidBookSerialNumberException
     * @throws BookNotFoundException
     */
    public function setAvailable(int $bookSerialNumber): void
    {
        $this->assertBookSerialNumber($bookSerialNumber);

        $book = $this->assertBookExist($bookSerialNumber);

        if ($book->isBorrowed()) {
            $book->setBookAvailable();
            $this->bookRepository->save($book);
        }
    }

    /**
     * @throws InvalidBookSerialNumberException
     */
    private function assertBookSerialNumber(int $bookSerialNumber): void
    {
        if ($bookSerialNumber < 100000 || $bookSerialNumber > 999999) {
            throw new InvalidBookSerialNumberException('Serial number ' . $bookSerialNumber . ' is not valid');
        }
    }

    /**
     * @throws BookNotFoundException
     */
    private function assertBookExist(int $bookSerialNumber): Book
    {
        $book = $this->bookRepository->findBySerialNumber($bookSerialNumber);

        if (!$book instanceof Book) {
            throw new BookNotFoundException('BookLibrary serial number ' . $bookSerialNumber . ' does not exist');
        }

        return $book;
    }
}
