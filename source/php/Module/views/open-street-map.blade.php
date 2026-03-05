
@includeWhen(!empty($filters), 'partials.filters')
@element([
    'classList' => $classList,
    'attributeList' => $attributeList,
])
    <div class="{{$baseClass}}__map" style="height:100vh;" id="openstreetmap__map-{{$ID}}" tabindex="0">
        @icon([
            'icon' => 'keyboard_arrow_up',
            'size' => 'lg',
            'classList' => [$baseClass . '__expand-icon-desktop'],
        ])
        @endicon
    </div>
    @include('partials.sidebar')
    @include('partials.template')
@endelement
