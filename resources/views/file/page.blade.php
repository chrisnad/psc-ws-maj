<table class="table table-hover table-condensed table-bordered table-sm">
    @for ($i = 0; $i < $colNum; $i++)
        <tr>
            <th>{{ $headers[$i] }}</th>
            <td>{{ $data[$i] }}</td>
        </tr>
    @endfor
</table>
