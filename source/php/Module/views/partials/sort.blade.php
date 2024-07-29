@select([
    'options' => [
        'asc' => __('Ascending', 'modularity-open-street-map'),
        'desc' => __('Descending', 'modularity-open-street-map'),
        'rand' => __('Random', 'modularity-open-street-map'),
    ],
    'selectAttributeList' => [
        'data-js-sort-select' => 'osm-posts-container-' . $ID,
    ],
    'classList' => [
        'u-margin__bottom--4'
    ]
])
@endselect