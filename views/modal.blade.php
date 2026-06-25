@element([
    'classList' => [
        'modularity-openstreetmap__post-full',
        'u-display--none'
    ],
    'attributeList' => [
        'role' => 'dialog',
        'aria-hidden' => 'true',
        'aria-labelledby' => 'osm-' . $place->id . '-title',
        'data-js-osm-full-post' => 'osm-' . $place->id
    ]
])
    @include('partials.hero')
    @element([
        'classList' => [
            'o-container'
        ]
    ])
        @paper([
            'attributeList' => [
                'style' => !empty($place->featuredImage['src']) ? 'transform:translateY(calc(max(-50%, -50px)))' : 'margin-top: 32px'
            ],
            'classList' => ['u-padding--6']
        ])
            @element([
                'classList' => [
                    'o-layout-grid',
                    'o-layout-grid--cols-12',
                    'o-layout-grid--column-gap-8@md', 
                    'o-layout-grid--row-gap-12'
                ]
            ])
                @include('partials.content')
                @include('partials.aside')
            @endelement
        @endpaper
    @endelement
@endelement