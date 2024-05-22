<div class="page-header">
    <div class="w-full flex items-center justify-between">
        <h3 class="page-title flex-1">{{ $active }}</h3>
        <ul class="breadcrumb">
            @foreach ($navs as $url => $title)
                <li class="breadcrumb-item"><a href="{{ $url }}">{{ $title }}</a></li>
            @endforeach
            <li class="breadcrumb-item active">{{ $active }}</li>
        </ul>
    </div>
</div>
