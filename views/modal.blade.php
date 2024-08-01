<div class="c-openstreetmap__post-full u-display--none" role="dialog" aria-hidden="true" aria-labelledby="osm-{{$place->id}}-title" data-js-osm-full-post="osm-{{$place->id}}">

<div class="c-openstreetmap__button-back-container">
    @icon([
        'icon' => 'arrow_back',
        'size' => 'md',
        'color' => 'white',
        'classList' => ['c-openstreetmap__button-back', 'c-openstreetmap__post-icon'],
        'attributeList' => [
            'tabindex' => '0',
        ]
    ])
    @endicon
</div>

@if(!empty($place->images['thumbnail16:9']['src']))
    @hero([
            'image' => $place->featuredImage['src'],
        ])
    @endhero
@endif
    <div class="o-container">
        @paper([
            'attributeList' => [
                'style' => !empty($place->featuredImage['src']) ? 'transform:translateY(calc(max(-50%, -50px)))' : 'margin-top: 32px'
            ],
            'classList' => ['u-padding--6']
        ])
            @group([
                'justifyContent'=> 'space-between',
            ])
                @typography([
                    'element' => 'h2',
                    'variant' => 'h1',
                    'id' => 'osm-' . $place->id . '-title',
                ])
                    {!! $place->postTitleFiltered !!}
                @endtypography
                @if (!empty($place->callToActionItems['floating']))
                    @icon($place->callToActionItems['floating'])
                    @endicon
                @endif
            @endgroup
            <div class="o-grid u-padding__top--4">
                <div class="o-grid-12@sm o-grid-9@md o-grid-9@lg o-grid-9@xl">
                    @typography([])
                        {!! $place->postContentFiltered !!}
                    @endtypography
                </div>
                <div class="o-grid-12@sm o-grid-3@md o-grid-3@lg o-grid-3@xl">

                    @if (!empty($place->placeInfo))
                        @listing([
                            'list' => $place->placeInfo,
                            'icon' => false,
                            'classList' => [
                                'unlist',
                                'u-padding__top--2@xs',
                                'u-padding__top--2@sm',
                                'u-padding__bottom--3',
                                'u-margin__top--2'
                            ],
                            'padding' => 4
                        ])
                        @endlisting
                    @endif

                    @if (!empty($place->bookingLink))
                        @button([
                            'text' => $lang->bookHere,
                            'color' => 'primary',
                            'style' => 'filled',
                            'href' => $place->bookingLink,
                            'classList' => ['u-width--100'],
                        ])
                        @endbutton
                    @endif

                </div>
            </div>
        @endpaper
    </div>
</div>