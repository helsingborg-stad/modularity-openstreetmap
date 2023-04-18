@openStreetMap([
    'pins' => $pins,
    'startPosition' => $startPosition,
    'mapStyle' => $mapStyle,
    'title' => !$hideTitle && !empty($postTitle) ? $postTitle : false,
    'classList' => [$blockData['align'] == 'full' || $isFullWidth ? 'openstreetmap--full-width' : '']

])
    @if($places)
    @slot('sidebarContent')
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