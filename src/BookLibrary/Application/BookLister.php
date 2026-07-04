<?php
declare(strict_types=1);

namespace App\BookLibrary\Application;

use App\BookLibrary\Domain\Entity\Book;
use App\BookLibrary\Domain\Repository\BookRepositoryInterface;

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
