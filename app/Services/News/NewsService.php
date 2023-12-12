<?php

namespace App\Services\News;

use App\Services\VkServices\VkNewsService;

class NewsService
{

    public function __construct(
        private readonly VkNewsService $vkNewsService
    ) {}

    public function getNews(): array
    {
        //TODO обсудить с Уйминым объединение новостей, брать не только из ВК
        return $this->vkNewsService->getNews();
    }
}
