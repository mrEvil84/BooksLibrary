<?php
declare(strict_types=1);

namespace App\Tests\Book\Application;

use App\BookLibrary\Application\BookLister;
use App\BookLibrary\Domain\Entity\Book;
use App\BookLibrary\Domain\Repository\BookRepositoryInterface;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class BookListerTest extends TestCase
{
    private BookRepositoryInterface&\PHPUnit\Framework\MockObject\MockObject $bookRepository;
    private BookLister $bookLister;

    protected function setUp(): void
    {
        $this->bookRepository = $this->createMock(BookRepositoryInterface::class);
        $this->bookLister = new BookLister($this->bookRepository);
    }

    #[Test]
    public function itListsAllBooks(): void
    {
        $books = [Book::create(123456, 'Lord of the rings', 'J.R.R Tolkien')];

        $this->bookRepository
            ->expects(self::once())
            ->method('findAll')
            ->willReturn($books);

        self::assertSame($books, $this->bookLister->list());
    }
}
