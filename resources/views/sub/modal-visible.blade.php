<div id="{{ $name }}" class="custom-overlay-visible">
    <a href="{{ $path }}" class="cancel"></a>

    <div class="custom-modal">
        {{ $slot }}

        <a href="{{ $path }}" class="close">&times;</a>
    </div>
</div>
