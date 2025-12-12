<?php

declare(strict_types=1);


namespace ModularityOpenStreetMap\Api;

use ModularityOpenStreetMap\Api\PostTransformer\PostTransformerInterface;
use ModularityOpenStreetMap\Api\SettingsInterface;

class OsmTransformationHandler
{
    private string $cacheKey;

    public function __construct(
        private array $posts = [],
        private SettingsInterface $settings,
        private PostTransformerInterface $default,
        private PostTransformerInterface $html,
    ) {
        $this->cacheKey = $settings->getHtml() ? 'osm_html' : 'osm_post';
    }

    public function getTransformedPosts(): array
    {
        if (empty($this->posts)) {
            return [];
        }

        $transformedPosts = [];
        foreach ($this->posts as $post) {
            $id = $post->ID;
            wp_cache_flush();
            $cachedPost = wp_cache_get($id, $this->cacheKey);

            if (!$cachedPost) {
                $post = $this->default->transform($post);
                $post = $this->html->transform($post);

                wp_cache_set($id, $post, $this->cacheKey, 7 * \DAY_IN_SECONDS);
                $cachedPost = $post;
            }

            $transformedPosts[] = $cachedPost;
        }

        return $transformedPosts;
    }
}
