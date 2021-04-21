@extends('layouts.app')

@section('content')

    @component('sub.card', ['title' => 'Dépot du fichier'])

        <input id="colNum" type="hidden" value="{{ $colNum }}">

        <form method="post" action="{{ route('files.upload') }}" enctype="multipart/form-data">
            @csrf
            <div class="col text-center input-group">
                <input id="file-select" type="file" onchange="readFile()" name="files[]" required>
                <input type="hidden" name="MAX_FILE_SIZE" value="4194304">
                <p class="text-danger">{{ $errors->first('file') }}</p>
            </div>
            <div class="col text-center input-group pt-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="inputGroup-sizing-sm">Separateur</span>
                </div>
                <input id="separator" name="separator" required type="text" onchange="setSeparator()" value="|"
                       class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
                <p class="text-danger">{{ $errors->first('separator') }}</p>
            </div>

            <div class="col text-center form-group pt-5" id="validation_button" style="display:none">
                <a href="#user-validation" class="btn btn-default btn-outline-primary"
                   onclick="$('#page_content').slideToggle(); displayPage(1); this.style.display='none'">Validation</a>
            </div>

            <div class="table-responsive col text-center" id="page_content" style="display:none">
                <a onclick="dec()" class='btn btn-default btn-outline-secondary file'> < </a>
                <label>
                    <button type="submit" class="btn btn-default btn-outline-primary">Charger le fichier</button>
                </label>
                <a onclick="inc()" class='btn btn-default btn-outline-secondary'> > </a>
                <!-- this part will change -->
                <table id="validation_table" class="table table-hover table-condensed table-bordered table-sm">
                    @for ($i = 0; $i < $colNum; $i++)
                        <tr>
                            <th>{{ $headers[$i] }}</th>
                            <td></td>
                        </tr>
                    @endfor
                </table>
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

<script type="text/javascript">

    let fileData = [];
    let separator = '|';
    let page = 1;

    function displayPage(page) {
        if (fileData.length<1) {
            return;
        }
        const line = fileData[page].split(separator);
        const colNum = document.getElementById('colNum');
        let validationTable = document.getElementById('validation_table');
        setAttrInTable(validationTable, colNum, line, 0);
    }

    function inc() {
        let maxPages = fileData.length;
        if (page < maxPages-1) {
            page++;
            displayPage(page);
        }
    }

    function dec() {
        if (page > 0) {
            page--;
            displayPage(page);
        }
    }

    function setAttrInTable(validationTable, colNum, line, i) {
        if (i > colNum) {
            console.log(i);
            return;
        }
        validationTable.rows[i].cells[1].innerHTML = line[i];
        return setAttrInTable(validationTable, colNum, line, i+1);
    }

    function setSeparator() {
        separator = document.getElementById('separator').value;
    }

    function readFile() {
        const fileSelected = $('#file-select');
        const reader = new FileReader();
        reader.readAsText(fileSelected[0].files[0]);

        reader.addEventListener('load', (event) => {
            const content = event.target.result;
            fileData = content.split(/[\r\n]+/g); // tolerate both Windows and Unix linebreaks
            $('#validation_button').slideToggle();
        });
    }
</script>
