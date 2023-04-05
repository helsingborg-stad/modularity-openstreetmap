@collection([
    'classList' => ['c-collection--posts', 'o-grid']
])
    @foreach($places as $place)
    <div class="{{$postsColumns}}">
        @collection__item([
            'classList' => ['c-collection__item--post'],
            'containerAware' => true,
            // 'link' => $place->permalink,
            'attributeList' => ['js-map-lat' => $place->location['lat'], 'js-map-lng' => $place->location['lng']]
        ])
        @slot('before')
            @if(!empty($place->thumbnail['src']))
                @image([
                    'src' => $place->thumbnail['src'],
                    'alt' => $place->thumbnail['alt'] ? $place->thumbnail['alt'] : $place->postTitle,
                ])
                @endimage
            @endif
        @endslot
        @group([
            'direction' => 'vertical'
        ])
            @group([
                'justifyContent' => 'space-between'
            ])
                @typography([
                    'element' => 'h2',
                    'variant' => 'h3',
                ])
                    {{$place->postTitle}}
                @endtypography
                @if($place->termMarker)
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
    </div>
    @endforeach
@endcollection