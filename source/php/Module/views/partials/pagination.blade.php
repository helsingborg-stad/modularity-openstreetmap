@pagination([
    'classList' => [
        'u-padding__top--8',
        'u-padding__bottom--6',
        'u-justify-content--center'
    ],
    'useJS' => true,
    'current' => 1,
    'perPage' => $perPage,
    'pagesToShow' => 4,
    'keepDOM' => true,
])
@endpagination