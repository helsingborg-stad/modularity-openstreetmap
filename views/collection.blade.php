@collection__item([
    'classList' => ['c-openstreetmap__collection__item'],
    'containerAware' => true,
    'bordered' => true,
    'attributeList' => [
        'data-js-map-location' => !empty($place->openStreetMapData['pin']) ? json_encode(array_merge($place->openStreetMapData['pin'], ['id' => 'osm-' . $place->id])) : "",
        'id' => 'osm-' . $place->id,
        'tabindex' => '0'
    ]
])

    @slot('floating')
        @if (!empty($place->callToActionItems['floating']['icon']) && !empty($place->callToActionItems['floating']['wrapper']))
            @element($place->callToActionItems['floating']['wrapper'] ?? [])
                @icon($place->callToActionItems['floating']['icon'])
                @endicon
            @endelement
        @endif
    @endslot
@slot('before')
    @if(!empty($place->images['thumbnail16:9']['src']))
        @image([
            'src' => $place->images['thumbnail16:9']['src'],
            'alt' => $place->images['thumbnail16:9']['alt'] ? $place->images['thumbnail16:9']['alt'] : $place->postTitle,
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
        @if(!empty($place->getIcon()))
            @element([
                'attributeList' => [
                    'style' => 'background-color: ' . $place->getIcon()->getCustomColor() ?? 'transparent' . ';',
                ],
                'classList' => [
                    'u-display--flex',
                    $place->getIcon()->getCustomColor() ? 'u-padding__x--1' : '',
                    $place->getIcon()->getCustomColor() ? 'u-padding__y--1' : '',
                    'u-rounded--full',
                    'u-detail-shadow-3'
                ]
            ])
                @icon([
                    'icon' => $place->getIcon()->getIcon(),
                    'color' => 'white',
                ])
                @endicon
            @endelement
        @endif
    @endgroup
        @tags([
            'tags' => $place->termsUnlinked,
            'classList' => ['u-padding__y--2'],
            'format' => true,
            'compress' => 3
        ])
        @endtags
    @endgroup
@endcollection__item