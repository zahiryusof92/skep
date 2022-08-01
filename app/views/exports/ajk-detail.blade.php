<table class="table text-center" style="border: 1px solid">
    <thead>
    </thead>
    <tbody>
        <tr>
            <th>COB</th>
            <th>File No</th>
            <th>File Name</th>
            <th>Type</th>
            <th>Name</th>
            <th>Address</th>
            <th>Email</th>
            <th>Phone No.</th>
            <th>AJK Name</th>
            <th>Designation</th>
            <th>Phone Number</th>
            <th>Month</th>
            <th>Start Year</th>
            <th>End Year</th>
        </tr>
        @foreach ($datas as $data)
            <tr>
                <td>
                    {{ $data['cob'] }}
                </td>
                <td>
                    {{ $data['file_no'] }}
                </td>
                <td>
                    {{ $data['file_name'] }}
                </td>
                @if (count($data['management']))
                    <td>{{ $data['management'][0]['type'] }}</td>
                    <td>{{ $data['management'][0]['name'] }}</td>
                    <td>{{ $data['management'][0]['address'] }}</td>
                    <td>{{ $data['management'][0]['email'] }}</td>
                    <td>{{ $data['management'][0]['phone_no'] }}</td>
                @else
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                @endif
                @if (count($data['ajk']))
                    <td>{{ $data['ajk'][0]['name'] }}</td>
                    <td>{{ $data['ajk'][0]['designation'] }}</td>
                    <td>{{ $data['ajk'][0]['phone_no'] }}</td>
                    <td>{{ $data['ajk'][0]['month'] }}</td>
                    <td>{{ $data['ajk'][0]['start_year'] }}</td>
                    <td>{{ $data['ajk'][0]['end_year'] }}</td>
                @else
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                @endif
            </tr>
            <?php $ajk_i = 1; ?>
            @if (count($data['management']))
                @for ($i = 1; $i < count($data['management']); $i++)
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>{{ $data['management'][$i]['type'] }}</td>
                        <td>{{ $data['management'][$i]['name'] }}</td>
                        <td>{{ $data['management'][$i]['address'] }}</td>
                        <td>{{ $data['management'][$i]['email'] }}</td>
                        <td>{{ $data['management'][$i]['phone_no'] }}</td>
                        @if (!empty($data['ajk'][$i]))
                            <td>{{ $data['ajk'][$i]['name'] }}</td>
                            <td>{{ $data['ajk'][$i]['designation'] }}</td>
                            <td>{{ $data['ajk'][$i]['phone_no'] }}</td>
                            <td>{{ $data['ajk'][$i]['month'] }}</td>
                            <td>{{ $data['ajk'][$i]['start_year'] }}</td>
                            <td>{{ $data['ajk'][$i]['end_year'] }}</td>
                        @else
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        @endif
                    </tr>
                    <?php $ajk_i++; ?>
                @endfor
            @endif
            @if (!empty($data['ajk'][$ajk_i]))
                @for ($i = $ajk_i; $i < count($data['ajk']); $i++)
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>{{ $data['ajk'][$i]['name'] }}</td>
                        <td>{{ $data['ajk'][$i]['designation'] }}</td>
                        <td>{{ $data['ajk'][$i]['phone_no'] }}</td>
                        <td>{{ $data['ajk'][$i]['month'] }}</td>
                        <td>{{ $data['ajk'][$i]['start_year'] }}</td>
                        <td>{{ $data['ajk'][$i]['end_year'] }}</td>
                    </tr>
                @endfor
            @endif
        @endforeach
    </tbody>
</table>
