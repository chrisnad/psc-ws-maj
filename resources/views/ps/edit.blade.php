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

                        {{ Form::open(['route' => ['ps.update', $ps], 'method' => 'PUT']) }}

                            <div class="form-group"><!-- Phone is required -->
                                {{ Form::label('phone', 'Telephone :', array('class' => 'control-label')) }}
                                {{ Form::text('phone', $ps->phone, array('id'=>'phone-id', 'class'=>'form-control', 'required')) }}
                                <p class="text-danger">{{ $errors->first('phone') }}</p>
                            </div>

                            <div class="form-group"><!-- Email is required -->
                                {{ Form::label('email', 'E-Mail Address :', array('class' => 'control-label')) }}
                                {{ Form::text('email', $ps->email, array('id'=>'email-id', 'class'=>'form-control', 'required')) }}
                                <p class="text-danger">{{ $errors->first('email') }}</p>
                            </div>

                            <div class="form-group"><!-- Accept conditions is required -->
                                {{ Form::checkbox('conditions', 'required') }}
                                {{ Form::label('conditions', "J'accept les conditions d'utilisation du service", array('class' => 'control-label')) }}
                                <p class="text-danger">{{ $errors->first('conditions') }}</p>
                            </div>

                            <div class="col text-center">
                                {{ Form::submit('Soumettre les changements', array('class' => 'btn btn-default btn-outline-primary')) }}
                            </div>
                        {{ Form::close() }}

                        <p class="p-2 h5 font-italic">Ce service vous permet de parrainer un professionnel de santé pour
                            qu’il puisse activer sa e-CPS. En cliquant sur PARRAINER vous pourrez enregistrer l'adresse
                            mail et le numéro de mobile de votre confrère, ils lui serviront à activer sa e-CPS.
                            Votre parrainage expirera au bout de 24h, passé ce délai les données seront effacées</p>

                        <p class="p-1 font-italic">* Lors de l'activation de la e-CPS votre confrère recevra un mail puis un SMS</p>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
