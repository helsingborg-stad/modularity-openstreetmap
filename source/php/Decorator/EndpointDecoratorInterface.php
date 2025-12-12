<?php

declare(strict_types=1);


namespace ModularityOpenStreetMap\Decorator;

interface EndpointDecoratorInterface
{
    public function decorate(string $endpoint, array $fields): string;
}
