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
                'data-js-pagination-container' => '',
            ]
        ])
            @if($places)
                @foreach($places as $place)
                    <div class="c-openstreetmap__posts" data-js-pagination-item tabindex="0">
                        @include('partials.collection')
                        @include('partials.post')
                    </div>
                @endforeach
            @endif
        @endcollection

        @include('partials.pagination')
    @endslot
@endopenStreetMap