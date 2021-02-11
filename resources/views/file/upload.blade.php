@extends('layouts.app')

@push('extra')

    <!-- Styles -->
    <link href="{{ asset('css/dropzone.css') }}" rel="stylesheet">
    <!-- Scripts -->
    <script src="{{ asset('js/dropzone.js') }}"></script>

@endpush

@section('content')

    @component('sub.card', ['title' => 'Dépot du fichier'])

{{--        <form method="post" action="{{ route('files.upload') }}" enctype="multipart/form-data"--}}
{{--              class="dropzone" id="dropzone">--}}
{{--            @csrf--}}
{{--            <label>--}}
{{--                <input name='separator' type="text">--}}
{{--            </label>--}}
{{--            <button type="submit" id="submit-dropzone" class="btn btn-default btn-outline-primary">Submit</button>--}}
{{--        </form>--}}

        <form method="post" action="{{ route('files.upload') }}" enctype="multipart/form-data">
            @csrf
            <div class="col text-center input-group">
                <input name='file' type='file' required>
                <input type="hidden" name="MAX_FILE_SIZE" value="4194304">
                <p class="text-danger">{{ $errors->first('file') }}</p>
            </div>
            <div class="col text-center input-group pt-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="inputGroup-sizing-sm">Separateur</span>
                </div>
                <input name="separator" required type="text" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
                <p class="text-danger">{{ $errors->first('separator') }}</p>
            </div>
            <div class="col text-center form-group pt-5">
                <button type="submit" class="btn btn-default btn-outline-primary">Charger le fichier</button>
            </div>
        </form>

        @isset($message)
            @component('sub.modal-visible', ['name' => 'info-modal', 'route' => 'files.index'])
                <h1>{{ $title }}</h1>
                <p>{{ $message }}</p>
            @endcomponent
        @endif

    @endcomponent

@endsection
