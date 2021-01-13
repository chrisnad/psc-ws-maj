@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __("Page de parrainage") }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        {{ $ps }}

                        <a class="nav-link" href={{ route('ps.edit', $ps) }}>{{ __('Parrainer ce professionel') }}</a>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
