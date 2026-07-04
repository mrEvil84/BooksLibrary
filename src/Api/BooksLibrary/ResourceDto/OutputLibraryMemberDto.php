<?php
declare(strict_types=1);

namespace App\Api\BooksLibrary\ResourceDto;

use ApiPlatform\Metadata\ApiProperty;

class OutputLibraryMemberDto
{
    public function __construct(
        #[ApiProperty(example: '123456')]
        public int $identificationNumber,
        #[ApiProperty(example: 'Jan')]
        public string $name,
        #[ApiProperty(example: 'Kowalski')]
        public string $surname,
    ) {}
}
