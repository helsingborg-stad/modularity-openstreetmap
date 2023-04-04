<div id="openstreetmap" js-map-locations="{{$coords}}" js-map-start-position="{{$startPosition}}" js-map-style="{{$mapStyle}}" class="openstreetmap{{$blockData['align'] == 'full' || $isFullWidth ? ' openstreetmap--full-width' : ''}} " data-js-toggle-item="expand" data-js-toggle-class="is-expanded">
    <div style="height:1000px; width: 70%;" id="openstreetmap__map"></div>
    @if($places)
    <div class="openstreetmap__sidebar">
        @icon([
            'icon' => 'map',
            'size' => 'lg',
            'classList' => ['openstreetmap__expand-icon'],
            'attributeList' => ['data-js-toggle-trigger' => 'expand']
        ])
        @endicon
        <div class="openstreetmap__container">
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
        @include('partials.collection')
            @pagination([
                'list' => $secondaryPaginationList,
                'classList' => ['u-margin__top--8', 'u-display--flex', 'u-justify-content--center'],
                'current' => 1,
                'linkPrefix' => '?paged='
            ])
            @endpagination
        </div>
    </div>
    @endif
</div>