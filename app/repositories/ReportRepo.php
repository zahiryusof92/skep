<?php

namespace Repositories;

use Developer;
use Files;
use Liquidator;
use Illuminate\Support\Str;

class ReportRepo {

    public function statisticsReport($request = []) {
        $condition = function($query) use($request){
            if(!empty($request['year'])) {
                $query->where('files.year', $request['year']);
            }
        };        
        $jumlah_petak = Files::file()
                            ->join('strata', 'files.id', '=', 'strata.file_id')
                            ->leftJoin('city', 'city.id', '=', 'strata.town')
                            ->leftJoin('residential_block', 'files.id', '=', 'residential_block.file_id')
                            ->leftJoin('residential_block_extra', 'files.id', '=', 'residential_block_extra.file_id')
                            ->leftJoin('commercial_block', 'files.id', '=', 'commercial_block.file_id')
                            ->leftJoin('commercial_block_extra', 'files.id', '=', 'commercial_block_extra.file_id')
                            ->selectRaw("SUM(residential_block.unit_no) as residential_block_unit, SUM(residential_block_extra.unit_no) as residential_block_extra_unit,".
                            "SUM(commercial_block.unit_no) as commercial_block_unit, SUM(commercial_block_extra.unit_no) as commercial_block_extra_unit, city.description as town")
                            ->where($condition)
                            ->groupBy(['strata.town'])
                            ->get();
        $jumlah_skim = Files::file()
                            ->join('strata', 'files.id', '=', 'strata.file_id')
                            ->leftJoin('city', 'city.id', '=', 'strata.town')
                            ->selectRaw("COUNT(strata.id) as total, city.description as town")
                            ->where($condition)
                            ->groupBy(['strata.town'])
                            ->get();
        $jumlah_developer = Developer::join('city', 'city.id', '=', 'developer.city')
                                    ->where('developer.is_active', true)
                                    ->where('developer.is_deleted', false)
                                    ->selectRaw("COUNT(developer.id) as total, city.description as town")
                                    ->groupBy(['developer.city'])
                                    ->get();
        $jumlah_developer_skim = Files::file()
                            ->join('strata', 'files.id', '=', 'strata.file_id')
                            ->join('management', 'files.id', '=', 'management.file_id')
                            ->leftJoin('city', 'city.id', '=', 'strata.town')
                            ->selectRaw("COUNT(management.id) as total, city.description as town")
                            ->where('management.is_developer', true)
                            ->where('management.is_jmb', false)
                            ->where('management.is_mc', false)
                            ->where('management.is_others', false)
                            ->where($condition)
                            ->groupBy(['strata.town'])
                            ->get();
        $jumlah_liquidator = Liquidator::join('city', 'city.id', '=', 'liquidators.city')
                                    ->where('liquidators.is_active', true)
                                    ->where('liquidators.is_deleted', false)
                                    ->selectRaw("COUNT(liquidators.id) as total, city.description as town")
                                    ->groupBy(['liquidators.city'])
                                    ->get();
        $jumlah_liquidator_skim = Files::file()
                            ->join('strata', 'files.id', '=', 'strata.file_id')
                            ->join('house_scheme', 'files.id', '=', 'house_scheme.file_id')
                            ->leftJoin('city', 'city.id', '=', 'strata.town')
                            ->selectRaw("COUNT(house_scheme.id) as total, city.description as town")
                            ->where('house_scheme.is_deleted', 0)
                            ->where('house_scheme.is_liquidator', 1)
                            ->where($condition)
                            ->groupBy(['strata.town'])
                            ->get();
        $jumlah_jmb_skim = Files::file()
                            ->join('strata', 'files.id', '=', 'strata.file_id')
                            ->join('management', 'files.id', '=', 'management.file_id')
                            ->leftJoin('city', 'city.id', '=', 'strata.town')
                            ->selectRaw("COUNT(management.id) as total, city.description as town")
                            ->where('management.is_jmb', true)
                            ->where('management.is_mc', false)
                            ->where('management.is_others', false)
                            ->where($condition)
                            ->groupBy(['strata.town'])
                            ->get();
        $jumlah_mc_skim = Files::file()
                            ->join('strata', 'files.id', '=', 'strata.file_id')
                            ->join('management', 'files.id', '=', 'management.file_id')
                            ->leftJoin('city', 'city.id', '=', 'strata.town')
                            ->selectRaw("COUNT(management.id) as total, city.description as town")
                            ->where('management.is_mc', true)
                            ->where($condition)
                            ->groupBy(['strata.town'])
                            ->get();
        $jumlah_no_management_skim = Files::file()
                            ->join('strata', 'files.id', '=', 'strata.file_id')
                            ->join('management', 'files.id', '=', 'management.file_id')
                            ->leftJoin('city', 'city.id', '=', 'strata.town')
                            ->selectRaw("COUNT(management.id) as total, city.description as town")
                            ->where('management.is_jmb', false)
                            ->where('management.is_mc', false)
                            ->where('management.is_agent', false)
                            ->where('management.is_others', false)
                            ->where('management.is_developer', false)
                            ->where($condition)
                            ->groupBy(['strata.town'])
                            ->get();
        $jumlah_under_10_units = Files::file()
                            ->join('strata', 'files.id', '=', 'strata.file_id')
                            ->leftJoin('city', 'city.id', '=', 'strata.town')
                            ->leftJoin('residential_block', 'files.id', '=', 'residential_block.file_id')
                            ->selectRaw("COUNT(files.id) as total, city.description as town")
                            ->where('residential_block.unit_no', '<', 10)
                            ->where($condition)
                            ->groupBy(['strata.town'])
                            ->get();
                            
        $data = [];
        $i = 0;
        $butirs = [
            [
                'name' => 'jumlah_petak',
                'title' => 'Jumlah Petak',
                'data' => $jumlah_petak
            ],
            [
                'name' => 'jumlah_skim',
                'title' => 'Jumlah Skim',
                'data' => $jumlah_skim
            ],
            [
                'name' => 'jumlah_pemaju',
                'title' => 'Jumlah Pemaju',
                'data' => $jumlah_developer
            ],
            [
                'name' => 'jumlah_skim_pemaju',
                'title' => 'Jumlah Skim Pemaju',
                'data' => $jumlah_developer_skim
            ],
            // [
            //     'name' => 'jumlah_jmb',
            //     'title' => 'Jumlah JMB',
            // ],
            [
                'name' => 'jumlah_skim_jmb',
                'title' => 'Jumlah Skim JMB',
                'data' => $jumlah_jmb_skim
            ],
            // [
            //     'name' => 'jumlah_mc',
            //     'title' => 'Jumlah MC',
            // ],
            [
                'name' => 'jumlah_skim_mc',
                'title' => 'Jumlah Skim MC',
                'data' => $jumlah_mc_skim
            ],
            [
                'name' => 'jumlah_liquidator',
                'title' => 'Jumlah Liquidator',
                'data' => $jumlah_liquidator
            ],
            [
                'name' => 'jumlah_skim_liquidator',
                'title' => 'Jumlah Skim Liquidator',
                'data' => $jumlah_liquidator_skim
            ],
            // [
            //     'name' => 'jumlah_terbengkalai',
            //     'title' => 'Jumlah Terbengkalai',
            //     'data' => $jumlah_no_management_skim
            // ],
            [
                'name' => 'jumlah_skim_terbengkalai',
                'title' => 'Jumlah Skim Terbengkalai',
                'data' => $jumlah_no_management_skim
            ],
            [
                'name' => 'jumlah_strata_under_10_unit',
                'title' => 'Jumlah Skim Strata < 10 Unit',
                'data' => $jumlah_under_10_units
            ],
            // [
            //     'name' => 'jumlah_skim_strata_under_10_unit',
            //     'title' => 'Jumlah Skim',
            // ],
        ];
        foreach($butirs as $key => $butir) {      
            $butir_data = $butir['data'];
            $jumlah_tebrau = $butir_data->filter(function($item) {
                return Str::lower($item->town) == "tebrau";
            })->first();
            $jumlah_bandar = $butir_data->filter(function($item) {
                return Str::lower($item->town) == "bandar";
            })->first();           
            $jumlah_pulai = $butir_data->filter(function($item) {
                return Str::lower($item->town) == "pulai";
            })->first();           
            $jumlah_plentong = $butir_data->filter(function($item) {
                return Str::lower($item->town) == "plentong";
            })->first();           
            $new_data[$i]['Butir'] = $butir['title'];
            if($butir['name'] == 'jumlah_petak') {
                $new_data[$i]['TEBRAU'] = $jumlah_tebrau? (round($jumlah_tebrau->residential_block_unit) + round($jumlah_tebrau->residential_block_extra_unit) + round($jumlah_tebrau->commercial_block_unit) + round($jumlah_tebrau->commercial_block_extra_unit)) : 0;
                $new_data[$i]['BANDAR'] = $jumlah_bandar? (round($jumlah_bandar->residential_block_unit) + round($jumlah_bandar->residential_block_extra_unit) + round($jumlah_bandar->commercial_block_unit) + round($jumlah_bandar->commercial_block_extra_unit)) : 0;
                $new_data[$i]['PULAI'] = $jumlah_pulai? (round($jumlah_pulai->residential_block_unit) + round($jumlah_pulai->residential_block_extra_unit) + round($jumlah_pulai->commercial_block_unit) + round($jumlah_pulai->commercial_block_extra_unit)) : 0;
                $new_data[$i]['PLENTONG'] = $jumlah_plentong? (round($jumlah_plentong->residential_block_unit) + round($jumlah_plentong->residential_block_extra_unit) + round($jumlah_plentong->commercial_block_unit) + round($jumlah_plentong->commercial_block_extra_unit)) : 0;
                $new_data[$i]['Jumlah Keseluruhan'] = ($new_data[$i]['TEBRAU'] + $new_data[$i]['BANDAR'] + $new_data[$i]['PULAI'] + $new_data[$i]['PLENTONG']);
            } else {
                $new_data[$i]['TEBRAU'] = $jumlah_tebrau? $jumlah_tebrau->total : 0;
                $new_data[$i]['BANDAR'] = $jumlah_bandar? $jumlah_bandar->total : 0;
                $new_data[$i]['PULAI'] = $jumlah_pulai? $jumlah_pulai->total : 0;
                $new_data[$i]['PLENTONG'] = $jumlah_plentong? $jumlah_plentong->total : 0;
                $new_data[$i]['Jumlah Keseluruhan'] = ($new_data[$i]['TEBRAU'] + $new_data[$i]['BANDAR'] + $new_data[$i]['PULAI'] + $new_data[$i]['PLENTONG']);
            }
            
            $data = array_merge($data, $new_data);
        }

        return $data;
    }
}