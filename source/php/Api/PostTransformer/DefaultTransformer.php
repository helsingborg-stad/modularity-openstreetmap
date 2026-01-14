<?php

namespace ModularityOpenStreetMap\Api\PostTransformer;

use ModularityOpenStreetMap\Api\SettingsInterface;
use ModularityOpenStreetMap\ApplyOpenStreetMapDataToPostObject;

class DefaultTransformer implements PostTransformerInterface {
    public function __construct(private SettingsInterface $settings)
    {}

    public function transform($post): mixed
    {
        $post = \Municipio\Helper\Post::preparePostObject($post);
        $post = (new ApplyOpenStreetMapDataToPostObject($post))->apply();
        $post->osmFilterValues = $this->addOsmFilteringCapabilities($post);

        $fields = get_fields($post->getId() ?? 0) ?? [];
        $post->placeInfo = $this->createPlaceInfoList($fields);

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
                $attributeString .= ' osm-filter-item-' . $taxonomy . '=' . implode(',', $term);
            }
        }

        return $attributeString;
    }

     /**
     * Create a list of place information based on specified fields.
     *
     * @param array $fields An array of fields containing information about the place.
     *
     * @return array The list of place information.
     */
    private function createPlaceInfoList($fields)
    {
        $list = [];
        // Phone number
        if (!empty($fields['phone'])) {
            $list['phone'] = \Municipio\Helper\Listing::createListingItem(
                $fields['phone'],
                '',
                ['src' => 'call']
            );
        }

        // Website link (with fixed label)
        if (!empty($fields['website'])) {
            $list['website'] = \Municipio\Helper\Listing::createListingItem(
                __('Visit website', 'municipio'),
                $fields['website'],
                ['src' => 'language'],
            );
        }

        // Apply filters to listing items
        $list = apply_filters(
            'Municipio/Controller/SingularPlace/listing',
            $list,
            $fields
        );

        return $list;
    }
}