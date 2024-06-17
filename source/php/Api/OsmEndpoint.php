<?php

namespace ModularityOpenStreetMap\Api;

use Municipio\Api\RestApiEndpoint;
use WP_REST_Request;
use WP_REST_Response;

class OsmEndpoint extends RestApiEndpoint {
    private const NAMESPACE = 'osm/v1';
    private const ROUTE     = '/(?P<postType>[a-zA-Z0-9-]+)';

    public function handleRegisterRestRoute(): bool {
        return register_rest_route(self::NAMESPACE, self::ROUTE, array(
            'methods'             => 'GET',
            'callback'            => array($this, 'handleRequest'),
            'permission_callback' => '__return_true'
        ));
    }

    public function handleRequest(WP_REST_Request $request)
    {
        $params = $request->get_params();

        if (empty($params['postType'])) {
            return new WP_REST_Response(null, 400);
        }

        $args = [
            'postType' => $params['postType'],
            'page' => $params['page'] ?? 1,
            'postsPerPage' => $params['postsPerPage'] ?? 20
        ];

        unset($params['postType']);
        unset($params['page']);
        unset($params['postsPerPage']);

        $responseData = $this->getPosts($args, $params);

        return new WP_REST_Response($responseData, 200);
    }

    private function getPosts(array $args, array $taxonomies) {
        $args = [
            'post_type' => $args['postType'],
            'posts_per_page' => $args['postsPerPage'],
            'paged' => $args['page'],
            'post_status' => 'publish',
            'tax_query' => [
                'relation' => 'OR',
            ]
        ];

        foreach ($taxonomies as $taxonomy => $termsString) {
            $args['tax_query'][] = [
                'taxonomy' => $taxonomy,
                'field' => 'term_id',
                'terms' => explode(',', $termsString)
            ];
        }

        $query = new \WP_Query($args);

        if (!$query->have_posts()) {
            return [];
        }
        
        // foreach 
        $responseData = [
            'posts' => $query->posts,
            'foundPosts' => $query->found_posts
        ];

        return $responseData;
    }
}