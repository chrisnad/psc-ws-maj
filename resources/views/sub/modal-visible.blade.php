<div id="{{ $name }}" class="custom-overlay-visible">
    <a href="{{ route($route) }}" class="cancel"></a>

    <div class="custom-modal">
        {{ $slot }}

        <a href="{{ route($route) }}" class="close">&times;</a>
    </div>
</div>
