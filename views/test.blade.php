
<div class="c-openstreetmap__posts">
@collection__item([
    'classList' => ['c-openstreetmap__collection__item'],
    'containerAware' => true,
    'bordered' => true,
    'attributeList' => [
        'data-js-map-location' => !empty($place->schemaData['place']['pin']) ? json_encode($place->schemaData['place']['pin']) : "",
    ]
])
@if (!empty($place->callToActionItems['floating']))
    @slot('floating')
        @icon($place->callToActionItems['floating'])
        @endicon
    @endslot
@endif
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
        @if(!empty($place->termIcon['icon']))
            @inlineCssWrapper([
                'styles' => ['background-color' => $place->termIcon['backgroundColor'], 'display' => 'flex'],
                'classList' => [$place->termIcon['backgroundColor'] ? '' : 'u-color__bg--primary', 'u-rounded--full', 'u-detail-shadow-3']
            ])
                @icon($place->termIcon)
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
    @endgroup
@endcollection__item

@if($includePost)
    halloj
@endif
</div>
