@if (session('authenticated'))
    <div class="card-header font-weight-bold text-white bg-primary">
        {{ $title }}
        <img src="{{ asset('images/authenticated-user-2.png') }}"
             style="max-height: 3em; max-width: 3em; position: absolute; top: 1.7em; right: 1em; float:right;" alt="auth-user"
             data-toggle="tooltip" title="Utilisateur authentifié">
    </div>
@else
    <div class="card-header">{{ __("Page de parrainage") }}</div>
@endif
