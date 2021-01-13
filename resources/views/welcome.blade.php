@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __("Page d'accueil") }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        @if (Auth::user())
                                {{ __("Hello ") }} {{Auth::user()->name}} :)
                        @else
                                {{ __("Welcome, please consider log in") }}
                        @endif

                        {{ Form::open(['route' => 'ps.getById', 'method' => 'GET']) }}

                        <div class="form-group"><!-- IdNat is required -->
                            {{ Form::label('id', 'Identifiant :', ['class' => 'control-label']) }}
                            {{ Form::text('id', '', array('id'=>'id', 'class'=>'form-control', 'required')) }}
                        </div>

                        <div class="col text-center">
                            {{ Form::submit('Submit', array('class' => 'btn btn-primary')) }}
                        </div>
                        {{ Form::close() }}


                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
