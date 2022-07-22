
@extends('emails.layouts.app1')

@section('title')
{{ $title }}
@endsection

@section('fullname')
{{ $full_name }}
@endsection

@section('content')

<table style="font-family:'Montserrat',sans-serif;" role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0">
    <tbody>
        <tr>
            <td style="overflow-wrap:break-word;word-break:break-word;padding:25px 10px 0px 30px;font-family:'Montserrat',sans-serif;" align="left">

                <div style="color: #264653; line-height: 140%; text-align: left; word-wrap: break-word;">
                    <p style="font-size: 14px; line-height: 140%;"><span style="font-family: Lato, sans-serif; font-size: 14px; line-height: 22.4px; color: #264653;">{{ $description }}.</span></p>
                </div>

            </td>
        </tr>
    </tbody>
</table>

<table style="font-family:'Montserrat',sans-serif;" role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0">
    <tbody>
        <tr>
            <td style="overflow-wrap:break-word;word-break:break-word;padding:10px 10px 0px 30px;font-family:'Montserrat',sans-serif;" align="left">

                <div style="color: #264653; line-height: 140%; text-align: left; word-wrap: break-word;">
                    <p style="font-size: 14px; line-height: 140%;"><strong>Please check it via the link below:</strong></p>
                </div>

            </td>
        </tr>
    </tbody>
</table>

<table style="font-family:'Montserrat',sans-serif;" role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0">
    <tbody>
        <tr>
            <td style="overflow-wrap:break-word;word-break:break-word;padding:10px 30px;font-family:'Montserrat',sans-serif;" align="left">

                <div style="color: #264653; line-height: 160%; text-align: left; word-wrap: break-word;">
                    <p style="font-size: 14px; line-height: 160%;">
                        <span style="font-family: Lato, sans-serif; font-size: 14px; line-height: 22.4px; color: #264653;">
                            Link : 
                            <a href="{{ $link }}">
                                <u>{{ $link }}</u>
                            </a>
                        </span>
                    </p>
                </div>

            </td>
        </tr>
    </tbody>
</table>
@endsection