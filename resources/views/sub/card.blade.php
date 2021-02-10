<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-8 col-sm-8">
            <div class="card">
                @isset($title)
                    @include('sub.card-header', array('title' => $title ))
                @endif

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ $slot }}

                </div>
            </div>
        </div>
    </div>
</div>
