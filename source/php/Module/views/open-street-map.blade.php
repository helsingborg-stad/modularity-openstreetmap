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
            'classList' => ['o-grid', 'o-grid--horizontal'],
            'attributeList' => [
                'data-js-osm-endpoint-posts' => '',
            ]
        ])

        @endcollection
    @endslot
    </div>
@endopenStreetMap