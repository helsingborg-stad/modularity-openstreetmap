<?php

declare(strict_types=1);


namespace ModularityOpenStreetMap;

class ApplyOpenStreetMapDataToPostObject
{
    public function __construct(
        private \Municipio\PostObject\PostObjectInterface $post,
    ) {}

    public function apply(): \Municipio\PostObject\PostObjectInterface|null
    {
        if (!$this->post->getSchemaProperty('geo') instanceof \Municipio\Schema\GeoCoordinates) {
            return $this->post;
        }

        $lat = $this->post->getSchemaProperty('geo')->getProperty('latitude');
        $lng = $this->post->getSchemaProperty('geo')->getProperty('longitude');

        if ($lat === null || $lng === null) {
            return $this->post;
        }

        $googleMapsLink = $this->getGoogleMapsLink($lat, $lng);

        $this->post->openStreetMapData = [
            'pin' => $this->getPin($this->post, $lat, $lng, $googleMapsLink),
            'googleMapsLink' => $googleMapsLink,
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
            'icon' => $post->getIcon()
                ? [
                    'icon' => $post->getIcon()->getIcon(),
                    'backgroundColor' => $post->getIcon() ? $post->getIcon()->getCustomColor() : null,
                    'color' => 'white',
                    'size' => 'md',
                ] : null,
            'lat' => $lat,
            'lng' => $lng,
            'tooltip' => [
                'title' => $post->getTitle(),
                'excerpt' => $post->post_excerpt ?? '',
                'url' => $post->getPermalink(),
                'directions' => [
                    'url' => $googleMapsLink ?? '',
                    'label' => __('Get directions on Google Maps', 'municipio'),
                ],
            ],
        ];
    }
}
