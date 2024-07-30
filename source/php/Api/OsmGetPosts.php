<?php

namespace ModularityOpenStreetMap\Api;

class OsmGetPosts {
    public function __construct(private array $args, private SettingsInterface $settings)
    {}

    public function getPosts()
    {
        $query = new \WP_Query($this->args);
        $posts = $query->posts ?? [];

        if (!empty($posts) && $this->settings->getRandomize()) {
            shuffle($posts);
        }
        
        return $posts;
    }
}