@extends('layouts.app')

@section('content')

    @component('sub.card', ['title' => 'Control utilisateur'])

        <div class="col text-center">
            <button onclick="dec('page')" class='btn btn-default btn-outline-secondary file'> < </button>
            <label>
                <input name='maxPages' readonly type="hidden" value="{{ count($data) }}">
                <a href="{{ route('files.publish') }}" class='btn btn-default btn-outline-primary'>Publish</a>
                <input name='page' readonly type="hidden" value="{{ $page }}">
            </label>
            <button onclick="inc('page')" class='btn btn-default btn-outline-secondary'> > </button>
        </div>

        <div class="table-responsive" id="page_content">
            <!-- this part will change -->
            <table class="table table-hover table-condensed table-bordered table-sm">
                @for ($i = 0; $i < $colNum; $i++)
                    <tr>
                        <th>{{ $headers[$i] }}</th>
                        <td>{{ $data[$page][$i] }}</td>
                    </tr>
                @endfor
            </table>
        </div>

    @endcomponent

@endsection

<script type="text/javascript">
    function inc(element) {
        let maxPages = document.querySelector(`[name='maxPages']`);
        let page = document.querySelector(`[name="${element}"]`);
        if (parseInt(page.value) < parseInt(maxPages.value) - 1) {
            page.value = parseInt(page.value) + 1;
        }
        $('#page_content').load('/files/validation/' + parseInt(page.value));
    }

    function dec(element) {
        let page = document.querySelector(`[name="${element}"]`);
        if (parseInt(page.value) > 0) {
            page.value = parseInt(page.value) - 1;
        }
        $('#page_content').load('/files/validation/' + parseInt(page.value));
    }
</script>
