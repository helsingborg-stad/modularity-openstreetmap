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
                'isMultiSelect' => true,
                'placeholder' => $filter['label'],
                'options' => $filter['terms']
            ])
            @endselect
        @endif
    @endforeach
@endgroup