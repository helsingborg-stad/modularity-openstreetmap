<div class="{{$baseClass}}__sidebar u-display--none" data-observe-resizes>
    <div class="{{$baseClass}}__expand-icon-container">
        @icon([
            'icon' => 'keyboard_arrow_up',
            'size' => 'lg',
            'classList' => [$baseClass . '__expand-icon-mobile'],
        ])
        @endicon
    </div>
    <div class="{{$baseClass}}__container">
        <div class="{{$baseClass}}__inner-blocks u-hide-empty">{!! '<InnerBlocks />' !!}</div>
        @includeWhen($sort, 'partials.sort')
    <div>
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
            'id' => 'osm-posts-container-' . $ID,
            'classList' => ['o-grid', 'o-grid--horizontal'],
            'attributeList' => [
                'data-js-osm-endpoint-posts' => '',
                'data-js-filter-select-container' => 'osm-filter-container-' . $ID,
            ],
        ])

        @endcollection

        @loader([
            'shape' => 'linear',
            'size' => 'xs',
            'classList' => [
                'u-margin__top--8',
                'u-margin__bottom--8'
            ],
            'attributeList' => [
                'data-js-map-endpoint-spinner' => ''
            ]
        ])
        @endloader

        <template data-js-map-no-posts-found>
            @notice([
                'type' => 'info',
                'message' => [
                    'text' => $lang['noPostsFound']
                ],
                'icon' => [
                    'name' => 'report',
                    'size' => 'md',
                    'color' => 'white'
                ]
            ])
            @endnotice
        </template>
    </div>
    </div>
</div>