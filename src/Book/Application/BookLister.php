<?php
declare(strict_types=1);

namespace App\Book\Application;

use App\Book\Domain\Entity\Book;
use App\Book\Domain\Repository\BookRepositoryInterface;

readonly class BookLister
{
    public function __construct(
        private BookRepositoryInterface $bookRepository,
    ) {
    }

    /**
     * @return Book[]
     */
    public function list(): array
    {
        return $this->bookRepository->findAll();
    }
}
