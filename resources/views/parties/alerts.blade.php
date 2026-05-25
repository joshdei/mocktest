@foreach (['success', 'error', 'warning', 'info'] as $type)
    @if (session($type))
        <div class="alert alert-{{ $type }}">
            {{ session($type) }}
        </div>
    @endif
@endforeach