<?php

class Scoring extends Eloquent {

    protected $table = 'scoring_quality_index';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'file_id',
        'strata_id',
    ];
    
    public function file() {
        return $this->belongsTo('Files', 'file_id');
    }

    public static function getAnalyticData($request = []) {
        $active = function ($query) {
            $query->where('scoring_quality_index.is_deleted', 0);
            $query->where('files.is_deleted', 0);
        };

        $query = self::join('files', 'scoring_quality_index.file_id', '=', 'files.id')
                    ->leftjoin('company','files.company_id', '=', 'company.id')
                    ->where($active);
        if(!empty($request['cob'])) {
            $query = $query->where('company.short_name',$request['cob']);
        }
        $items = $query->selectRaw('company.short_name, (sum(score1) + sum(score2) + sum(score3) + sum(score4) + sum(score5)) as total_a,
                                (sum(score6) + sum(score7) + sum(score8) + sum(score9) + sum(score10)) as total_b,
                                (sum(score11) + sum(score12) + sum(score13) + sum(score14)) as total_c,
                                (sum(score15) + sum(score16) + sum(score17) + sum(score18)) as total_d,
                                (sum(score19) + sum(score20) + sum(score21)) as total_e')
                    ->groupBy('company.short_name')
                    ->get();

        $scoring_data = array(
            'total_a' => 0,
            'total_b' => 0,
            'total_c' => 0,
            'total_d' => 0,
            'total_e' => 0,
        );
        $scoring_area_data = [];
        $scoring_type = Config::get('constant.analytic.scoring');
        foreach($items as $item) {
            /** Data */
            $new_area_data = [
                'name' => $item->short_name,  
                'data' => [
                    intval($item->total_a), intval($item->total_b), intval($item->total_c), intval($item->total_d),
                    intval($item->total_e)
                ]
            ];
            array_push($scoring_area_data, $new_area_data);
            $scoring_data['total_a'] += $item->total_a;
            $scoring_data['total_b'] += $item->total_b;
            $scoring_data['total_c'] += $item->total_c;
            $scoring_data['total_d'] += $item->total_d;
            $scoring_data['total_e'] += $item->total_e;
        }
        $rating_pie_data = array(
            ['name' => 'Penubuhan dan Pengurusan', 'slug' => 'penubuhan_dan_pengurusan', 'y' => $scoring_data['total_a']],
            ['name' => 'Kewangan', 'slug' => 'kewangan', 'y' => $scoring_data['total_b']],
            ['name' => 'Pengurusan dan Pentadbiran', 'slug' => 'pengurusan_dan_pentadbiran', 'y' => $scoring_data['total_c']],
            ['name' => 'Kesejahteraan Penduduk', 'slug' => 'kesejahteraan_penduduk', 'y' => $scoring_data['total_d']],
            ['name' => 'Pengurusan Keselamatan dan Risiko', 'slug' => 'pengurusan_keselamatan', 'y' => $scoring_data['total_e']]
        );
        $result = array(
            'rating_pie_data' => $rating_pie_data,
            'scoring_area_name' => $scoring_type['area'],
            'scoring_area_data' => $scoring_area_data,
        );
        
        return $result;
    }

    public static function getRatingData($request = []) {
        $active = function ($query) use($request){
            $query->where('scoring_quality_index.is_deleted', 0);
            $query->where('files.is_deleted', 0);

            if(!empty($request['cob'])) {
                $query->where('company.short_name',$request['cob']);
            }

        };
        $rating_name = 'Star Rating For All Categories';
        $select_score = 'scoring_quanlity_index.total_score as total_score';
        if(!empty($request['score_type'])) {
            if($request['score_type'] == 'penubuhan_dan_pengurusan') {
                $rating_name = 'Penubuhan dan Pengurusan';
                $select_score = '(sum(scoring_quanlity_index.score1) + sum(scoring_quanlity_index.score2) + sum(scoring_quanlity_index.score3) + sum(scoring_quanlity_index.score4) + sum(scoring_quanlity_index.score5)) as total_score';
            }else if($request['score_type'] == 'kewangan') {
                $rating_name = 'Kewangan';
                $select_score = '(sum(scoring_quanlity_index.score6) + sum(scoring_quanlity_index.score7) + sum(scoring_quanlity_index.score8) + sum(scoring_quanlity_index.score9) + sum(scoring_quanlity_index.score10)) as total_score';
            }else if($request['score_type'] == 'pengurusan_dan_pentadbiran') {
                $rating_name = 'Pengurusan dan Pentadbiran';
                $select_score = '(sum(scoring_quanlity_index.score11) + sum(scoring_quanlity_index.score12) + sum(scoring_quanlity_index.score13) + sum(scoring_quanlity_index.score14)) as total_score';
            }else if($request['score_type'] == 'kesejahteraan_penduduk') {
                $rating_name = 'Kesejahteraan Penduduk';
                $select_score = '(sum(scoring_quanlity_index.score15) + sum(scoring_quanlity_index.score16) + sum(scoring_quanlity_index.score17) + sum(scoring_quanlity_index.score18)) as total_score';
            }else if($request['score_type'] == 'pengurusan_keselamatan') {
                $rating_name = 'Pengurusan Keselamatan dan Risiko';
                $select_score = '(sum(scoring_quanlity_index.score19) + sum(scoring_quanlity_index.score20) + sum(scoring_quanlity_index.score21)) as total_score';
            }
        }
        $fiveStar = self::join('files', 'scoring_quality_index.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->select($select_score)
                    ->where($active)
                    ->where('total_score', '>=', 90)
                    ->where('total_score', '<=', 100)
                    ->count();
                    
        $fourStar = self::join('files', 'scoring_quality_index.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->select($select_score)
                    ->where('total_score', '>=', 70)
                    ->where('total_score', '<=', 89)
                    ->where($active)
                    ->count();

        $threeStar = self::join('files', 'scoring_quality_index.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->select($select_score)
                    ->where('total_score', '>=', 50)
                    ->where('total_score', '<=', 69)
                    ->where($active)
                    ->count();

        $twoStar = self::join('files', 'scoring_quality_index.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->select($select_score)
                    ->where('total_score', '>=', 40)
                    ->where('total_score', '<=', 49)
                    ->where($active)
                    ->count();

        $oneStar = self::join('files', 'scoring_quality_index.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->select($select_score)
                    ->where('total_score', '>=', 0)
                    ->where('total_score', '<=', 39)
                    ->where($active)
                    ->count();

        $rating_data = array(
            ['name' => '1 star', 'y' => $oneStar],
            ['name' => '2 Star', 'y' => $twoStar],
            ['name' => '3 Star', 'y' => $threeStar],
            ['name' => '4 Star', 'y' => $fourStar],
            ['name' => '5 Star', 'y' => $fiveStar]
        );
        $result = array(
            'rating_name' => $rating_name,
            'rating_data' => $rating_data,
        );
        
        return $result;
    }

}
