@group([
    'flexGrow' => true,
    'classList' => [
        'o-container',
        'o-grid'
    ]
])
    @foreach($filters as $filter)
        @if (!empty($filter['terms']))
            @select([
                'multiple' => true,
                'placeholder' => $filter['label'],
                'options' => $filter['terms'],
                'selectAttributeList' => [
                    'data-js-filter-select' => 'osm-filter-item-' . $filter['taxonomy'],
                    'data-js-filter-select-target' => 'osm-filter-container-' . $ID
                ]
            ])
            @endselect
        @endif
    @endforeach
@endgroup