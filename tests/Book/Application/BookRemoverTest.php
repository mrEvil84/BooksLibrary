<?php
declare(strict_types=1);

namespace App\Tests\Book\Application;

use App\BookLibrary\Application\BookRemover;
use App\BookLibrary\Application\Exception\BookIsBorrowedException;
use App\BookLibrary\Application\Exception\BookNotFoundException;
use App\BookLibrary\Application\Exception\InvalidBookSerialNumberException;
use App\BookLibrary\Domain\Entity\Book;
use App\BookLibrary\Domain\Entity\LibraryMember;
use App\BookLibrary\Domain\Repository\BookRepositoryInterface;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class BookRemoverTest extends TestCase
{
    private BookRepositoryInterface&MockObject $bookRepository;
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
        $this->expectExceptionMessageIs('BookLibrary serial number ' . $serialNumber . ' does not exist');

        $this->bookRemover->remove($serialNumber);
    }

    #[Test]
    public function itThrowsWhenBookIsBorrowed(): void
    {
        $serialNumber = 123456;
        $book = Book::create($serialNumber, 'Lord of the rings', 'J.R.R Tolkien');
        $book->setBorrowedBy(LibraryMember::create(654321, 'Jan', 'Kowalski'));

        $this->bookRepository
            ->expects(self::once())
            ->method('findBySerialNumber')
            ->with($serialNumber)
            ->willReturn($book);

        $this->bookRepository
            ->expects(self::never())
            ->method('remove');

        $this->expectException(BookIsBorrowedException::class);
        $this->expectExceptionMessageIs('BookLibrary serial number ' . $serialNumber . ' is currently borrowed and cannot be removed');

        $this->bookRemover->remove($serialNumber);
    }

    #[Test]
    public function itThrowsWhenSerialNumberIsTooLow(): void
    {
        $serialNumber = 99999;

        $this->bookRepository->expects(self::never())->method('findBySerialNumber');
        $this->bookRepository->expects(self::never())->method('remove');

        $this->expectException(InvalidBookSerialNumberException::class);
        $this->expectExceptionMessageIs('Serial number ' . $serialNumber . ' is not valid');

        $this->bookRemover->remove($serialNumber);
    }

    #[Test]
    public function itThrowsWhenSerialNumberIsTooHigh(): void
    {
        $serialNumber = 1000000;

        $this->bookRepository->expects(self::never())->method('findBySerialNumber');
        $this->bookRepository->expects(self::never())->method('remove');

        $this->expectException(InvalidBookSerialNumberException::class);
        $this->expectExceptionMessageIs('Serial number ' . $serialNumber . ' is not valid');

        $this->bookRemover->remove($serialNumber);
    }
}
