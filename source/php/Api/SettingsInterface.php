<?php

declare(strict_types=1);


namespace ModularityOpenStreetMap\Api;

interface SettingsInterface
{
    public function getPostType(): null|string;

    public function getPage(): int;

    public function getPostsPerPage(): int;

    public function getTaxonomies(): array;

    public function getHtml(): bool;

    public function getOrder(): string;

    public function getOrderBy(): string;

    public function getRandomize(): bool;
}
