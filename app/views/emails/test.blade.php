@extends('emails.layouts.app')

@section('title')
Testing E-mail
@endsection

@section('fullname')
User
@endsection

@section('content')
<table style="font-family:'Montserrat',sans-serif;" role="presentation" cellpadding="0" cellspacing="0" width="100%"
    border="0">
    <tbody>
        <tr>
            <td style="overflow-wrap:break-word;word-break:break-word;padding:25px 10px 0px 30px;font-family:'Montserrat',sans-serif;"
                align="left">

                <div style="color: #264653; line-height: 140%; text-align: left; word-wrap: break-word;">
                    <p style="font-size: 14px; line-height: 140%;">
                        <span
                            style="font-family: Lato, sans-serif; font-size: 14px; line-height: 22.4px; color: #264653;">
                            Testing e-mail here.
                        </span>
                    </p>
                </div>

            </td>
        </tr>
    </tbody>
</table>
@endsection