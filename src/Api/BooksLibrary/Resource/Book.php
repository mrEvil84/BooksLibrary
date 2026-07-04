<?php
declare(strict_types=1);

namespace App\Api\BooksLibrary\Resource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Api\BooksLibrary\ResourceDto\OutputBookDto;
use App\Api\BooksLibrary\State\AddBookProcessor;
use App\Api\BooksLibrary\State\BookCollectionProvider;
use App\Api\BooksLibrary\State\DeleteBookProcessor;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    operations: [
        new Post(
            uriTemplate: '/book/add',
            security: null,
            processor: AddBookProcessor::class,
        ),
        new Delete(
            uriTemplate: '/book/{serialNumber}',
            uriVariables: ['serialNumber'],
            security: null,
            processor: DeleteBookProcessor::class,
        ),
        new GetCollection(
            uriTemplate: '/books',
            output: OutputBookDto::class,
            paginationEnabled: false,
            security: null,
            provider: BookCollectionProvider::class,
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
