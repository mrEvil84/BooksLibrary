<?php
declare(strict_types=1);

namespace App\Tests\Book\Application;

use App\Book\Application\BookRemover;
use App\Book\Application\Exception\BookNotFoundException;
use App\Book\Domain\Entity\Book;
use App\Book\Domain\Repository\BookRepositoryInterface;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class BookRemoverTest extends TestCase
{
    private BookRepositoryInterface&\PHPUnit\Framework\MockObject\MockObject $bookRepository;
    private BookRemover $bookRemover;

    protected function setUp(): void
    {
        $this->bookRepository = $this->createMock(BookRepositoryInterface::class);
        $this->bookRemover = new BookRemover($this->bookRepository);
    }

    #[Test]
    public function itRemovesAnExistingBook(): void
    {
        $serialNumber = 123456;
        $book = Book::create($serialNumber, 'Lord of the rings', 'J.R.R Tolkien');

        $this->bookRepository
            ->expects(self::once())
            ->method('findBySerialNumber')
            ->with($serialNumber)
            ->willReturn($book);

        $this->bookRepository
            ->expects(self::once())
            ->method('remove')
            ->with($book);

        $this->bookRemover->remove($serialNumber);
    }

    #[Test]
    public function itThrowsWhenBookDoesNotExist(): void
    {
        $serialNumber = 123456;

        $this->bookRepository
            ->expects(self::once())
            ->method('findBySerialNumber')
            ->with($serialNumber)
            ->willReturn(null);

        $this->bookRepository
            ->expects(self::never())
            ->method('remove');

        $this->expectException(BookNotFoundException::class);
        $this->expectExceptionMessageIs('Book serial number ' . $serialNumber . ' does not exist');

        $this->bookRemover->remove($serialNumber);
    }
}
