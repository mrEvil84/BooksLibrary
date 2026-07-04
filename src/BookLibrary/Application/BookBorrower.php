<?php
declare(strict_types=1);

namespace App\BookLibrary\Application;

use App\BookLibrary\Application\Exception\BookNotFoundException;
use App\BookLibrary\Application\Exception\InvalidBookSerialNumberException;
use App\BookLibrary\Application\Exception\InvalidLibraryMemberNumberException;
use App\BookLibrary\Application\Exception\LibraryMemberNotFoundException;
use App\BookLibrary\Domain\Entity\Book;
use App\BookLibrary\Domain\Entity\LibraryMember;
use App\BookLibrary\Domain\Repository\BookRepositoryInterface;
use App\BookLibrary\Domain\Repository\LibraryMemberRepositoryInterface;
use DateTime;

readonly class BookBorrower
{
    public function __construct(
        private BookRepositoryInterface $bookRepository,
        private LibraryMemberRepositoryInterface $libraryMemberRepository,
    ) {
    }

    /**
     * @throws InvalidBookSerialNumberException
     * @throws InvalidLibraryMemberNumberException
     * @throws BookNotFoundException
     * @throws LibraryMemberNotFoundException
     */
    public function borrow(int $bookSerialNumber, int $libraryMemberNumber): Book
    {
        $this->assertBookSerialNumber($bookSerialNumber);
        $this->assertLibraryMemberNumber($libraryMemberNumber);

        $book = $this->assertBookExists($bookSerialNumber);
        $libraryMember = $this->assertLibraryMemberExists($libraryMemberNumber);

        $book->setBorrowedBy($libraryMember);
        $book->setDateOfBorrowing(new DateTime());

        $this->bookRepository->save($book);

        return $book;
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

    /**
     * @throws InvalidLibraryMemberNumberException
     */
    private function assertLibraryMemberNumber(int $libraryMemberNumber): void
    {
        if ($libraryMemberNumber < 100000 || $libraryMemberNumber > 999999) {
            throw new InvalidLibraryMemberNumberException('Library member number ' . $libraryMemberNumber . ' is not valid');
        }
    }

    /**
     * @throws BookNotFoundException
     */
    private function assertBookExists(int $serialNumber): Book
    {
        $book = $this->bookRepository->findBySerialNumber($serialNumber);

        if (!$book instanceof Book) {
            throw new BookNotFoundException('BookLibrary serial number ' . $serialNumber . ' does not exist');
        }

        return $book;
    }

    /**
     * @throws LibraryMemberNotFoundException
     */
    private function assertLibraryMemberExists(int $libraryMemberNumber): LibraryMember
    {
        $libraryMember = $this->libraryMemberRepository->findByIdentificationNumber($libraryMemberNumber);

        if (!$libraryMember instanceof LibraryMember) {
            throw new LibraryMemberNotFoundException('Library member number ' . $libraryMemberNumber . ' does not exist');
        }

        return $libraryMember;
    }
}
