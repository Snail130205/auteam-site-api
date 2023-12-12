<?php

namespace App\Http\Controllers;

use App\Services\News\NewsService;

class NewsController extends Controller
{
    public function __construct(
        private readonly NewsService $newsService
    ){}

    public function getNews()
    {
        return $this->newsService->getNews();
    }

}
