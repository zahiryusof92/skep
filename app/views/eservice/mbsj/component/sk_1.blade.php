<table border="0" cellspacing="0" cellpadding="0" style="width: 100%;">
    <tr>
        <td style="text-align: left; vertical-align: top;">
            s.k:
        </td>
        <td colspan="6">
            {{ isset($content['management_name']) ? $content['management_name'] . '<br/>' : '' }}
            {{ isset($content['management_address1']) && !empty($content['management_address1']) ? $content['management_address1'] . '<br/>' : '' }}
            {{ isset($content['management_address2']) && !empty($content['management_address2']) ? $content['management_address2'] . '<br/>' : '' }}
            {{ isset($content['management_address3']) && !empty($content['management_address3']) ? $content['management_address3'] . '<br/>' : '' }}
            {{ isset($content['management_address4']) && !empty($content['management_address4']) ? $content['management_address4'] . '<br/>' : '' }}
            <span style="text-transform: uppercase; font-weight: bold;">
                {{ isset($content['management_postcode']) ? $content['management_postcode'] : '' }}
                {{ isset($content['management_city']) ? $content['management_city'] . '<br/>' : '' }}
                {{ isset($content['management_state']) ? $content['management_state'] : '' }}
            </span>
        </td>
    </tr>
    <tr>
        <td colspan="7">
            &nbsp;
            <br />
            &nbsp;
            <br />
        </td>
    </tr>
    <tr>
        <td>
            &nbsp;
        </td>
        <td colspan="6">
            Kepada Pemilik/ Penyewa
            <br />
            {{ isset($content['affected_address1']) && !empty($content['affected_address1']) ? $content['affected_address1'] . '<br/>' : '' }}
            {{ isset($content['affected_address2']) && !empty($content['affected_address2']) ? $content['affected_address2'] . '<br/>' : '' }}
            {{ isset($content['affected_address3']) && !empty($content['affected_address3']) ? $content['affected_address3'] . '<br/>' : '' }}
            {{ isset($content['affected_address4']) && !empty($content['affected_address4']) ? $content['affected_address4'] . '<br/>' : '' }}
            <span style="text-transform: uppercase; font-weight: bold;">
                {{ isset($content['affected_postcode']) ? $content['affected_postcode'] : '' }}
                {{ isset($content['affected_city']) ? $content['affected_city'] . '<br/>' : '' }}
                {{ isset($content['affected_state']) ? $content['affected_state'] : '' }}
            </span>
        </td>
    </tr>
    <tr>
        <td colspan="7">
            &nbsp;
            <br />
            &nbsp;
            <br />
        </td>
    </tr>
    <tr>
        <td>
            &nbsp;
        </td>
        <td colspan="6">
            {{ isset($content['owner_name']) ? $content['owner_name'] . '<br/>' : '' }}
            {{ isset($content['owner_address1']) && !empty($content['owner_address1']) ? $content['owner_address1'] . '<br/>' : '' }}
            {{ isset($content['owner_address2']) && !empty($content['owner_address2']) ? $content['owner_address2'] . '<br/>' : '' }}
            {{ isset($content['owner_address3']) && !empty($content['owner_address3']) ? $content['owner_address3'] . '<br/>' : '' }}
            {{ isset($content['owner_address4']) && !empty($content['owner_address4']) ? $content['owner_address4'] . '<br/>' : '' }}
            <span style="text-transform: uppercase; font-weight: bold;">
                {{ isset($content['owner_postcode']) ? $content['owner_postcode'] : '' }}
                {{ isset($content['owner_city']) ? $content['owner_city'] . '<br/>' : '' }}
                {{ isset($content['owner_state']) ? $content['owner_state'] : '' }}
            </span>
        </td>
    </tr>
</table>
