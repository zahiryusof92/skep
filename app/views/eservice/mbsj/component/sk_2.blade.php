<table border="0" cellspacing="0" cellpadding="0" style="width: 100%;">
    <tr>
        <td style="text-align: left; vertical-align: top;">
            s.k:
        </td>
        <td colspan="6">
            <span style="text-transform: uppercase; font-weight: bold;">
                {{ isset($content['management_name']) ? $content['management_name'] . '<br/>' : '' }}
            </span>
            {{ isset($content['management_address1']) && !empty($content['management_address1']) ? $content['management_address1'] . '<br/>' : '' }}
            {{ isset($content['management_address2']) && !empty($content['management_address2']) ? $content['management_address2'] . '<br/>' : '' }}
            {{ isset($content['management_address3']) && !empty($content['management_address3']) ? $content['management_address3'] . '<br/>' : '' }}
            {{ isset($content['management_address4']) && !empty($content['management_address4']) ? $content['management_address4'] . '<br/>' : '' }}
            {{ isset($content['management_postcode']) ? $content['management_postcode'] : '' }}
            {{ isset($content['management_city']) ? $content['management_city'] . '<br/>' : '' }}
            <span style="text-transform: uppercase; font-weight: bold;">
                {{ isset($content['management_state']) ? $content['management_state'] : '' }}
            </span>
        </td>
    </tr>
</table>
