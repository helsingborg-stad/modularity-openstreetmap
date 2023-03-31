<div id="openstreetmap" js-map-locations="{{$coords}}" js-map-start-position="{{$startPosition}}" class="openstreetmap{{$blockData['align'] == 'full' || $isFullWidth ? ' openstreetmap--full-width' : ''}}">
    <div style="height:1000px" id="openstreetmap_map"></div>
    @if($places)
    <div class="openstreetmap-sidebar" data-js-toggle-item="expand" data-js-toggle-class="is-expanded">
        @icon([
            'icon' => 'map',
            'size' => 'lg',
            'classList' => ['openstreetmap-expand-icon'],
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