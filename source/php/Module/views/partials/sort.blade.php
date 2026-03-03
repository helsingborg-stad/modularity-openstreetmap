@select([
    'placeholder' => $lang['sort'],
    'options' => [
        'asc' => $lang['ascending'],
        'desc' => $lang['descending'],
        'rand' => $lang['randomized'],
    ],
    'selectAttributeList' => [
        'data-js-sort-select' => 'osm-posts-container-' . $ID,
    ],
    'classList' => [
        'u-margin__bottom--4'
    ]
])
@endselect
