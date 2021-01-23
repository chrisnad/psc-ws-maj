@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    @include('sub.card-header', array('title' => 'Page de parrainage'))

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif


                        <p class="h3 font-weight-bold">Nom : {{ $ps->name }} {{ $ps->lastName }}</p>
                        <p class="h3 font-weight-bold">Id national : {{ $ps->nationalId }}</p>

                            <p class="p-2"></p>

                        <a class="btn btn-default btn-outline-primary center" href="{{ route('ps.edit', $ps) }}">
                            {{ __('Parrainer ce professionel') }}</a>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
