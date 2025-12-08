<style>
    .footer {
        position: absolute;
        bottom: -1.5cm;
        width: 100%;
        font-size: 12px;
        text-align: right;
    }

    .footer-text {
        position: absolute;
        bottom: -1.0cm;
        width: 100%;
        font-size: 12px;
        text-align: center;
        font-style: italic;
    }
</style>

@if (isset($last_page) && $last_page)
    <div class="footer-text">
        Ini adalah cetakan komputer dan tidak perlu ditandatangani
    </div>
@endif

<div class="footer">
    {{ $page_num }}/{{ $page_count }}
</div>
