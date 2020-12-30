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
                                $council[] = $cob->name;
                            }
                            ?>
                            {{ implode('<br/>', $council) }}
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
                            @if (!empty($review))
                            @foreach ($review as $rev)
                            <blockquote>
                                <p>{{ $rev['content'] }}</p>
                                <footer><cite>{{ $rev['author'] }}</cite></footer>
                            </blockquote>
                            @endforeach
                            @else
                            <p>-</p>
                            @endif
                        </dd>                        
                    </dl>
                </div>
            </div>

            <hr/>

            <h4>{{ trans('app.directory.vendors.project.title') }}</h4>

            <div class="row">
                <div class="col-lg-12">
                    <table class="table table-hover" id="vendors_detail_table" width="100%">
                        <thead>
                            <tr>
                                <th style="width:30%;">{{ trans('app.directory.vendors.project.name') }}</th>
                                <th style="width:20%;">{{ trans('app.directory.vendors.project.category') }}</th>
                                <th style="width:10%;">{{ trans('app.directory.vendors.project.council') }}</th>
                                <th style="width:30%;">{{ trans('app.directory.vendors.project.address') }}</th>
                                <th style="width:10%;">{{ trans('app.directory.vendors.project.status') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!empty($data))                                                    
                            @foreach ($data as $res)
                            <tr>
                                <td>{{ $res['name'] }}</td>
                                <td>{{ $res['category'] }}</td>
                                <td>{{ $res['council'] }}</td>
                                <td>{{ $res['address'] }}</td>
                                <td>{{ Vendor::status($res['status']) }}</td>
                            </tr> 
                            @endforeach
                            @else
                            <tr>
                                <td valign="top" colspan="5" class="dataTables_empty">No data available in table</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <hr/>

            @if (!empty($data))
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
                        complete: {
                            icon: iconBase + "grn-circle.png"
                        },
                        pending: {
                            icon: iconBase + "red-circle.png"
                        },
                        inprogress: {
                            icon: iconBase + "orange-circle.png"
                        }
                    };

                    const projects = <?php echo json_encode($data) ?>;
                    console.log(projects);

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

<script>
    $("#vendors_detail_table").DataTable({
        lengthMenu: [[5, 10, 50, -1], [5, 10, 50, "All"]],
        pageLength: 10,
        order: [[4, "asc"]],
        responsive: true
    });
</script>
@endsection