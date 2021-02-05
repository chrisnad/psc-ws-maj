@extends('layouts.app')

@push('dropzone')

    <!-- Styles -->
    <link href="{{ asset('css/dropzone.css') }}" rel="stylesheet">
    <!-- Scripts -->
    <script src="{{ asset('js/dropzone.js')}}"></script>

@endpush

@section('content')

    @component('sub.card', ['title' => 'Dépot du fichier'])

{{--        <form method="post" action="{{ route('files.store') }}" enctype="multipart/form-data"--}}
{{--              class="dropzone" id="dropzone">--}}
{{--            @csrf--}}
{{--            <button type="submit" id="submit-dropzone" class="btn btn-default btn-outline-primary">Submit</button>--}}
{{--        </form>--}}

{{--        {{ Form::open(['route'=>'files.parse', 'class'=>'dropzone', 'id'=>'dropzone', 'method'=>'POST',--}}
{{--'files'=>true, 'enctype'=>'multipart/form-data']) }}--}}

{{--        {{ Form::submit('Soumettre les changements', array('class' => 'btn btn-default btn-outline-primary', 'id' => 'submit-dropzone')) }}--}}

{{--        {{ Form::close() }}--}}

        @isset($message)
            @component('sub.modal-visible', ['name' => 'info-modal', 'path' => '/'])
                <h1>{{ $title }}</h1>
                <p>{{ $message }}</p>
            @endcomponent
        @endif

        {{ Form::open(['route'=>'files.parse', 'method'=>'POST', 'files'=>true, 'enctype'=>'multipart/form-data']) }}

        <div><!-- File is required -->
            {{ Form::file('file', array('required')) }}
            <p class="text-danger">{{ $errors->first('file') }}</p>
        </div>

        <div class="form-group"><!-- Separator is required -->
            {{ Form::label('separator', 'Separator :', array('class' => 'control-label')) }}
            {{ Form::text('separator', null, array('required')) }}
            <p class="text-danger">{{ $errors->first('separator') }}</p>
        </div>

        <div class="col text-center">
            {{ Form::submit('Envoyer le fichier', array('class' => 'btn btn-default btn-outline-primary')) }}
        </div>

        {{ Form::close() }}

        <!-- Form -->
{{--        <form method='post' action="{{ route('files.parse') }}" enctype='multipart/form-data' >--}}
{{--            @csrf--}}
{{--            <input type='file' name='file' >--}}
{{--            <label>--}}
{{--                separator--}}
{{--                <input type='text' name='separator' >--}}
{{--            </label>--}}
{{--            <input type='submit' name='submit' value='Upload File'>--}}
{{--        </form>--}}

    @endcomponent

@endsection
