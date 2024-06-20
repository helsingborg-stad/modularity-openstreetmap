<?php

namespace ModularityOpenStreetMap\Api;

interface SettingsInterface {
    public function getPostType(): ?string;
    public function getPage(): int;
    public function getPostsPerPage(): int;
    public function getTaxonomies(): array;
    public function getHtml(): bool;
    public function getIncludePost(): bool;
}