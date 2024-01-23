<?php

namespace App\Dto;

class NewsLinkDto
{
    public function __construct(string $link, ?array $images, string $text)
    {
        $this->link = $link;

        $this->text = $text;

        $this->image = !empty($images) ? end($images['sizes'])['url'] : null;
    }

    public string $link;

    public ?string $image;

    public string $text;
}
