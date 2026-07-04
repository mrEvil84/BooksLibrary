<?php
declare(strict_types=1);

namespace App\Tests\Book\Application;

use App\BookLibrary\Application\BookBorrower;
use App\BookLibrary\Application\Exception\BookNotFoundException;
use App\BookLibrary\Application\Exception\InvalidBookSerialNumberException;
use App\BookLibrary\Application\Exception\InvalidLibraryMemberNumberException;
use App\BookLibrary\Application\Exception\LibraryMemberNotFoundException;
use App\BookLibrary\Domain\Entity\Book;
use App\BookLibrary\Domain\Entity\LibraryMember;
use App\BookLibrary\Domain\Repository\BookRepositoryInterface;
use App\BookLibrary\Domain\Repository\LibraryMemberRepositoryInterface;
use DateTime;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class BookBorrowerTest extends TestCase
{
    private BookRepositoryInterface&\PHPUnit\Framework\MockObject\MockObject $bookRepository;
    private LibraryMemberRepositoryInterface&\PHPUnit\Framework\MockObject\MockObject $libraryMemberRepository;
    private BookBorrower $bookBorrower;

    protected function setUp(): void
    {
        $this->bookRepository = $this->createMock(BookRepositoryInterface::class);
        $this->libraryMemberRepository = $this->createMock(LibraryMemberRepositoryInterface::class);
        $this->bookBorrower = new BookBorrower($this->bookRepository, $this->libraryMemberRepository);
    }

    #[Test]
    public function itBorrowsAnExistingBookForAnExistingLibraryMember(): void
    {
        $bookSerialNumber = 123456;
        $libraryMemberNumber = 654321;

        $book = Book::create($bookSerialNumber, 'Lord of the rings', 'J.R.R Tolkien');
        $libraryMember = LibraryMember::create($libraryMemberNumber, 'Jan', 'Kowalski');

        $this->bookRepository
            ->expects(self::once())
            ->method('findBySerialNumber')
            ->with($bookSerialNumber)
            ->willReturn($book);

        $this->libraryMemberRepository
            ->expects(self::once())
            ->method('findByIdentificationNumber')
            ->with($libraryMemberNumber)
            ->willReturn($libraryMember);

        $this->bookRepository
            ->expects(self::once())
            ->method('save')
            ->with($book);

        $result = $this->bookBorrower->borrow($bookSerialNumber, $libraryMemberNumber);

        self::assertSame($book, $result);
        self::assertSame($libraryMember, $result->getBorrowedBy());
        self::assertInstanceOf(DateTime::class, $result->getDateOfBorrowing());
    }

    #[Test]
    public function itThrowsWhenBookSerialNumberIsTooLow(): void
    {
        $bookSerialNumber = 99999;
        $libraryMemberNumber = 654321;

        $this->bookRepository->expects(self::never())->method('findBySerialNumber');
        $this->libraryMemberRepository->expects(self::never())->method('findByIdentificationNumber');

        $this->expectException(InvalidBookSerialNumberException::class);
        $this->expectExceptionMessageIs('Serial number ' . $bookSerialNumber . ' is not valid');

        $this->bookBorrower->borrow($bookSerialNumber, $libraryMemberNumber);
    }

    #[Test]
    public function itThrowsWhenBookSerialNumberIsTooHigh(): void
    {
        $bookSerialNumber = 1000000;
        $libraryMemberNumber = 654321;

        $this->bookRepository->expects(self::never())->method('findBySerialNumber');
        $this->libraryMemberRepository->expects(self::never())->method('findByIdentificationNumber');

        $this->expectException(InvalidBookSerialNumberException::class);
        $this->expectExceptionMessageIs('Serial number ' . $bookSerialNumber . ' is not valid');

        $this->bookBorrower->borrow($bookSerialNumber, $libraryMemberNumber);
    }

    #[Test]
    public function itThrowsWhenLibraryMemberNumberIsInvalid(): void
    {
        $bookSerialNumber = 123456;
        $libraryMemberNumber = 99999;

        $this->bookRepository->expects(self::never())->method('findBySerialNumber');
        $this->libraryMemberRepository->expects(self::never())->method('findByIdentificationNumber');

        $this->expectException(InvalidLibraryMemberNumberException::class);
        $this->expectExceptionMessageIs('Library member number ' . $libraryMemberNumber . ' is not valid');

        $this->bookBorrower->borrow($bookSerialNumber, $libraryMemberNumber);
    }

    #[Test]
    public function itThrowsWhenBookDoesNotExist(): void
    {
        $bookSerialNumber = 123456;
        $libraryMemberNumber = 654321;

        $this->bookRepository
            ->expects(self::once())
            ->method('findBySerialNumber')
            ->with($bookSerialNumber)
            ->willReturn(null);

        $this->libraryMemberRepository->expects(self::never())->method('findByIdentificationNumber');
        $this->bookRepository->expects(self::never())->method('save');

        $this->expectException(BookNotFoundException::class);
        $this->expectExceptionMessageIs('BookLibrary serial number ' . $bookSerialNumber . ' does not exist');

        $this->bookBorrower->borrow($bookSerialNumber, $libraryMemberNumber);
    }

    #[Test]
    public function itThrowsWhenLibraryMemberDoesNotExist(): void
    {
        $bookSerialNumber = 123456;
        $libraryMemberNumber = 654321;

        $book = Book::create($bookSerialNumber, 'Lord of the rings', 'J.R.R Tolkien');

        $this->bookRepository
            ->expects(self::once())
            ->method('findBySerialNumber')
            ->with($bookSerialNumber)
            ->willReturn($book);

        $this->libraryMemberRepository
            ->expects(self::once())
            ->method('findByIdentificationNumber')
            ->with($libraryMemberNumber)
            ->willReturn(null);

        $this->bookRepository->expects(self::never())->method('save');

        $this->expectException(LibraryMemberNotFoundException::class);
        $this->expectExceptionMessageIs('Library member number ' . $libraryMemberNumber . ' does not exist');

        $this->bookBorrower->borrow($bookSerialNumber, $libraryMemberNumber);
    }
}
