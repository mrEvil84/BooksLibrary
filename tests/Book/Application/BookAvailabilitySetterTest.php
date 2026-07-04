<?php
declare(strict_types=1);

namespace App\Tests\Book\Application;

use App\BookLibrary\Application\BookAvailabilitySetter;
use App\BookLibrary\Application\Exception\BookNotFoundException;
use App\BookLibrary\Application\Exception\InvalidBookSerialNumberException;
use App\BookLibrary\Domain\Entity\Book;
use App\BookLibrary\Domain\Entity\LibraryMember;
use App\BookLibrary\Domain\Repository\BookRepositoryInterface;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class BookAvailabilitySetterTest extends TestCase
{
    private BookRepositoryInterface&\PHPUnit\Framework\MockObject\MockObject $bookRepository;
    private BookAvailabilitySetter $bookAvailabilitySetter;

    protected function setUp(): void
    {
        $this->bookRepository = $this->createMock(BookRepositoryInterface::class);
        $this->bookAvailabilitySetter = new BookAvailabilitySetter($this->bookRepository);
    }

    #[Test]
    public function itSetsAnExistingBorrowedBookAsAvailable(): void
    {
        $bookSerialNumber = 123456;

        $book = Book::create($bookSerialNumber, 'Lord of the rings', 'J.R.R Tolkien');
        $book->setBorrowedBy(LibraryMember::create(654321, 'Jan', 'Kowalski'));
        $book->setDateOfBorrowing(new \DateTime());

        $this->bookRepository
            ->expects(self::once())
            ->method('findBySerialNumber')
            ->with($bookSerialNumber)
            ->willReturn($book);

        $this->bookRepository
            ->expects(self::once())
            ->method('save')
            ->with($book);

        $this->bookAvailabilitySetter->setAvailable($bookSerialNumber);

        self::assertFalse($book->isBorrowed());
        self::assertNull($book->getBorrowedBy());
        self::assertNull($book->getDateOfBorrowing());
    }

    #[Test]
    public function itDoesNothingWhenBookIsNotBorrowed(): void
    {
        $bookSerialNumber = 123456;

        $book = Book::create($bookSerialNumber, 'Lord of the rings', 'J.R.R Tolkien');

        $this->bookRepository
            ->expects(self::once())
            ->method('findBySerialNumber')
            ->with($bookSerialNumber)
            ->willReturn($book);

        $this->bookRepository->expects(self::never())->method('save');

        $this->bookAvailabilitySetter->setAvailable($bookSerialNumber);
    }

    #[Test]
    public function itThrowsWhenBookSerialNumberIsTooLow(): void
    {
        $bookSerialNumber = 99999;

        $this->bookRepository->expects(self::never())->method('findBySerialNumber');
        $this->bookRepository->expects(self::never())->method('save');

        $this->expectException(InvalidBookSerialNumberException::class);
        $this->expectExceptionMessageIs('Serial number ' . $bookSerialNumber . ' is not valid');

        $this->bookAvailabilitySetter->setAvailable($bookSerialNumber);
    }

    #[Test]
    public function itThrowsWhenBookSerialNumberIsTooHigh(): void
    {
        $bookSerialNumber = 1000000;

        $this->bookRepository->expects(self::never())->method('findBySerialNumber');
        $this->bookRepository->expects(self::never())->method('save');

        $this->expectException(InvalidBookSerialNumberException::class);
        $this->expectExceptionMessageIs('Serial number ' . $bookSerialNumber . ' is not valid');

        $this->bookAvailabilitySetter->setAvailable($bookSerialNumber);
    }

    #[Test]
    public function itThrowsWhenBookDoesNotExist(): void
    {
        $bookSerialNumber = 123456;

        $this->bookRepository
            ->expects(self::once())
            ->method('findBySerialNumber')
            ->with($bookSerialNumber)
            ->willReturn(null);

        $this->bookRepository->expects(self::never())->method('save');

        $this->expectException(BookNotFoundException::class);
        $this->expectExceptionMessageIs('BookLibrary serial number ' . $bookSerialNumber . ' does not exist');

        $this->bookAvailabilitySetter->setAvailable($bookSerialNumber);
    }
}
