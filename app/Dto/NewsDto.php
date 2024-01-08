<?php

namespace App\Dto;

class NewsDto
{
    public string $id;

    public ?string $text = null;

    public ?string $shortText = null;

    public ?string $imageUrl = null;

    /** @var NewsLinkDto[]  */
    public ?array $links = [];

    /** @var FileUrlDto[]  */
    public array $fileUrls = [];
}
