@if (Auth::user())
    <div class="card-header bg-dark font-weight-bold text-white">
        {{ $title }}
        <img src="{{ asset('images/authenticated-user.png') }}"
             style="max-height: 1.8em; max-width: 1.8em; float:right;" alt="auth-user"
             data-toggle="tooltip" title="Utilisateur authentifié">
    </div>
@else
    <div class="card-header">{{ __("Page de parrainage") }}</div>
@endif
