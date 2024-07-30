<?php

namespace ModularityOpenStreetMap\Decorator;

class EndpointOrder implements EndpointDecoratorInterface
{
    public function __construct()
    {}

    public function decorate(string $endpoint, array $fields): string 
    {
        $orderBy = $fields['mod_osm_order_by'];
        $order = $fields['mod_osm_order'];

        return $endpoint . '&order=' . ($orderBy !== 'random' ? $order : $this->getRandomizedOrder());
    }

    private function getRandomizedOrder(): string
    {
        $possibleOrders = ['ASC', 'DESC'];
        $randomOrderKey = array_rand($possibleOrders);

        return $possibleOrders[$randomOrderKey];
    }
}