<?php

namespace ModularityOpenStreetMap\Api;

class OsmGetPosts {
    public function __construct(private array $args)
    {}

    public function getPosts()
    {
        $query = new \WP_Query($this->args);

        return $query->posts;
    }
}