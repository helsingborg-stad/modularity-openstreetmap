<?php

namespace ModularityOpenStreetMap\Api\PostTransformer;

interface PostTransformerInterface
{
    public function transform($post): mixed;
}