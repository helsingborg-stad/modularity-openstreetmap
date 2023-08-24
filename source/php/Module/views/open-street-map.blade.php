@openStreetMap([
    'startPosition' => $startPosition,
    'mapStyle' => $mapStyle,
    'fullWidth' => (isset($blockData['align']) && $blockData['align'] == 'full') || $isFullWidth ? true : false,
    'containerAware' => true,

])
    @if($places)
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
            @foreach($places as $place)
                <div class="c-openstreetmap__posts" data-js-pagination-item tabindex="0">
                    @include('partials.collection')
                    @include('partials.post')
                </div>
            @endforeach
        @endcollection

        @include('partials.pagination')
    @endslot
    @endif
@endopenStreetMap