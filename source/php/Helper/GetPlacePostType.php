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
        $postTypes = get_post_types();
        if (empty($postTypes)) {
            return [];
        }

        $filteredPostTypes = [];
        foreach ($postTypes as $postType) {
            $contentType = \Municipio\Helper\ContentType::getContentType($postType);
            if (is_object($contentType) && $contentType->getKey() == 'place') {
                $postTypeObject = get_post_type_object($postType);
                $filteredPostTypes[$postTypeObject->name] = $postTypeObject->label;
            }
        }

        $this->placePostTypes = $filteredPostTypes;
        return $filteredPostTypes;
    }

}
