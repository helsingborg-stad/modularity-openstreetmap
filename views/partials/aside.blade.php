@element([
    'componentElement' => 'aside',
    'classList' => [
        'o-layout-grid',
        'o-layout-grid--gap-6',
        'o-layout-grid--col-span-4@md',
        'o-layout-grid--col-span-12',
        'u-print-display--none',
        'o-layout-grid--grid-auto-rows-min-content',
        'o-layout-grid--order-3'
    ]
])
    @if (!empty($place->placeInfo))
        @listing([
            'list' => $place->placeInfo,
            'icon' => false,
            'classList' => [
                'unlist',
            ],
            'padding' => 4
        ])
        @endlisting
    @endif

    @if (!empty($place->placeActions))
        @foreach ($place->placeActions as $action)
            @button($action)
            @endbutton
        @endforeach
    @endif
@endelement