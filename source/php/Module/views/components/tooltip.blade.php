<div class="modularity-openstreetmap__tooltip">
    @typography([
        'classList' => ['modularity-openstreetmap__tooltip-title'],
        'element' => 'h2',
        'variant' => 'h5',
        'attributeList' => [
            'data-js-osm-id' => '{TOOLTIP_ID}'
        ]
    ])
        {TOOLTIP_HEADING}
    @endtypography
    @typography([
        'classList' => ['u-margin__y--1', 'modularity-openstreetmap__tooltip-excerpt'],
        
    ])
        {TOOLTIP_EXCERPT}
    @endtypography
    @image([
        'classList' => ['modularity-openstreetmap__tooltip-image', 'u-margin__top--1'],
        'src' => '{TOOLTIP_IMAGE_SRC}',
        'alt' => '{TOOLTIP_IMAGE_ALT}',
        'attributeList' => [
            'rel' => 'nofollow'
        ]
    ])
    @endimage
    @link([
        'href' => '{TOOLTIP_DIRECTIONS_URL}',
        'classList' => ['modularity-openstreetmap__tooltip-directions'],
        'attributeList' => [
            'rel' => 'nofollow'
        ]
    ])
        {TOOLTIP_DIRECTIONS_LABEL}
    @endlink
</div>
