<?php

namespace ModularityOpenStreetMap\Api\PostTransformer;

use ModularityOpenStreetMap\Api\SettingsInterface;

class DefaultTransformer implements PostTransformerInterface {
    public function __construct(private SettingsInterface $settings)
    {}

    public function transform($post): mixed
    {
        $post = \Municipio\Helper\ContentType::complementPlacePost($post);
        $post = \Municipio\Helper\Post::preparePostObject($post);

        return $post;
    }
}