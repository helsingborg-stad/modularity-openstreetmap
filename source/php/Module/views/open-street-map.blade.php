<div id="openstreetmap" js-map-locations="{{$coords}}" js-map-start-position="{{$startPosition}}" js-map-style="{{$mapStyle}}" class="openstreetmap{{$blockData['align'] == 'full' || $isFullWidth ? ' openstreetmap--full-width' : ''}} " data-js-toggle-item="expand" data-js-toggle-class="is-expanded">
    <div style="height:100vh;" id="openstreetmap__map">
        @icon([
            'icon' => 'map',
            'size' => 'lg',
            'classList' => ['openstreetmap__expand-icon', 'u-level-1'],
            'attributeList' => ['data-js-toggle-trigger' => 'expand']
        ])
        @endicon
    </div>
    @if($places)
    <div class="openstreetmap__sidebar">
        <div class="openstreetmap__container" js-pagination-target>
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
        </div>
    </div>
    @endif
</div>