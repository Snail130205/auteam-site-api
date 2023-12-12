<?php

namespace App\Dto;

class NewsDto
{
    public string $text;

    public string $shortText;

    public ?string $imageUrl = null;

    /** @var NewsLinkDto[]  */
    public ?array $links = [];

    /** @var FileUrlDto[]  */
    public array $fileUrls = [];
}
