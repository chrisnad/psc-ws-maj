@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    @include('sub.card-header', array('title' => 'Recherche par identifiant'))

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        @if (Auth::user())
                                <p>{{ __("Hello ") }} {{Auth::user()->name}}</p>
                        @else
                                <p>{{ __("Welcome, please consider log in") }}</p>
                        @endif

                        <hX>Vous trouverez l'identifiant national :</hX>
                        <ul>
                            <li>sur la première de la ligne de la CPS</li>
                            <li>en ajoutant un 8 devant un numéro RPPS, par exemple : 812345678901</li>
                            <li>en ajoutant un 0 devant un numéro ADELI, par exemple : 0123456789</li>
                        </ul>

                        {{ Form::open(['route' => 'ps.getById', 'method' => 'GET']) }}

                        <div class="form-group"><!-- IdNat is required -->
                            {{ Form::label('id', 'Identifiant :', ['class' => 'control-label']) }}
                            {{ Form::text('id', '', array('id'=>'id', 'class'=>'form-control', 'required')) }}
                            <p class="text-danger">{{ $errors->first('id') }}</p>
                        </div>

                        <div class="col text-center">
                            {{ Form::submit('Submit', array('class' => 'btn btn-primary')) }}
                        </div>
                        {{ Form::close() }}

                        <a href="#info-modal">Info</a>

                        @component('sub.modal', ['name' => 'info-modal'])
                            <h1>Pick a Plan</h1>

                            <p>
                                Lorem ipsum...
                            </p>
                        @endcomponent

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
