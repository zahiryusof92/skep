@extends('layout.english_layout.default')

@section('content')

<?php
$company = Company::find(Auth::user()->company_id);
?>

<div class="page-content-inner">
    <section class="panel panel-with-borders">
        <div class="panel-heading">
            <h3>{{$title}}</h3>
        </div>
        <div class="panel-body">
            <div class="invoice-block">
                <div class="row">
                    <table width="100%">
                        <tr>
                            <td class="text-center">
                                <h4 class="margin-bottom-0">
                                    <img src="{{asset($company->image_url)}}" height="100px;" alt="">
                                </h4> 
                            </td>
                            <td>
                                <h5 class="margin-bottom-10">
                                    {{$company->name}}
                                </h5>
                                <h6 class="margin-bottom-0">
                                    {{$title}}
                                </h6>
                            </td>
                            <td class="text-center">
                                <a href="{{URL::action('PrintController@printRatingSummary')}}" target="_blank">
                                    <button type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Print"><i class="fa fa-print"></i></button>
                                </a>
                            </td>
                        </tr>
                    </table>
                </div>
                <hr/>
                <div class="row">
                    <div class="col-lg-12">
                        <br/>
                        <table border="1" id="rating_summary" width="100%" style="font-size: 12px;">
                            <thead>
                                <tr>
                                    <th rowspan="2" style="width:20%; text-align: center !important; vertical-align:middle !important;">BILANGAN KAWASAN PEMAJUAN</th>
                                    <th rowspan="2" style="width:20%; text-align: center !important; vertical-align:middle !important;">BILANGAN SAMPEL DAN PERATUSAN (%)</th>
                                    <th colspan="6" style="width:60%; text-align: center !important; vertical-align:middle !important;">PENAKRIFAN BINTANG</th>
                                </tr>
                                <tr>
                                    <th style="width:8%; text-align: center !important; vertical-align:middle !important;">5 BINTANG</th>
                                    <th style="width:8%; text-align: center !important; vertical-align:middle !important;">4 BINTANG</th>
                                    <th style="width:8%; text-align: center !important; vertical-align:middle !important;">3 BINTANG</th>
                                    <th style="width:8%; text-align: center !important; vertical-align:middle !important;">2 BINTANG</th>
                                    <th style="width:8%; text-align: center !important; vertical-align:middle !important;">1 BINTANG</th>
                                    <th style="width:10%; text-align: center !important; vertical-align:middle !important;">TIADA MAKLUMAT</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="text-align: center !important; vertical-align:middle !important;">{{$strata}}</td>
                                    @if ($strata == 0)
                                    <td style="text-align: center !important; vertical-align:middle !important;">{{$rating}} (0%)</td>
                                    @else
                                    <td style="text-align: center !important; vertical-align:middle !important;">{{$rating}} ({{number_format((($rating/$strata)*100), 2)}}%)</td>
                                    @endif
                                    <td style="text-align: center !important; vertical-align:middle !important;">{{$fiveStar}}</td>
                                    <td style="text-align: center !important; vertical-align:middle !important;">{{$fourStar}}</td>
                                    <td style="text-align: center !important; vertical-align:middle !important;">{{$threeStar}}</td>
                                    <td style="text-align: center !important; vertical-align:middle !important;">{{$twoStar}}</td>
                                    <td style="text-align: center !important; vertical-align:middle !important;">{{$oneStar}}</td>
                                    <td style="text-align: center !important; vertical-align:middle !important;">{{$strata - $rating}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                @if ($category)
                <br/>                
                <div class="row">
                    <div class="col-lg-12">
                        <table border="1" id="rating_category_summary" width="100%" style="font-size: 12px;">
                            <thead>
                                <tr>
                                    <th rowspan="2" style="width:20%; text-align: center !important; vertical-align:middle !important;">KATEGORI</th>
                                    <th rowspan="2" style="width:20%; text-align: center !important; vertical-align:middle !important;">BILANGAN SAMPEL DAN PERATUSAN (%)</th>
                                    <th colspan="6" style="width:60%; text-align: center !important; vertical-align:middle !important;">PENAKRIFAN BINTANG</th>
                                </tr>
                                <tr>
                                    <th style="width:8%; text-align: center !important; vertical-align:middle !important;">5 BINTANG</th>
                                    <th style="width:8%; text-align: center !important; vertical-align:middle !important;">4 BINTANG</th>
                                    <th style="width:8%; text-align: center !important; vertical-align:middle !important;">3 BINTANG</th>
                                    <th style="width:8%; text-align: center !important; vertical-align:middle !important;">2 BINTANG</th>
                                    <th style="width:8%; text-align: center !important; vertical-align:middle !important;">1 BINTANG</th>
                                    <th style="width:10%; text-align: center !important; vertical-align:middle !important;">TIADA MAKLUMAT</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($category as $cat)
                                <?php
                                $cat_stratas = 0;
                                $cat_ratings = 0;
                                $cat_fiveStars = 0;
                                $cat_fourStars = 0;
                                $cat_threeStars = 0;
                                $cat_twoStars = 0;
                                $cat_oneStars = 0;

                                $category_file = Category::getFiles($cat->id);
                                if ($category_file) {
                                    foreach ($category_file as $cat_file) {
                                        $cat_strata = Strata::where('file_id', $cat_file['id'])->count();
                                        $cat_rating = Scoring::where('file_id', $cat_file['id'])->where('is_deleted', 0)->count();
                                        $cat_fiveStar = Scoring::where('file_id', $cat_file['id'])->where('is_deleted', 0)->where('total_score', '>=', 81)->where('total_score', '<=', 100)->count();
                                        $cat_fourStar = Scoring::where('file_id', $cat_file['id'])->where('is_deleted', 0)->where('total_score', '>=', 61)->where('total_score', '<=', 80)->count();
                                        $cat_threeStar = Scoring::where('file_id', $cat_file['id'])->where('is_deleted', 0)->where('total_score', '>=', 41)->where('total_score', '<=', 60)->count();
                                        $cat_twoStar = Scoring::where('file_id', $cat_file['id'])->where('is_deleted', 0)->where('total_score', '>=', 21)->where('total_score', '<=', 40)->count();
                                        $cat_oneStar = Scoring::where('file_id', $cat_file['id'])->where('is_deleted', 0)->where('total_score', '>=', 1)->where('total_score', '<=', 20)->count();

                                        $cat_stratas += $cat_strata;
                                        $cat_ratings += $cat_rating;
                                        $cat_fiveStars += $cat_fiveStar;
                                        $cat_fourStars += $cat_fourStar;
                                        $cat_threeStars += $cat_threeStar;
                                        $cat_twoStars += $cat_twoStar;
                                        $cat_oneStars += $cat_oneStar;
                                    }
                                }
                                ?>                                
                                <tr>
                                    <td style="text-align: center !important; vertical-align:middle !important;">{{ $cat->description }}</td>
                                    @if ($cat_stratas == 0)
                                    <td style="text-align: center !important; vertical-align:middle !important;">{{$cat_ratings}} (0%)</td>
                                    @else
                                    <td style="text-align: center !important; vertical-align:middle !important;">{{$cat_ratings}} ({{number_format((($cat_ratings/$cat_stratas)*100), 2)}}%)</td>
                                    @endif
                                    <td style="text-align: center !important; vertical-align:middle !important;">{{$cat_fiveStars}}</td>
                                    <td style="text-align: center !important; vertical-align:middle !important;">{{$cat_fourStars}}</td>
                                    <td style="text-align: center !important; vertical-align:middle !important;">{{$cat_threeStars}}</td>
                                    <td style="text-align: center !important; vertical-align:middle !important;">{{$cat_twoStars}}</td>
                                    <td style="text-align: center !important; vertical-align:middle !important;">{{$cat_oneStars}}</td>
                                    <td style="text-align: center !important; vertical-align:middle !important;">{{$cat_stratas - $cat_ratings}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif

                <br/>
                <div class="row">
                    <div class="col-lg-12">
                        <!--<h5 class="text-center">Rumusan Penakrifan Bintang Kawasan Pemajuan</h5>-->
                        <div class="margin-bottom-50">
                            <div id="rating_star"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>        
    </section>    
    <!-- End  -->
</div>

<!-- Page Scripts -->
<script>
    Highcharts.chart('rating_star', {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
            text: 'Rumusan Penakrifan Bintang Kawasan Pemajuan'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '{point.name}<br/><b>{point.percentage:.1f} %</b>'
                },
                showInLegend: true
            }
        },
        series: [{
                name: 'Penakrifan Bintang',
                colorByPoint: true,
                data: [
                    {name: '1 Bintang', y: <?php echo $oneStar; ?>},
                    {name: '2 Bintang', y: <?php echo $twoStar; ?>},
                    {name: '3 Bintang', y: <?php echo $threeStar; ?>},
                    {name: '4 Bintang', y: <?php echo $fourStar; ?>},
                    {name: '5 Bintang', y: <?php echo $fiveStar; ?>}
                ]
            }]
    });
</script>

@stop