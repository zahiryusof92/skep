<tr>
    <td colspan="7">
        &nbsp;
    </td>
</tr>
<tr>
    <td colspan="7">
        <strong>
            “SELANGOR MAJU BERSAMA”
            <br />
            “MALAYSIA MADANI”
            <br />
            “BERKHIDMAT UNTUK NEGARA”
        </strong>
    </td>
</tr>
<tr>
    <td colspan="7">
        &nbsp;
    </td>
</tr>
<tr>
    <td colspan="7">
        Saya yang menjalankan amanah,
    </td>
</tr>
<tr>
    <td colspan="7">
        &nbsp; <br />
        &nbsp; <br />
        &nbsp; <br />
    </td>
</tr>
<tr>
    <td colspan="7">
        Unit Pesuruhjaya Bangunan (COB)
    </td>
</tr>
<tr>
    <td colspan="7">
        Majlis Bandaraya Petaling Jaya
    </td>
</tr>
<tr>
    <td colspan="2">
        <small>
            No. Telefon
        </small>
    </td>
    <td style="text-align: center;">
        <small>
            :
        </small>
    </td>
    <td colspan="4">
        <small>
            03-7960 1646/2410
        </small>
    </td>
</tr>
<tr>
    <td colspan="2">
        <small>
            Alamat e-mel
        </small>
    </td>
    <td style="text-align: center;">
        <small>
            :
        </small>
    </td>
    <td colspan="4">
        <small>
            cob@mbpj.gov.my
        </small>
    </td>
</tr>
<tr>
    <td colspan="7">
        &nbsp;
    </td>
</tr>

@if(!empty($sk))
<tr>
    <td colspan="7">
        &nbsp;
    </td>
</tr>
<tr>
    <td>
        s.k:
    </td>
    <td colspan="6">
        <strong>
            {{ (isset($content['management_name']) ? Str::upper($content['management_name']) : '') }}
        </strong>
    </td>
</tr>
<tr>
    <td>
        &nbsp;
    </td>
    <td colspan="6">
        {{ (isset($content['management_address1']) ? $content['management_address1'] : '') }}
    </td>
</tr>
<tr>
    <td>
        &nbsp;
    </td>
    <td colspan="6">
        {{ (isset($content['management_address2']) ? $content['management_address2'] : '') }}
    </td>
</tr>
<tr>
    <td>
        &nbsp;
    </td>
    <td colspan="6">
        {{ (isset($content['management_address3']) ? $content['management_address3'] : '') }}
    </td>
</tr>
@if (isset($content['management_phone']) && !empty($content['management_phone']))
<tr>
    <td>
        &nbsp;
    </td>
    <td colspan="3">
        {{ (isset($content['management_postcode']) ? $content['management_postcode'] : '') }}
        {{ (isset($content['management_city']) ? $content['management_city'] : '') }}
    </td>
    <td style="text-align: right;">
        Tel
    </td>
    <td style="text-align: center;">
        :
    </td>
    <td>
        {{ (isset($content['management_phone']) ? $content['management_phone'] : '') }}
    </td>
</tr>
@else
<tr>
    <td>
        &nbsp;
    </td>
    <td colspan="6">
        {{ (isset($content['management_postcode']) ? $content['management_postcode'] : '') }}
        {{ (isset($content['management_city']) ? $content['management_city'] : '') }}
    </td>
</tr>
@endif
@if (isset($content['management_email']) && !empty($content['management_email']))
<tr>
    <td>
        &nbsp;
    </td>
    <td colspan="3">
        {{ (isset($content['management_state']) ? $content['management_state'] : '') }}
    </td>
    <td style="text-align: right;">
        E-mel
    </td>
    <td style="text-align: center;">
        :
    </td>
    <td>
        &nbsp;
    </td>
</tr>
@else
<tr>
    <td>
        &nbsp;
    </td>
    <td colspan="6">
        {{ (isset($content['management_state']) ? $content['management_state'] : '') }}
    </td>
</tr>
@endif
<tr>
    <td colspan="7">
        &nbsp;
    </td>
</tr>
<tr>
    <td colspan="7">
        &nbsp;
    </td>
</tr>
<tr>
    <td>
        &nbsp;
    </td>
    <td colspan="6">
        Fail JMB/MC
    </td>
</tr>
@endif

<tr>
    <td colspan="7">
        &nbsp; <br />
        &nbsp; <br />
        &nbsp; <br />
    </td>
</tr>
<tr>
    <td colspan="7" style="text-align: center; font-style: italic;">
        Ini adalah cetakan komputer dan tidak perlu ditandatangani
    </td>
</tr>