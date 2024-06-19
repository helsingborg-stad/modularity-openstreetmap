<?php

namespace ModularityOpenStreetMap\Api;

use Municipio\Api\RestApiEndpoint;
use ModularityOpenStreetMap\Api\Settings;
use ModularityOpenStreetMap\Api\SettingsInterface;
use ModularityOpenStreetMap\Api\OsmGetPosts;
use ModularityOpenStreetMap\Api\OsmQueryArgsCreator;
use ModularityOpenStreetMap\Api\OsmPostsHandler;
use ModularityOpenStreetMap\Api\PostTransformer\DefaultTransformer;
use ModularityOpenStreetMap\Api\PostTransformer\HtmlTransformer;
use WP_REST_Request;
use WP_REST_Response;

class OsmEndpoint extends RestApiEndpoint {
    private const NAMESPACE = 'osm/v1';
    private const ROUTE     = '/(?P<postType>[a-zA-Z0-9-]+)';
    private SettingsInterface|null $settings = null;

    public function handleRegisterRestRoute(): bool {
        return register_rest_route(self::NAMESPACE, self::ROUTE, array(
            'methods'             => 'GET',
            'callback'            => array($this, 'handleRequest'),
            'permission_callback' => '__return_true'
        ));
    }

    public function handleRequest(WP_REST_Request $request)
    {
        $this->settings = new Settings($request->get_params());

        if (!$this->settings->getPostType()) {
            return new WP_REST_Response(null, 400);
        }

        $argsInstance           = new OsmQueryArgsCreator($this->settings);
        $posts                  = (new OsmGetPosts($argsInstance->CreateQueryArgs()))->getPosts();
        $postsHandlerInstance   = new OsmPostsHandler(
            $posts, 
            $this->settings,
            new DefaultTransformer($this->settings),
            new HtmlTransformer($this->settings)
        );

        $test = $postsHandlerInstance->getTransformedPosts();
        echo '<pre>' . print_r( $test, true ) . '</pre>';
        die;
        return new WP_REST_Response($postsHandlerInstance->getTransformedPosts(), 200);
    }
}