@extends('layouts.app')

@section('content')

    @component('sub.card', ['title' => "Page d'authentification"])
        {{ __('Vous êtes authentifié') }}
    @endcomponent

@endsection
