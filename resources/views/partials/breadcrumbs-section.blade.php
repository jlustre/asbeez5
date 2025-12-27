<section class="breadcrumb-section pt-0">
    <div class="container-fluid-lg">
        <div class="row">
            <div class="col-12">
                <div class="breadcrumb-contain">
                    @php
                    $items = [];
                    if (isset($breadcrumbs) && is_array($breadcrumbs) && count($breadcrumbs) > 0) {
                    $items = $breadcrumbs;
                    } else {
                    $items = [
                    ['label' => 'Home', 'url' => route('home')],
                    ['label' => $title ?? '']
                    ];
                    }
                    $heading = $title ?? ($items[count($items) - 1]['label'] ?? '');
                    @endphp

                    <h2>{{ $heading }}</h2>
                    <nav>
                        <ol class="breadcrumb mb-0">
                            @foreach ($items as $item)
                            <li class="breadcrumb-item {{ $loop->last ? 'active' : '' }}">
                                @if (!empty($item['url']) && !$loop->last)
                                <a href="{{ $item['url'] }}">{{ $item['label'] ?? '' }}</a>
                                @else
                                {{ $item['label'] ?? '' }}
                                @endif
                            </li>
                            @endforeach
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</section>