@extends('layouts.app')

@section('content')

    @component('sub.card', ['title' => 'Page de parrainage'])

        <span class="pl-3 h3">Nom :</span>
        <span class="h3 font-weight-bolder">
            {{ $ps['name'] }} {{ $ps['lastName'] }}</span>
        <p class="p-0"></p>
        <span class="pl-3 h3">Id national :</span>
        <span class="h3 font-weight-bolder">
            {{ $ps['nationalId'] }}</span>
        <p class="p-2"></p>

        <div class="text-center">
            <a class="btn btn-default btn-outline-primary" href="{{ route('ps.edit', $ps['nationalId']) }}">
                {{ __('Parrainer ce professionel') }}</a>
        </div>

    @endcomponent

@endsection
