<?php
declare(strict_types=1);

namespace App\Api\BooksLibrary\Resource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Api\BooksLibrary\ResourceDto\BorrowBookDto;
use App\Api\BooksLibrary\ResourceDto\InputBookDto;
use App\Api\BooksLibrary\ResourceDto\OutputBookDto;
use App\Api\BooksLibrary\ResourceDto\SetAvailableBookDto;
use App\Api\BooksLibrary\State\AddBookProcessor;
use App\Api\BooksLibrary\State\BookCollectionProvider;
use App\Api\BooksLibrary\State\BorrowBookProcessor;
use App\Api\BooksLibrary\State\DeleteBookProcessor;
use App\Api\BooksLibrary\State\SetAvailableBookProcessor;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    operations: [
        new Post(
            uriTemplate: '/book/add',
            security: null,
            input: InputBookDto::class,
            processor: AddBookProcessor::class,
        ),
        new Delete(
            uriTemplate: '/book/{serialNumber}',
            uriVariables: ['serialNumber'],
            security: null,
            read: false,
            processor: DeleteBookProcessor::class,
        ),
        new GetCollection(
            uriTemplate: '/books',
            paginationEnabled: false,
            security: null,
            output: OutputBookDto::class,
            provider: BookCollectionProvider::class,
        ),
        new Patch(
            uriTemplate: '/book/borrow',
            security: null,
            input: BorrowBookDto::class,
            output: false,
            read: false,
            processor: BorrowBookProcessor::class,
        ),
        new Patch(
            uriTemplate: '/book/setAvailable',
            security: null,
            input: SetAvailableBookDto::class,
            output: false,
            read: false,
            processor: SetAvailableBookProcessor::class,
        ),
    ],
)]
class Book
{
    public ?int $id = null;

    #[Assert\NotBlank]
    public ?int $serialNumber = null;

    #[Assert\NotBlank]
    public ?string $title = null;

    #[Assert\NotBlank]
    public ?string $author = null;
}
