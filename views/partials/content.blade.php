@element([
    'componentElement' => 'article',
    'classList' => [
        'o-layout-grid--col-span-8@md',
        'o-layout-grid--col-span-12',
        'o-layout-grid',
        'o-layout-grid--gap-6',
        'o-layout-grid--grid-auto-rows-min-content',
        'o-layout-grid--order-2@md',
        'o-layout-grid--order-1'
    ]
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
        @if (!empty($place->callToActionItems['floating']['icon']) && !empty($place->callToActionItems['floating']['wrapper']))
            @element($place->callToActionItems['floating']['wrapper'] ?? [])
                @icon($place->callToActionItems['floating']['icon'])
                @endicon
            @endelement
        @endif
    @endgroup
    @typography([])
        {!! $place->postContentFiltered !!}
    @endtypography
@endelement