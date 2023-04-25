@collection__item([
    'classList' => ['c-collection__item--post', 'c-openstreetmap__collection__item'],
    'containerAware' => true,
    'attributeList' => [
        'js-map-lat' => $place->location['lat'], 
        'js-map-lng' => $place->location['lng'],
    ]
])
@slot('before')
    @if(!empty($place->thumbnail['src']))
        @image([
            'src' => $place->thumbnail['src'],
            'alt' => $place->thumbnail['alt'] ? $place->thumbnail['alt'] : $place->postTitle,
            'classList' => ['u-width--100']
        ])
        @endimage
    @endif
@endslot
@group([
    'direction' => 'vertical'
])
    @group([
        'justifyContent' => 'space-between',
        'alignItems' => 'flex-start',
    ])
        @typography([
            'element' => 'h2',
            'variant' => 'h3',
        ])
            {{$place->postTitle}}
        @endtypography
        @if($place->termMarker['icon'])
            @inlineCssWrapper([
                'styles' => ['background-color' => $place->termMarker['backgroundColor'], 'display' => 'flex'],
                'classList' => [$place->termMarker['backgroundColor'] ? '' : 'u-color__bg--primary', 'u-rounded--full', 'u-detail-shadow-3']
            ])
                @icon($place->termMarker)
                @endicon
            @endinlineCssWrapper
        @endif
    @endgroup
        @tags([
            'tags' => $place->termsUnlinked,
            'classList' => ['u-padding__y--2'],
            'format' => true,
        ])
        @endtags
        @typography([])
            {{$place->postExcerpt}}
        @endtypography
    @endgroup
@endcollection__item