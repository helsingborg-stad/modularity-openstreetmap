@modal([
    'closeButtonText' => 'Close',
    'id' => 'osm-' . $place->id,
    'isPanel' => true,
    'padding' => 0,
])
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
                    'element' => 'h1',
                    'variant' => 'h1'
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
@endmodal