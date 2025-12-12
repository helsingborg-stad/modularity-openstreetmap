<?php

declare(strict_types=1);

namespace ModularityOpenStreetMap\Helper;

class GetSelectedTaxonomies
{
    public function getSelectedTaxonomies(): array
    {
        $postType = get_field('mod_osm_post_type');
        $filterRepeater = get_field('mod_osm_filters');
        if (empty($filterRepeater) || empty($postType)) {
            return [];
        }

        $taxonomies = [];
        foreach ($filterRepeater as $row) {
            if (empty($row['mod_osm_filter_taxonomy'])) {
                continue;
            }

            $taxonomies[$postType][] = $row['mod_osm_filter_taxonomy'];
        }

        return $taxonomies;
    }
}
