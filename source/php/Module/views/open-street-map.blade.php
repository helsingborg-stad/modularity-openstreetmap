<div id="openstreetmap" js-map-locations="{{$coords}}" js-map-start-position="{{$startPosition}}" js-map-style="{{$mapStyle}}" class="openstreetmap{{$blockData['align'] == 'full' || $isFullWidth ? ' openstreetmap--full-width' : ''}} " data-js-toggle-item="expand" data-js-toggle-class="is-expanded">
    <div style="height:100vh;" id="openstreetmap__map"></div>
    @if($places)
    <div class="openstreetmap__sidebar">
        @icon([
            'icon' => 'map',
            'size' => 'lg',
            'classList' => ['openstreetmap__expand-icon'],
            'attributeList' => ['data-js-toggle-trigger' => 'expand']
        ])
        @endicon
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
        @include('partials.collection')
            @pagination([
                'list' => [
                    ['href' => '?pagination=1', 'label' => 'Page 1'],
                ],
                'classList' => [
                    'u-padding__top--8',
                    'u-padding__bottom--6',
                    'u-justify-content--center'
                ],
                'useJS' => true,
                'current' => 1,
                'perPage' => 8,
                'pagesToShow' => 4,
            ])
            @endpagination
        </div>
    </div>
    @endif
</div>