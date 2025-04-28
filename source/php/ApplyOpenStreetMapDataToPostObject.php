<?php

namespace ModularityOpenStreetMap;

class ApplyOpenStreetMapDataToPostObject {
    public function __construct(private \Municipio\PostObject\PostObjectInterface $post)
    {}
    public function apply():\Municipio\PostObject\PostObjectInterface|null {
        $geo = $this->post->getSchemaProperty('geo');

        if (!$geo) {
            return $this->post;
        }

        if (is_string($geo)) {
            $geo = unserialize($geo);
        }

        if (isset($geo['lat'], $geo['lng'])) {
            $lat = $geo['lat'];
            $lng = $geo['lng'];
        } else {
            return $this->post;
        }

        //This returns the same cached? value each time
        //$lat            = $this->post->getSchemaProperty('geo')['latitude'] ?? null;
        //$lng            = $this->post->getSchemaProperty('geo')['longitude'] ?? null;
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
                'backgroundColor' => $post->getIcon() ? $post->getIcon()->getCustomColor() : null,
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