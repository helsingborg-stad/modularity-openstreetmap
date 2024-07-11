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
        $post->osmFilterValues = $this->addOsmFilteringCapabilities($post);

        return $post;
    }

    private function addOsmFilteringCapabilities($post): string
    {
        $filterTermsStructure = [];
        $attributeString = "";
        
        if (!empty($post->termsUnlinked)) {
            foreach ($post->termsUnlinked as $term) {
                $filterTermsStructure[$term['taxonomy']][] = $term['slug'];
            }

            foreach ($filterTermsStructure as $taxonomy => $term) {
                $attributeString .= ' data-js-osm-' . $taxonomy . '="' . implode(',', $term) . '"';
            }
        }

        return $attributeString;
    }
}