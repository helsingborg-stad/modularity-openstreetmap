<div class="modularity-openstreetmap__button-back-container">
    @icon([
        'icon' => 'arrow_back',
        'size' => 'md',
        'classList' => ['modularity-openstreetmap__button-back', 'modularity-openstreetmap__post-icon'],
        'attributeList' => [
            'tabindex' => '0',
            'style' => 'color: white;',
        ]
    ])
    @endicon
</div>

@if(!empty($place->images['thumbnail16:9']['src']))
    @hero([
            'image' => $place->featuredImage['src'],
        ])
    @endhero
@endif