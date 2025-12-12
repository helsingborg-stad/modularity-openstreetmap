<?php

declare(strict_types=1);

namespace ModularityOpenStreetMap\Api;

use ModularityOpenStreetMap\Api\OsmGetPosts;
use ModularityOpenStreetMap\Api\OsmQueryArgsCreator;
use ModularityOpenStreetMap\Api\OsmTransformationHandler;
use ModularityOpenStreetMap\Api\PostTransformer\DefaultTransformer;
use ModularityOpenStreetMap\Api\PostTransformer\HtmlTransformer;
use ModularityOpenStreetMap\Api\Settings;
use ModularityOpenStreetMap\Api\SettingsInterface;
use Municipio\Api\RestApiEndpoint;
use WP_REST_Request;
use WP_REST_Response;

class OsmEndpoint extends RestApiEndpoint
{
    private const ROUTE = '/(?P<postType>[a-zA-Z0-9-]+)';

    private SettingsInterface|null $settings = null;

    public function handleRegisterRestRoute(): bool
    {
        return register_rest_route(OSM_ENDPOINT, self::ROUTE, [
            'methods' => 'GET',
            'callback' => [$this, 'handleRequest'],
            'permission_callback' => '__return_true',
        ]);
    }

    public function handleRequest(WP_REST_Request $request)
    {
        $this->settings = new Settings($request->get_params());

        if (!$this->settings->getPostType()) {
            return new WP_REST_Response(null, 400);
        }

        $argsInstance = new OsmQueryArgsCreator($this->settings);
        $posts = (new OsmGetPosts($argsInstance->CreateQueryArgs(), $this->settings))->getPosts();
        $postsHandlerInstance = new OsmTransformationHandler(
            $posts,
            $this->settings,
            new DefaultTransformer($this->settings),
            new HtmlTransformer($this->settings),
        );

        return new WP_REST_Response($postsHandlerInstance->getTransformedPosts(), 200);
    }
}
