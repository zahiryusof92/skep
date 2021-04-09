<?php
$company = Company::find(Auth::user()->company_id);
?>

<footer style="margin-top: 15px; padding-left: 0px;">
    <div class="row">
        <div class="col-lg-6">
            <div class="pull-left">
                <a href="https://odesi.tech/terms.html" target="_blank">{{ trans('User Terms & Conditions') }}</a>
            </div>
        </div>
        @if ($company->short_name != 'MBS')
        <div class="col-lg-6">
            <div class="pull-right">
                <strong>&copy; {{ date('Y') }} - {{ trans('ODESI ECOB SDN BHD') }}</strong>. All rights reserved.
            </div>
        </div>
        @endif
    </div>
</footer>