<?php
declare(strict_types=1);

namespace App\Api\BooksLibrary\ResourceDto;

use ApiPlatform\Metadata\ApiProperty;
use DateTimeInterface;

class OutputBookDto
{
    public function __construct(
        #[ApiProperty(example: '123456')]
        public int $serialNumber,
        #[ApiProperty(example: 'Lord of the rings')]
        public string $name,
        #[ApiProperty(example: 'J.R.R Tolkien')]
        public string $author,
        #[ApiProperty(example: 'true|false')]
        public bool $isBorrowed,
        #[ApiProperty(example: '2026-07-05 10:11:11|null')]
        public ?DateTimeInterface $borrowedAt,
        #[ApiProperty]
        public ?OutputLibraryMemberDto $borrower,
    ) {}
}
