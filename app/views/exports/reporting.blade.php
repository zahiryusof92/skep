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
            <th>Start Year</th>
            <th>End Year</th>
        </tr>
        @foreach($datas as $data)
        @if(empty($data['management'][0]['name']) == false || empty($data['management'][1]['name']) == false || empty($data['management'][2]['name']) == false || empty($data['management'][3]['name']) == false || empty($data['management'][4]['name']) == false || empty($data['ajk']) == false)
            <tr>
                <td>
                    {{$data['cob']}}
                </td> 
                <td>
                    {{$data['file_no']}} 
                </td>
                <td>
                    {{$data['file_name']}} 
                </td>
            </tr>
                @if(empty($data['ajk']) == false )
                    @if(count($data['management']) > count($data['ajk']))
                        @for($i = 0; $i <= count($data['management']); $i++)
                            @if(empty($data['management'][$i]['name']) == false || empty($data['ajk'][$i]['name']) == false)
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>
                                    {{(empty($data['management'][$i]['name']))? '' : $data['management'][$i]['type']}}
                                </td>
                                <td>
                                    {{(empty($data['management'][$i]['name']))? '' : $data['management'][$i]['name']}}
                                </td>
                                <td>
                                    {{(empty($data['management'][$i]['name']))? '' : $data['management'][$i]['address']}}
                                </td>
                                <td>
                                    {{(empty($data['management'][$i]['name']))? '' : $data['management'][$i]['email']}}
                                </td>
                                <td>
                                    {{(empty($data['management'][$i]['name']))? '' : $data['management'][$i]['phone_no']}}
                                </td>
                                @if(empty($data['ajk'][$i]['name']) == false)
                                <td>
                                    {{empty($data['ajk'][$i])? '' : $data['ajk'][$i]['name']}}
                                </td>
                                <td>
                                    {{empty($data['ajk'][$i])? '' : $data['ajk'][$i]['designation']}}
                                </td>
                                <td>
                                    {{empty($data['ajk'][$i])? '' : $data['ajk'][$i]['phone_no']}}
                                </td>
                                <td>
                                    {{empty($data['ajk'][$i])? '' : $data['ajk'][$i]['start_year']}}
                                </td>
                                <td>
                                    {{empty($data['ajk'][$i])? '' : $data['ajk'][$i]['end_year']}}
                                </td>
                                @endif
                            </tr>
                            @endif
                        @endfor
                    @else
                        @for($i = 0; $i <= count($data['ajk']); $i++)
                            @if(empty($data['management'][$i]['name']) == false || empty($data['ajk'][$i]['name']) == false)
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>
                                    {{(empty($data['management'][$i]['name']))? '' : $data['management'][$i]['type']}}
                                </td>
                                <td>
                                    {{(empty($data['management'][$i]['name']))? '' : $data['management'][$i]['name']}}
                                </td>
                                <td>
                                    {{(empty($data['management'][$i]['name']))? '' : $data['management'][$i]['address']}}
                                </td>
                                <td>
                                    {{(empty($data['management'][$i]['name']))? '' : $data['management'][$i]['email']}}
                                </td>
                                <td>
                                    {{(empty($data['management'][$i]['name']))? '' : $data['management'][$i]['phone_no']}}
                                </td>
                                @if(empty($data['ajk'][$i]['name']) == false)
                                <td>
                                    {{empty($data['ajk'][$i])? '' : $data['ajk'][$i]['name']}}
                                </td>
                                <td>
                                    {{empty($data['ajk'][$i])? '' : $data['ajk'][$i]['designation']}}
                                </td>
                                <td>
                                    {{empty($data['ajk'][$i])? '' : $data['ajk'][$i]['phone_no']}}
                                </td>
                                <td>
                                    {{empty($data['ajk'][$i])? '' : $data['ajk'][$i]['start_year']}}
                                </td>
                                <td>
                                    {{empty($data['ajk'][$i])? '' : $data['ajk'][$i]['end_year']}}
                                </td>
                                @endif
                            </tr>
                            @endif
                        @endfor
                    @endif
                @else
                    @for($i = 0; $i <= count($data['management']); $i++)
                        @if(empty($data['management'][$i]['name']) == false || empty($data['ajk'][$i]['name']) == false)
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>
                                {{(empty($data['management'][$i]['name']))? '' : $data['management'][$i]['type']}}
                            </td>
                            <td>
                                {{(empty($data['management'][$i]['name']))? '' : $data['management'][$i]['name']}}
                            </td>
                            <td>
                                {{(empty($data['management'][$i]['name']))? '' : $data['management'][$i]['address']}}
                            </td>
                            <td>
                                {{(empty($data['management'][$i]['name']))? '' : $data['management'][$i]['email']}}
                            </td>
                            <td>
                                {{(empty($data['management'][$i]['name']))? '' : $data['management'][$i]['phone_no']}}
                            </td>
                            @if(empty($data['ajk'][$i]['name']) == false)
                            <td>
                                {{empty($data['ajk'][$i])? '' : $data['ajk'][$i]['name']}}
                            </td>
                            <td>
                                {{empty($data['ajk'][$i])? '' : $data['ajk'][$i]['designation']}}
                            </td>
                            <td>
                                {{empty($data['ajk'][$i])? '' : $data['ajk'][$i]['phone_no']}}
                            </td>
                            <td>
                                {{empty($data['ajk'][$i])? '' : $data['ajk'][$i]['start_year']}}
                            </td>
                            <td>
                                {{empty($data['ajk'][$i])? '' : $data['ajk'][$i]['end_year']}}
                            </td>
                            @endif
                        </tr>
                        @endif
                    @endfor
                @endif  
        @endif
        @endforeach
    </tbody>
</table>