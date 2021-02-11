@extends('layouts.app')

@section('content')

    @component('sub.card', ['title' => 'Recherche par identifiant'])

        @if (Auth::user())
            <p>{{ __("Bonjour ") }} {{Auth::user()->name}}</p>
        @else
            <p>{{ __("Bonjour, authentifiez-vous pour avoir accès au service") }}</p>
        @endif

        @isset($message)
            @component('sub.modal-visible', ['name' => 'info-modal', 'route' => 'welcome'])
                <h1>{{ $title }}</h1>
                <p>{{ $message }}</p>
            @endcomponent
        @endif

        <h6>Vous trouverez l'identifiant national :</h6>
        <ul>
            <li>sur la première de la ligne de la CPS</li>
            <li>en ajoutant un 8 devant un numéro RPPS, par exemple : 812345678901</li>
            <li>en ajoutant un 0 devant un numéro ADELI, par exemple : 0123456789</li>
        </ul>

        {{ Form::open(['route' => 'ps.getById', 'method' => 'GET']) }}

        <div class="form-group"><!-- IdNat is required -->
            {{ Form::label('id', 'Identifiant :', ['class' => 'control-label']) }}
            {{ Form::text('id', '', array('class'=>'form-control', 'required')) }}
            <p class="text-danger">{{ $errors->first('id') }}</p>
        </div>

        <div class="col text-center">
            {{ Form::submit('Recherche', array('class' => 'btn btn-default btn-outline-primary')) }}
        </div>
        {{ Form::close() }}

    @endcomponent

@endsection
