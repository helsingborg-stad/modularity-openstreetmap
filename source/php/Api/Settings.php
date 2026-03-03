<?php

declare(strict_types=1);


namespace ModularityOpenStreetMap\Api;

use ModularityOpenStreetMap\Api\SettingsInterface;

class Settings implements SettingsInterface
{
    private null|string $postType = null;
    private int $page = 1;
    private int $postsPerPage = 20;
    private null|array $taxonomies = [];
    private bool $html = false;
    private string $orderBy = 'date';
    private string $order = 'DESC';
    private bool $randomize = false;

    public function __construct(array $settings)
    {
        $this->postType = isset($settings['postType']) ? $settings['postType'] : $this->postType;
        $this->page = isset($settings['page']) ? intval($settings['page']) : $this->page;
        $this->postsPerPage = isset($settings['postsPerPage']) ? intval($settings['postsPerPage']) : $this->postsPerPage;
        $this->taxonomies = isset($settings['taxonomies']) ? $settings['taxonomies'] : $this->taxonomies;
        $this->html = isset($settings['html']) ? true : $this->html;
        $this->order = isset($settings['order']) ? $settings['order'] : $this->order;
        $this->orderBy = isset($settings['orderBy']) ? $settings['orderBy'] : $this->orderBy;
        $this->randomize = isset($settings['randomize']) ? true : false;
    }

    public function getPostType(): null|string
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

    public function getOrder(): string
    {
        return $this->order;
    }

    public function getOrderBy(): string
    {
        return $this->orderBy;
    }

    public function getRandomize(): bool
    {
        return $this->randomize;
    }
}
