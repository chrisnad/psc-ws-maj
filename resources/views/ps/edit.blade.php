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

        {{ Form::open(['route' => ['ps.update', $ps['nationalId']], 'method' => 'PUT']) }}

        <div class="form-group"><!-- Phone is required -->
            {{ Form::label('phone', 'Telephone :', array('class' => 'control-label')) }}
            {{ Form::text('phone', null, array('class'=>'form-control', 'required', 'placeholder' => $ps['phone'])) }}
            <p class="text-danger">{{ $errors->first('phone') }}</p>
        </div>

        <div class="form-group"><!-- Email is required -->
            {{ Form::label('email', 'E-Mail Address :', array('class' => 'control-label')) }}
            {{ Form::text('email', null, array('class'=>'form-control', 'required', 'placeholder' => $ps['email'])) }}
            <p class="text-danger">{{ $errors->first('email') }}</p>
        </div>

        <div class="form-group p-1"><!-- Accept conditions is required -->
            {{ Form::checkbox('conditions') }}
            <a class="align-middle small" href="#conditions-modal">
                {{ Form::label('conditions', "J'accept les conditions d'utilisation du service", array('class' => 'control-label')) }}</a>
            <p class="text-danger">{{ $errors->first('conditions') }}</p>
        </div>

        <div class="col text-center">
            {{ Form::submit('Soumettre les changements', array('class' => 'btn btn-default btn-outline-primary')) }}
        </div>
        {{ Form::close() }}

        <p class="pt-4 h5 font-italic">Ce service vous permet de parrainer un professionnel de santé pour
            qu’il puisse activer sa e-CPS. En cliquant sur PARRAINER vous pourrez enregistrer l'adresse
            mail et le numéro de mobile de votre confrère, ils lui serviront à activer sa e-CPS.
            Votre parrainage expirera au bout de 24h, passé ce délai les données seront effacées</p>

        <p class="pt-1 font-italic">* Lors de l'activation de la e-CPS votre confrère recevra un mail puis un SMS</p>

        @component('sub.modal', ['name' => 'conditions-modal'])
            @include('sub.conditions')
        @endcomponent

    @endcomponent

@endsection
