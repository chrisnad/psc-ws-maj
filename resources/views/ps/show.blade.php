@extends('layouts.app')

@section('content')

    @component('sub.card', ['title' => 'Page de parrainage'])
        <span class="pl-3 h3">Nom :</span>
        <span class="h3 font-weight-bolder">
            {{ $ps['firstName'] }} {{ $ps['lastName'] }}</span>
        <p class="p-0"></p>
        <span class="pl-3 h3">Id national :</span>
        <span class="h3 font-weight-bolder">
            {{ $ps['nationalId'] }}</span>
        <p class="p-2"></p>

        @if($errors->any())
            @include('ps.edit')
        @else
            <div class="edit-form" style="display:none">
                @include('ps.edit')
            </div>
            <div class="text-center">
                <a class="btn btn-default btn-outline-primary" id="more" href="#edit"
                   onclick="$('.edit-form').slideToggle(); this.style.display = 'none'">
                    {{ __('Parrainer ce professionel') }}</a>
            </div>
        @endif

    @endcomponent

@endsection
