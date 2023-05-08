@openStreetMap([
    'pins' => $pins,
    'startPosition' => $startPosition,
    'mapStyle' => $mapStyle,
    'fullWidth' => $blockData['align'] == 'full' || $isFullWidth ? true : false,
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
                'js-pagination-container' => '',
            ]
        ])
            @foreach($places as $place)
                <div class="openstreetmap__post-container" js-pagination-item>
                    @include('partials.collection')
                    @include('partials.post')
                </div>
            @endforeach
        @endcollection

        @include('partials.pagination')
    @endslot
    @endif
@endopenStreetMap