<?php

namespace ModularityOpenStreetMap\Decorator;

interface EndpointDecoratorInterface {
    public function decorate(string $endpoint, array $fields): string;
}