<style>
    .header {
        position: absolute;
        top: -1.5cm;
        width: 100%;
        font-size: 12px;
        text-align: left;
    }
</style>

<div class="header">
    <span style="text-transform: uppercase;">{{ !empty($order->bill_no) ? $order->bill_no . '<br />' : '' }}</span>
    <span style="text-transform: uppercase; font-weight: bold;">
        @if ($order->type == 'surat_kebocoran_antara_tingkat')
            ADUAN KEROSAKAN / KEBOCORAN ANTARA TINGKAT
        @elseif ($order->type == 'surat_peringatan_tunggakan_caj_jmb')
            MAKLUMAN SUPAYA MENJELASKAN CAJ PENYENGGARAAN
            {{ isset($content['building_name']) ? $content['building_name'] : '' }}
        @elseif ($order->type == 'surat_peringatan_tunggakan_caj_mc')
            MAKLUMAN SUPAYA MENJELASKAN CAJ PENYENGGARAAN
            {{ isset($content['building_name']) ? $content['building_name'] : '' }}
        @endif
    </span>
</div>
