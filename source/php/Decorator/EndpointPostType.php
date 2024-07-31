<?php

namespace ModularityOpenStreetMap\Decorator;

class EndpointPostType implements EndpointDecoratorInterface
{
    public function __construct()
    {}

    public function decorate(string $endpoint, array $fields): string 
    {
        $postTypeToShow = $fields['mod_osm_post_type'] ?? '';

        return $endpoint . $postTypeToShow . '?';
    }
}