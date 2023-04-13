@group([
        'classList' => ['openstreetmap__post'],
        'containerAware' => true,
    ])
    @if (!empty($place->thumbnail['src']))
        @hero([
            'image' => $place->thumbnail['src']
        ])
        @endhero
    @endif
    <div class="u-margin__x--2">
        @paper([
            'attributeList' => [
                'style' => !empty($place->thumbnail['src']) ? 'transform:translateY(calc(max(-50%, -50px)))' : 'margin-top: 32px'
            ],
            'containerAware' => true,
            'classList' => ['u-padding--6', 'o-container']
        ])
        @typography([
            'element' => 'h1',
            'variant' => 'h1'
            ])
            {{ $place->postTitle }}
        @endtypography
            <div class="o-grid openstreetmap__post-container">
                <div class="openstreetmap__post-content">
                        @typography([
                        ])
                        {!! $place->postContentFiltered !!}
                        @endtypography
                </div>
                <div class="openstreetmap__post-list">
                    @listing([
                        'list' => $place->list,
                        'icon' => false,
                        'classList' => ['unlist'],
                        'padding' => 4,
                        ])
                    @endlisting
                </div>
            </div>
        @endpaper
    </div>
    @foreach ($place->relatedPosts as $postType => $posts)
        <div class="o-grid openstreetmap__post-related-posts o-container">
            @group([
                'justifyContent' => 'space-between'
            ])
                @typography([
                    'element' => 'h2'
                ])
                    {{ $labels['related'] }} {{ $postType }}
                @endtypography
                @if (!empty(get_post_type_archive_link($postType)))
                    @link([
                        'href' => get_post_type_archive_link($postType)
                    ])
                        {{ $labels['showAll'] }}
                    @endlink
                @endif
            @endgroup
            @foreach ($posts as $post)
                <div class="u-margin__bottom--8 openstreetmap__post-related-post">
                    @segment([
                        'layout' => 'card',
                        'title' => $post->postTitle,
                        'content' => $post->excerptShort,
                        'image' => $post->thumbnail['src'],
                        'buttons' => [['text' => 'Read more', 'href' => $post->permalink]],
                        'tags' => $post->terms,
                        'meta' => $post->readingTime
                    ])
                    @endsegment
                </div>
            @endforeach
        </div>
    @endforeach
@endgroup