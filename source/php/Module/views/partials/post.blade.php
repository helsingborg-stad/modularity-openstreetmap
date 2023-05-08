@group([
        'classList' => ['c-openstreetmap__post'],
        'containerAware' => true,
    ])
    @icon([
        'icon' => 'arrow_back',
        'size' => 'md',
        'color' => 'white',
        'classList' => ['c-openstreetmap__post-icon'],
    ])
    @endicon
    @if (!empty($place->thumbnail['src']))
        @hero([
            'image' => $place->thumbnail['src']
        ])
        @endhero
    @endif
    <div class="u-margin__x--2">
        @paper([
            'attributeList' => [
                'style' => !empty($place->thumbnail['src']) ? 'transform:translateY(calc(max(-50%, -50px)))' : 'margin-top: 32px'
            ],
            'containerAware' => true,
            'classList' => ['u-padding--6', 'o-container']
        ])
        @group([
            'justifyContent' => 'space-between',
        ])
        @typography([
            'element' => 'h1',
            'variant' => 'h1'
            ])
            {{ $place->postTitle }}
        @endtypography
        @if (!empty($place->callToActionItems['floating']))
            @icon($place->callToActionItems['floating'])
            @endicon
        @endif
        @endgroup
            <div class="o-grid c-openstreetmap__post-container">
                <div class="c-openstreetmap__post-content">
                        @typography([
                        ])
                        {!! $place->postContentFiltered !!}
                        @endtypography
                </div>
                @if($place->placeInfo)
                <div class="c-openstreetmap__post-list">
                    @listing([
                        'list' => $place->placeInfo,
                        'icon' => false,
                        'classList' => ['unlist'],
                        'padding' => 4,
                        ])
                    @endlisting
                </div>
                @endif
            </div>
        @endpaper
    </div>
@endgroup