@openStreetMap([
    'pins' => $pins,
    'startPosition' => $startPosition,
    'mapStyle' => $mapStyle,
    'fullWidth' => $blockData['align'] == 'full' || $isFullWidth ? true : false

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
            'classList' => ['c-collection--posts', 'o-grid'],
            'attributeList' => [
                'js-pagination-container' => '',
            ]
        ])
            @foreach($places as $place)
                <div class="{{$postsColumns}}" js-pagination-item>
                    @include('partials.collection')
                    @include('partials.post')
                </div>
            @endforeach
        @endcollection

        @include('partials.pagination')
    @endslot
    @endif
@endopenStreetMap