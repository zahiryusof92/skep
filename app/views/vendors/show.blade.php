@extends('layout.english_layout.default')

@section('content')
<div class="page-content-inner">
    <section class="panel panel-with-borders">
        <div class="panel-heading">
            <h3>{{ $title }}</h3>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12">

                    @include('alert.bootbox')

                    <dl class="row">
                        <dt class="col-lg-3">
                            {{ trans('app.directory.vendors.name') }}
                        </dt>
                        <dd class="col-lg-9">
                            {{ $model->name }}
                        </dd>
                        <dt class="col-lg-3">
                            {{ trans('app.directory.vendors.address') }}
                        </dt>
                        <dd class="col-lg-9">
                            {{ $model->address }}
                        </dd>
                        <dt class="col-lg-3">
                            {{ trans('app.directory.vendors.council') }}
                        </dt>
                        <dd class="col-lg-9">
                            <?php
                            $council_id = json_decode($model->company_id);
                            $company = Company::whereIn('id', $council_id)->orderBy('name', 'asc')->get();
                            foreach ($company as $cob) {
                                $council_name[] = $cob->name;
                            }
                            echo implode('<br/>', $council_name);
                            ?>
                        </dd>
                        <dt class="col-lg-3">
                            {{ trans('app.directory.vendors.rating') }}
                        </dt>
                        <dd class="col-lg-9">
                            @if ($model->rating)
                            @for ($x = 1; $x <= $model->rating; $x++)
                            <span class="fa fa-star star-checked"></span>
                            @endfor
                            @endif
                        </dd>
                        <dt class="col-lg-3">
                            {{ trans('app.directory.vendors.remarks') }}
                        </dt>
                        <dd class="col-lg-9">
                            {{ ($model->remarks ? $model->remarks : '-') }}
                        </dd>
                        <dt class="col-lg-3">
                            {{ trans('app.directory.vendors.reviews') }}
                        </dt>
                        <dd class="col-lg-9">
                            @if (count($review) > 0)
                            @foreach ($review as $rev)
                            <blockquote>
                                <p>{{ $rev->description }}</p>
                                <footer><cite>- {{ $rev->user->full_name }} -</cite></footer>
                                <div class="pull-right"><small>{{ $rev->created_at->format('d-F-Y h:i A') }}</small></div>
                            </blockquote>
                            @endforeach
                            @else
                            <p>-</p>
                            @endif

                            <hr/>

                            <form action="{{ url('vendors/review') }}" method="POST">
                                <div class="form-group {{ $errors->has('review') ? 'has-danger' : '' }}">
                                    <textarea class="form-control" name="review" rows="3" placeholder="{{ trans('app.directory.vendors.write_review') }}"></textarea>
                                    @include('alert.feedback', ['field' => 'review'])
                                </div>
                                <div class="pull-right">
                                    <input type="hidden" name="id" value="{{ $model->id }}"/>
                                    <button class="btn btn-sm btn-primary">{{ trans('app.directory.vendors.submit_review') }}  <i class="fa fa-send-o margin-left-5"></i></button>
                                </div>
                            </form>
                        </dd>                        
                    </dl>
                </div>
            </div>

            <hr/>

            <h4>{{ trans('app.directory.vendors.project.title') }}</h4>

            <div class="row margin-bottom-20">
                <div class="col-lg-6">    
                    <button class="btn btn-primary" data-toggle="modal" data-target="#formProjectModal">
                        {{ trans('app.directory.vendors.add_project') }} <i class="fa fa-plus-circle margin-left-5"></i>
                    </button>
                </div>
            </div>

            <div class="modal fade modal" id="formProjectModal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog" role="document">
                    <form>
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">{{ trans('app.directory.vendors.add_project') }}</h5>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label style="color: red; font-style: italic;">* {{ trans('app.forms.mandatory_fields') }}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label><span style="color: red;">*</span> {{ trans('app.directory.vendors.project.name') }}</label>
                                            <input type="text" class="form-control" id="name" name="name" placeholder=""/>
                                            <span class="help-block text-danger" id="name_error" style="display: none;"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label><span style="color: red;">*</span> {{ trans('app.directory.vendors.project.category') }}</label>
                                            <select class="form-control" id="category" name="category">
                                                <option value="">{{ trans('app.forms.please_select') }}</option>
                                                @if(count($category) > 0)
                                                @foreach ($category as $value => $name)
                                                <option value="{{ $value }}">{{ $name }}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                            <span class="help-block text-danger" id="category_error" style="display: none;"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label><span style="color: red;">*</span> {{ trans('app.directory.vendors.project.council') }}</label>
                                            <select class="form-control" id="council" name="council">
                                                <option value="">{{ trans('app.forms.please_select') }}</option>
                                                @if(count($council) > 0)
                                                @foreach ($council as $value => $name)
                                                <option value="{{ $value }}">{{ $name }}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                            <span class="help-block text-danger" id="council_error" style="display: none;"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label><span style="color: red;">*</span> {{ trans('app.directory.vendors.project.address') }}</label>
                                            <textarea class="form-control" id="address" name="address" placeholder="" rows="4"></textarea>
                                            <span class="help-block text-danger" id="address_error" style="display: none;"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label><span style="color: red;">*</span> {{ trans('app.directory.vendors.project.latitude') }}</label>
                                            <input type="text" class="form-control" id="latitude" name="latitude" placeholder=""/>
                                            <span class="help-block text-danger" id="latitude_error" style="display: none;"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label><span style="color: red;">*</span> {{ trans('app.directory.vendors.project.longitude') }}</label>
                                            <input type="text" class="form-control" id="longitude" name="longitude" placeholder=""/>
                                            <span class="help-block text-danger" id="longitude_error" style="display: none;"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label><span style="color: red;">*</span> {{ trans('app.directory.vendors.project.status') }}</label>
                                            <select class="form-control" id="status" name="status">
                                                <option value="">{{ trans('app.forms.please_select') }}</option>
                                                <option value="{{ VendorProject::PENDING }}">{{ trans('app.directory.vendors.project.pending') }}</option>
                                                <option value="{{ VendorProject::INPROGRESS }}">{{ trans('app.directory.vendors.project.inprogress') }}</option>
                                                <option value="{{ VendorProject::COMPLETE }}">{{ trans('app.directory.vendors.project.complete') }}</option>
                                            </select>
                                            <span class="help-block text-danger" id="status_error" style="display: none;"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <img id="loading" style="display:none;" src="{{asset('assets/common/img/input-spinner.gif')}}"/>
                                <button id="submit_button" type="button" class="btn btn-primary" onclick="submitProject()">{{ trans('app.forms.submit') }}</button>
                                <button id="cancel_button" type="button" class="btn btn-danger" data-dismiss="modal">{{ trans('app.forms.cancel') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">                    
                    <table class="table table-hover" id="vendors_detail_table" width="100%">
                        <thead>
                            <tr>
                                <th style="width:20%;">{{ trans('app.directory.vendors.project.name') }}</th>
                                <th style="width:20%;">{{ trans('app.directory.vendors.project.category') }}</th>
                                <th style="width:10%;">{{ trans('app.directory.vendors.project.council') }}</th>
                                <th style="width:30%;">{{ trans('app.directory.vendors.project.address') }}</th>
                                <th style="width:10%;">{{ trans('app.directory.vendors.project.status') }}</th>
                                <th style="width:10%;">{{ trans('app.directory.vendors.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>

            <hr/>

            @if (count($data) > 0)
            <div class="row">
                <div class="col-lg-12">                    
                    <div id="map" style="height: 500px; width: 100%;"></div>                    
                </div>
            </div>

            <script>
                function initMap() {
                    const map = new google.maps.Map(document.getElementById("map"), {
                        center: new google.maps.LatLng(3.2334404, 101.6204472),
                        zoom: 9
                    });

                    const iconBase = "http://maps.google.com/mapfiles/kml/paddle/";

                    const icons = {
                        0: {
                            icon: iconBase + "red-circle.png"
                        },
                        1: {
                            icon: iconBase + "orange-circle.png"
                        },
                        2: {
                            icon: iconBase + "grn-circle.png"
                        }
                    };

                    const projects = <?php echo json_encode($data) ?>;

                    // Create markers.
                    for (let i = 0; i < projects.length; i++) {
                        const marker = new google.maps.Marker({
                            title: projects[i].name,
                            animation: google.maps.Animation.DROP,
                            position: new google.maps.LatLng(projects[i].latitude, projects[i].longitude),
                            icon: icons[projects[i].status].icon,
                            map: map
                        });

                        const contentString = '<div id="content">' +
                                '<div id="siteNotice">' +
                                '</div>' +
                                '<div id="bodyContent">' + projects[i].name +
                                '</div>' +
                                '</div>';

                        const infowindow = new google.maps.InfoWindow({
                            content: contentString
                        });

                        marker.addListener("click", () => {
                            if (infowindow) {
                                infowindow.close();
                            }
                            infowindow.open(map, marker);
                        });
                    }
                }
            </script>
            @endif

        </div>
    </section>
    <!-- End -->
</div>

<div class="modal fade" id="updateStatusForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <form class="form-horizontal">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{ trans('app.forms.update_file_no') }}</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label style="color: red; font-style: italic;">* {{ trans('app.forms.mandatory_fields') }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label><span style="color: red;">*</span> {{ trans('app.directory.vendors.project.status') }}</label>
                                <select class="form-control" id="update_status" name="update_status">
                                    <option value="">{{ trans('app.forms.please_select') }}</option>
                                    <option value="{{ VendorProject::PENDING }}">{{ trans('app.directory.vendors.project.pending') }}</option>
                                    <option value="{{ VendorProject::INPROGRESS }}">{{ trans('app.directory.vendors.project.inprogress') }}</option>
                                    <option value="{{ VendorProject::COMPLETE }}">{{ trans('app.directory.vendors.project.complete') }}</option>
                                </select>
                                <span class="help-block text-danger" id="update_status_error" style="display: none;"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="project_id" id="project_id"/>
                    <img id="loading_status" style="display:none;" src="{{asset('assets/common/img/input-spinner.gif')}}"/>
                    <button id="submit_button_status" type="button" class="btn btn-primary" onclick="submitStatus()">{{ trans('app.forms.submit') }}</button>
                    <button id="cancel_button_status" type="button" class="btn btn-danger" data-dismiss="modal">{{ trans('app.forms.cancel') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#vendors_detail_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('vendors.show', $model->id) }}",
            lengthMenu: [[5, 10, 50, -1], [5, 10, 50, "All"]],
            pageLength: 10,
            order: [[4, "asc"]],
            responsive: true,
            columns: [
                {data: 'name', name: 'name'},
                {data: 'project_category_id', name: 'project_category_id'},
                {data: 'company_id', name: 'company_id'},
                {data: 'address', name: 'address'},
                {data: 'status', name: 'status'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ]
        });
    });

    $('body').on('click', '.confirm-delete', function (e) {
        e.preventDefault();
        let formId = $(this).data('id');

        swal({
            title: "{{ trans('app.confirmation.are_you_sure') }}",
            text: "{{ trans('app.confirmation.no_recover_file') }}",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-warning",
            cancelButtonClass: "btn-default",
            confirmButtonText: "Delete",
            closeOnConfirm: true
        }, function () {
            $('#' + formId).submit();
        });
    });

    function submitProject() {
        $("#loading").css("display", "inline-block");
        $("#submit_button").attr("disabled", "disabled");
        $("#cancel_button").attr("disabled", "disabled");
        $("#name_error").css("display", "none");
        $("#council_error").css("display", "none");
        $("#category_error").css("display", "none");
        $("#address_error").css("display", "none");
        $("#latitude_error").css("display", "none");
        $("#longitude_error").css("display", "none");
        $("#status_error").css("display", "none");

        var name = $("#name").val(),
                council = $("#council").val(),
                category = $("#category").val(),
                address = $("#address").val(),
                latitude = $("#latitude").val(),
                longitude = $("#longitude").val(),
                status = $("#status").val();

        var error = 0;

        if (name.trim() == "") {
            $("#name_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.required", ["attribute"=>"Name"]) }}</span>');
            $("#name_error").css("display", "block");
            $("#name").focus();
            error = 1;
        }
        if (council.trim() == "") {
            $("#council_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.select", ["attribute"=>"Council"]) }}</span>');
            $("#council_error").css("display", "block");
            $("#council").focus();
            error = 1;
        }
        if (category.trim() == "") {
            $("#category_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.select", ["attribute"=>"Category"]) }}</span>');
            $("#category_error").css("display", "block");
            $("#category").focus();
            error = 1;
        }
        if (address.trim() == "") {
            $("#address_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.required", ["attribute"=>"Address"]) }}</span>');
            $("#address_error").css("display", "block");
            $("#address").focus();
            error = 1;
        }
        if (latitude.trim() == "") {
            $("#latitude_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.required", ["attribute"=>"Latitude"]) }}</span>');
            $("#latitude_error").css("display", "block");
            $("#latitude").focus();
            error = 1;
        }
        if (longitude.trim() == "") {
            $("#longitude_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.required", ["attribute"=>"Longitude"]) }}</span>');
            $("#longitude_error").css("display", "block");
            $("#longitude").focus();
            error = 1;
        }
        if (status.trim() == "") {
            $("#status_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.select", ["attribute"=>"Status"]) }}</span>');
            $("#status_error").css("display", "block");
            $("#status").focus();
            error = 1;
        }

        if (error == 0) {
            $.ajax({
                url: "{{ url('vendors/project') }}",
                type: "POST",
                data: {
                    id: "{{ $model->id }}",
                    name: name,
                    council: council,
                    category: category,
                    address: address,
                    latitude: latitude,
                    longitude: longitude,
                    status: status
                },
                success: function (data) {
                    $('#formProjectModal').modal('hide');
                    $("#loading").css("display", "none");
                    $("#submit_button").removeAttr("disabled");
                    $("#cancel_button").removeAttr("disabled");
                    if (data.trim() == "true") {
                        bootbox.alert("<span style='color:green;'>{{ trans('app.successes.saved_successfully') }}</span>", function () {
                            location.reload();
                        });
                    } else {
                        bootbox.alert("<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>");
                    }
                }
            });
        } else {
            $("#loading").css("display", "none");
            $("#submit_button").removeAttr("disabled");
            $("#cancel_button").removeAttr("disabled");
        }
    }

    $(document).on("click", ".modal-update-status", function () {
        $("#update_status_error").css("display", "none");

        var id = $(this).data('id'),
                status = $(this).data('status');

        $(".modal-footer #project_id").val(id);
        $(".modal-body #update_status").val(status);

    });

    function submitStatus() {
        $("#loading_status").css("display", "inline-block");
        $("#submit_button_status").attr("disabled", "disabled");
        $("#cancel_button_status").attr("disabled", "disabled");
        $("#update_status_error").css("display", "none");

        var id = $("#project_id").val(),
                status = $("#update_status").val();

        var error = 0;

        if (status.trim() == "") {
            $("#update_status_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.select", ["attribute"=>"Status"]) }}</span>');
            $("#update_status_error").css("display", "block");
            $("#update_status").focus();
            error = 1;
        }

        if (error == 0) {
            $.ajax({
                url: "{{ url('vendors/project/status') }}",
                type: "POST",
                data: {
                    id: id,
                    status: status
                },
                success: function (data) {
                    $('#updateStatusForm').modal('hide');
                    $("#loading_status").css("display", "none");
                    $("#submit_button_status").removeAttr("disabled");
                    $("#cancel_button_status").removeAttr("disabled");
                    if (data.trim() == "true") {
                        bootbox.alert("<span style='color:green;'>{{ trans('app.successes.updated_successfully') }}</span>", function () {
                            location.reload();
                        });
                    } else {
                        bootbox.alert("<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>");
                    }
                }
            });
        } else {
            $("#loading_status").css("display", "none");
            $("#submit_button_status").removeAttr("disabled");
            $("#cancel_button_status").removeAttr("disabled");
        }
    }
</script>
@endsection