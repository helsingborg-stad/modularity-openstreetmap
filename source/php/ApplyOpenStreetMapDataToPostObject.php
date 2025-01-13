<?php

namespace ModularityOpenStreetMap;

class ApplyOpenStreetMapDataToPostObject {
    public function __construct(private \Municipio\PostObject\PostObjectInterface $post)
    {}
    public function apply():\Municipio\PostObject\PostObjectInterface|null {
        
        if (empty($this->post->schemaObject)) {
            return $this->post;
        }

        if (empty($this->post->schemaObject['geo']) || !($this->post->schemaObject['geo'] instanceof \Spatie\SchemaOrg\GeoCoordinates)) {
            return $this->post;
        }

        $lat            = $this->post->schemaObject['geo']['latitude'];
        $lng            = $this->post->schemaObject['geo']['longitude'];
        $googleMapsLink = $this->getGoogleMapsLink($lat, $lng);

        $this->post->openStreetMapData = [
            'pin'            => $this->getPin($this->post, $lat, $lng, $googleMapsLink),
            'googleMapsLink' => $googleMapsLink
        ];

        return $this->post;
    }

    private function getGoogleMapsLink(float $lat, float $lng): string
    {
        return 'https://www.google.com/maps/dir/?api=1&destination=' . $lat . ',' . $lng . '&travelmode=transit';
    }

    private function getPin(\Municipio\PostObject\PostObjectInterface $post, float $lat, float $lng, string $googleMapsLink): array
    {
        return [
            'icon'    => $post->getIcon() ? [
                'icon' => $post->getIcon()->getIcon(),
                'backgroundColor' => $post->getIcon()->getCustomColor(),
                'color' => 'white',
                'size' => 'md',
            ] : null,
            'lat'     => $lat,
            'lng'     => $lng,
            'tooltip' => [
                'title'      => $post->getTitle(),
                'excerpt'    => $post->post_excerpt ?? '',
                'url'        => $post->getPermalink(),
                'directions' => [
                    'url'   => $googleMapsLink ?? '',
                    'label' => __('Get directions on Google Maps', 'municipio'),
                ]
            ],
        ];
    }
}