<?php

namespace ModularityOpenStreetMap\Api\PostTransformer;

use ModularityOpenStreetMap\Api\SettingsInterface;

class HtmlTransformer implements PostTransformerInterface {
    public function __construct(private SettingsInterface $settings)
    {}

    public function transform($post): mixed
    {
        if (!$this->settings->getHtml()) {
            return $post;
        }

        return open_street_map_render_blade_view('test', ['place' => $post, 'includePost' => $this->settings->getIncludePost()]);
    }
}