<?php

namespace ModularityOpenStreetMap\Helper;

class GetPlacePostType {
    private array $placePostTypes = [];

    public function __construct()
    {}

    public function getPlacePostTypes() {
        $postTypes = !empty($this->placePostTypes) ? $this->placePostTypes : $this->filterPostTypes();

        return $postTypes;
    }

    private function filterPostTypes(): array 
    {
        $filteredPostTypes = [];
        $settings = get_field('post_type_schema_types', 'option');

        if( empty($settings) ) {
            return [];
        }

        foreach ($settings as $row) {
            
            if ($row['schema_type'] === 'Place') {
                $postTypeObject = get_post_type_object($row['post_type']);
                $filteredPostTypes[$postTypeObject->name] = $postTypeObject->label;
            }
        }

        $this->placePostTypes = $filteredPostTypes;
        return $filteredPostTypes;
    }

}
