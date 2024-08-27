@includeWhen(!empty($filters), 'partials.filters')
@openStreetMap([
    'startPosition' => $startPosition,
    'mapStyle' => $mapStyle,
    'fullWidth' => true,
    'containerAware' => true,
    'expanded' => $expanded,
    'attributeList' => [
        'data-js-map-posts-endpoint' => $endpoint,
    ]

])
    @slot('sidebarContent')
    @includeWhen($sort, 'partials.sort')
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
            'id' => 'osm-posts-container-' . $ID,
            'classList' => ['o-grid', 'o-grid--horizontal'],
            'attributeList' => [
                'data-js-osm-endpoint-posts' => '',
                'data-js-filter-select-container' => 'osm-filter-container-' . $ID,
            ],
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