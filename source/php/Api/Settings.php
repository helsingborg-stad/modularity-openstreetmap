<?php

namespace ModularityOpenStreetMap\Api;

use ModularityOpenStreetMap\Api\SettingsInterface;

class Settings implements SettingsInterface
{
    private ?string $postType = null;
    private int $page = 1;
    private int $postsPerPage = 20;
    private ?array $taxonomies = [];
    private bool $html = false;
    private bool $includePost = false;

    public function __construct(array $settings)
    {
        $this->postType = isset($settings['postType']) ? $settings['postType'] : $this->postType;
        $this->page = isset($settings['page']) ? intval($settings['page']) : $this->page;
        $this->postsPerPage = isset($settings['postsPerPage']) ? intval($settings['postsPerPage']) : $this->postsPerPage;
        $this->taxonomies = isset($settings['taxonomies']) ? $settings['taxonomies'] : $this->taxonomies;
        $this->html     = isset($settings['html']) ? true : $this->html;
        $this->includePost = isset($settings['includePost']) ? true : $this->includePost;
    }

    public function getPostType(): ?string
    {
        return $this->postType;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function getPostsPerPage(): int
    {
        return $this->postsPerPage;
    }

    public function getTaxonomies(): array
    {
        return $this->taxonomies;
    }

    public function getHtml(): bool
    {
        return $this->html;
    }

    public function getIncludePost(): bool
    {
        return $this->includePost;
    }
}