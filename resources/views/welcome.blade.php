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

                        <a class="nav-link" href={{ route('ps.show', 6) }}>{{ __('>> Page de parrainage') }}</a>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
