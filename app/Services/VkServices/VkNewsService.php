<?php

namespace App\Services\VkServices;

use App\Dto\FileUrlDto;
use App\Dto\NewsDto;
use App\Dto\NewsLinkDto;
use App\Services\Util\Constants;
use VK\Client\VKApiClient;
use VK\Exceptions\Api\VKApiBlockedException;
use VK\Exceptions\VKApiException;
use VK\Exceptions\VKClientException;

class VkNewsService
{
    private VKApiClient $vk;

    public function __construct()
    {
        $this->vk = new VKApiClient();
    }

    /**
     * Получение новостей ВК
     * @throws VKApiBlockedException
     * @throws VKApiException
     * @throws VKClientException
     */
    public function getNews(): array
    {
        $news = $this->vk->wall()->get(config('vk.token'), ['owner_id' => Constants::AU_TEAM_VK_GROUP_ID, 'count' => 10]);
        $posts = $news['items'];
        $newsList = [];
        foreach ($posts as $post) {
            $newsDto = new NewsDto();
            $newsDto->text = !empty($post['text']) ? $post['text'] : null;
            $newsDto->id = $post['hash'];
            $newsDto->shortText = implode(' ', array_slice(explode(' ', $post['text']),0, 30));
            $attachments = $post['attachments'];

            if (isset($post['copy_history']) && empty($post['attachments'])) {
                $newsDto->text = $newsDto->text ?? end($post['copy_history'])['text'] ?? null;
                $newsDto->shortText = isset($newsDto->text) ? implode(' ', array_slice(explode(' ', $post['text']),0, 30)) : null;
                $attachments = end($post['copy_history'])['attachments'];
            }

            foreach ($attachments as $attachment) {
                $this->setPostFiles($attachment, $newsDto);
            }
            $newsList[] = $newsDto;
        }

        return $newsList;
    }

    /**
     * @param array $post
     * @param NewsDto $newsDto
     * @return void
     */
    private function setPostFiles(array $attachment, NewsDto &$newsDto): void
    {
        switch ($attachment['type']) {
            case 'doc':
                $newsDto->fileUrls[] = new FileUrlDto($attachment['doc']['url'], $attachment['doc']['title']);
                break;
            case 'link':
                $newsDto->links[] = new NewsLinkDto(
                    $attachment['link']['url'],
                    $attachment['link']['photo'] ?? [],
                    $attachment['link']['title']
                );
                break;
            case 'photo':
                $newsDto->imageUrl = end($attachment['photo']['sizes'])['url'];
                break;
        }
    }
}
