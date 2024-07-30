<?php

namespace ModularityOpenStreetMap\Decorator;

class EndpointOrderBy implements EndpointDecoratorInterface
{
    public function __construct()
    {}

    public function decorate(string $endpoint, array $fields): string 
    {
        $orderBy = $fields['mod_osm_order_by'] ?? 'date';

        return $endpoint . '&orderby=' . ($orderBy !== 'random' ? $orderBy : $this->getRandomizedOrderBy());
    }

    private function getRandomizedOrderBy(): string
    {
        $possibleOrderBys = ['title', 'date', 'modified', 'ID', 'author'];
        $randomOrderByKey = array_rand($possibleOrderBys);

        return $possibleOrderBys[$randomOrderByKey] . '&randomize=true';
    }
}