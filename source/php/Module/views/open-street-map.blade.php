@openStreetMap([
    'pins' => $pins,
    'startPosition' => $startPosition,
    'mapStyle' => $mapStyle,
    'title' => !$hideTitle && !empty($postTitle) ? $postTitle : false,
    'classList' => [$blockData['align'] == 'full' || $isFullWidth ? 'openstreetmap--full-width' : '']

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
        <div class="openstreetmap__inner-blocks u-hide-empty">{!! '<InnerBlocks />' !!}</div>
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