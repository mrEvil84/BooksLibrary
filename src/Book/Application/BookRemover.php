<?php
declare(strict_types=1);

namespace App\Book\Application;

use App\Book\Application\Exception\BookNotFoundException;
use App\Book\Domain\Entity\Book;
use App\Book\Domain\Repository\BookRepositoryInterface;

readonly class BookRemover
{
    public function __construct(
        private BookRepositoryInterface $bookRepository,
    ) {
    }

    /**
     * @throws BookNotFoundException
     */
    public function remove(int $serialNumber): void
    {
        $book = $this->bookRepository->findBySerialNumber($serialNumber);

        if (!$book instanceof Book) {
            throw new BookNotFoundException('Book serial number ' . $serialNumber . ' does not exist');
        }

        $this->bookRepository->remove($book);
    }
}
