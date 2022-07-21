@foreach($datas as $data)
<tr>
    <td>{{ $data['Butir'] }}</td>
    <td class="text-center">{{ $data['TEBRAU'] }}</td>
    <td class="text-center">{{ $data['BANDAR'] }}</td>
    <td class="text-center">{{ $data['PULAI'] }}</td>
    <td class="text-center">{{ $data['PLENTONG'] }}</td>
    <td class="text-center">{{ ($data['TEBRAU'] + $data['BANDAR'] + $data['PULAI'] + $data['PLENTONG']) }}</td>
</tr>
@endforeach