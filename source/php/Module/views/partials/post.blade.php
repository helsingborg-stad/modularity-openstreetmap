<div class="openstreetmap__post">
    @if (!empty($place->thumbnail['src']))
        @hero([
            'image' => $place->thumbnail['src']
        ])
        @endhero
    @endif
</div>