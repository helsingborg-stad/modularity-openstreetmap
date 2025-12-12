<?php

declare(strict_types=1);


namespace ModularityOpenStreetMap\Api\PostTransformer;

interface PostTransformerInterface
{
    public function transform($post): mixed;
}
