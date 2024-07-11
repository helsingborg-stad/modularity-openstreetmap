@group([
    'flexGrow' => true,
    'classList' => [
        'o-container',
        'o-grid'
    ]
])
    @foreach($filters as $filter)
    @dump($filter)
        @if (!empty($filter['terms']))
            @select([
                'isMultiSelect' => true,
                'placeholder' => $filter['label'],
                'options' => $filter['terms'],
                'attributeList' => [
                    'data-js-filter' => 'osm-' . $filter['taxonomy'],
                    'data-js-filter-target' => 'osm-filter-container-' . $ID
                ]
            ])
            @endselect
        @endif
    @endforeach
@endgroup