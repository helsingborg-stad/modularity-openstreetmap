@openStreetMap([
    'startPosition' => $startPosition,
    'mapStyle' => $mapStyle,
    'fullWidth' => (!empty($blockData['align']) && $blockData['align'] == 'full') || $isFullWidth ? true : false,
    'containerAware' => true,
    'attributeList' => [
        'data-js-map-posts-endpoint' => $endpoint,
    ]

])
    @slot('sidebarContent')
    <div>
        @if (!$hideTitle && !empty($postTitle))
            @typography([
                'id' => 'mod-posts-' . $ID . '-label',
                'element' => 'h2',
                'variant' => 'h2',
                'classList' => ['module-title']
            ])
                {!! $postTitle !!}
            @endtypography
        @endif
        @collection([
            'attributeList' => [
                'data-js-osm-endpoint-posts' => '',
            ]
        ])

        @endcollection

        @loader([
            'shape' => 'linear',
            'size' => 'xs',
            'classList' => [
                'u-margin__top--8',
                'u-margin__bottom--8'
            ],
            'attributeList' => [
                'data-js-map-endpoint-spinner' => ''
            ]
        ])
        @endloader

        <template data-js-map-no-posts-found>
            @notice([
                'type' => 'info',
                'message' => [
                    'text' => $lang['noPostsFound']
                ],
                'icon' => [
                    'name' => 'report',
                    'size' => 'md',
                    'color' => 'white'
                ]
            ])
            @endnotice
        </template>
    </div>
    @endslot
@endopenStreetMap