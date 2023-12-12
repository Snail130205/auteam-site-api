<?php

namespace App\Dto;

class FileUrlDto
{
    public function __construct(string $url, string $title)
    {
        $this->title = $title;
        $this->url = $url;
    }

    public string $title;

    public string $url;
}
