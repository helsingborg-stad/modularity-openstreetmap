<?php

namespace ModularityOpenStreetMap\Api;

use ModularityOpenStreetMap\Api\PostTransformer\PostTransformerInterface;
use ModularityOpenStreetMap\Api\SettingsInterface;

class OsmPostsHandler
{
    private string $cacheKey;
    public function __construct(
        private array $posts = [], 
        private SettingsInterface $settings,
        private PostTransformerInterface $default,
        private PostTransformerInterface $html
    )
    {
        $this->cacheKey = $settings->getHtml() ? 'osm_html' : 'osm_post';
    }

    public function getTransformedPosts(): array
    {
        if (empty($this->posts)) {
            return [];
        }

        $transformedPosts = [];
        foreach ($this->posts as $post) {
            $cachedPost = wp_cache_get($post->ID, $this->cacheKey);
            
            if (!$cachedPost) {
                $transformedPost = $this->default->transform($post);
                $transformedPost = $this->html->transform($post);
                
                wp_cache_set($post->ID, $post, $this->cacheKey, 7 * \DAY_IN_SECONDS);
                $cachedPost = $transformedPost;
            }

            $transformedPosts[] = $cachedPost;
        }

        return $transformedPosts;
    }
}