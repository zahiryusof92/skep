@extends('layout.english_layout.default')

@section('content')

<div class="page-content-inner">
    <section class="panel panel-style">
        <div class="panel-heading">
            <h3>{{$title}}</h3>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <?php $active = 'active'; ?>
                        <?php foreach ($formtype as $ft) { ?>
                            <li class="nav-item">
                                <a class="nav-link <?php echo $active ?> custom-tab" id="tab-{{ $ft->id }}" data-toggle="tab" href="#tab-pane{{ $ft->id }}" role="tab" aria-controls="{{ $ft->id }}" aria-selected="true">{{ $ft->name_en }}</a>
                            </li> 
                            <?php $active = ''; ?>
                        <?php } ?>
                    </ul>
                    
                    <section class="panel panel-pad">
                        <div class="tab-content padding-vertical-20" id="myTabContent">
                            <?php $active_show = 'show active in'; ?>
                            <?php foreach ($formtype as $ft) { ?>
                                <div class="tab-pane fade <?php echo $active_show ?>" id="tab-pane{{ $ft->id }}" role="tabpanel" aria-labelledby="tab-{{ $ft->id }}">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="row">
                                                <table class="table table-bordered">

                                                    <?php
                                                    if (!Auth::user()->getAdmin()) {
                                                        $formFile = AdminForm::where('company_id', Auth::user()->company_id)->where('form_type_id', $ft->id)->where('is_active', 1)->where('is_deleted', 0)->orderBy('sort_no')->get();
                                                    } else {
                                                        if (empty(Session::get('admin_cob'))) {
                                                            $formFile = AdminForm::where('form_type_id', $ft->id)->where('is_active', 1)->where('is_deleted', 0)->orderBy('sort_no')->get();
                                                        } else {
                                                            $formFile = AdminForm::where('company_id', Session::get('admin_cob'))->where('form_type_id', $ft->id)->where('is_active', 1)->where('is_deleted', 0)->orderBy('sort_no')->get();
                                                        }
                                                    }
                                                    ?>

                                                    <tbody>
                                                        <?php foreach ($formFile as $files) { ?>
                                                            <tr>
                                                                <td>
                                                                    <a href="{{ asset($files->file_url) }}" target="_blank">{{ $files->name_en }}</a>
                                                                </td>
                                                            </tr>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div> 
                                </div>
                                <?php $active_show = ''; ?>
                            <?php } ?>
                        </div>    
                    </section>            
                </div>
            </div>
        </div>
    </section>
</div>

@endsection