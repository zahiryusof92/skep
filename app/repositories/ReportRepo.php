<?php

namespace Repositories;

use Developer;
use Files;
use Liquidator;
use Illuminate\Support\Str;

class ReportRepo
{

    public function generateReport($request = [])
    {
        $request = $request;
        $models = Files::with([
            'strata.towns', 'strata.categories', 'houseScheme.developers', 'management', 'managementDeveloperLatest',
            'managementJMBLatest', 'managementMCLatest', 'insurance', 'other', 'resident', 'commercial', 'draft'
        ])
            ->file()
            ->join('strata', 'files.id', '=', 'strata.file_id')
            ->join('house_scheme', 'files.id', '=', 'house_scheme.file_id')
            ->join('others_details', 'files.id', '=', 'others_details.file_id')
            ->leftJoin('category', 'category.id', '=', 'strata.category')
            ->leftJoin('developer', 'developer.id', '=', 'house_scheme.developer')
            ->leftJoin('city', 'city.id', '=', 'strata.town')
            ->leftJoin('dun', 'strata.dun', '=', 'dun.id')
            ->leftJoin('area', 'strata.area', '=', 'area.id')
            ->join('management', 'files.id', '=', 'management.file_id')
            ->selectRaw("files.id as id, files.file_no as file_no," .
                "developer.name as developer_name, strata.name as strata_name, strata.area as area, strata.dun as dun," .
                "city.description as city_name, category.description as category_name," .
                "files.is_active as is_active, management.is_jmb as is_jmb, management.is_mc as is_mc," .
                "management.is_agent as is_agent, management.is_developer as is_developer, management.is_others as is_others")
            ->where(function ($query) use ($request) {
                if (!empty($request['file_id']) && $request['file_id'] != 'null') {
                    $file_id = explode(',', $request['file_id']);
                    $query->whereIn('files.id', $file_id);
                }
                if (!empty($request['city']) && $request['city'] != 'null') {
                    $city = explode(',', $request['city']);
                    $query->whereIn('strata.town', $city);
                }
                if (!empty($request['category']) && $request['category'] != 'null') {
                    $category = explode(',', $request['category']);
                    $query->whereIn('strata.category', $category);
                }
                if (!empty($request['developer']) && $request['developer'] != 'null') {
                    $developer = explode(',', $request['developer']);
                    $query->whereIn('house_scheme.developer', $developer);
                }
                if (!empty($request['dun']) && $request['dun'] != 'null') {
                    $dun = explode(',', $request['dun']);
                    $query->whereIn('strata.dun', $dun);
                }
                if (!empty($request['area']) && $request['area'] != 'null') {
                    $area = explode(',', $request['area']);
                    $query->whereIn('strata.area', $area);
                }
                if (!empty($request['management'])) {
                    $management = explode(',', $request['management']);
                    if (in_array('jmb', $management) && in_array('mc', $management) && in_array('agent', $management) && in_array('others', $management)) {
                        $query->where('is_agent', 1)
                            ->orWhere('is_others', 1)
                            ->orWhere('is_mc', 1)
                            ->orWhere('is_jmb', 1);
                    } else if (in_array('mc', $management) && in_array('agent', $management) && in_array('others', $management)) {
                        $query->where('is_agent', 1)
                            ->orWhere('is_others', 1)
                            ->orWhere('is_mc', 1);
                    } else if (in_array('jmb', $management) && in_array('agent', $management) && in_array('others', $management)) {
                        $query->where('is_agent', 1)
                            ->orWhere('is_others', 1)
                            ->orWhere('is_jmb', 1);
                    } else if (in_array('jmb', $management) && in_array('agent', $management) && in_array('mc', $management)) {
                        $query->where('is_agent', 1)
                            ->orWhere('is_mc', 1)
                            ->orWhere('is_jmb', 1);
                    } else if (in_array('agent', $management) && in_array('others', $management)) {
                        $query->where('is_agent', 1)
                            ->orWhere('is_others', 1);
                    } else if (in_array('others', $management) && in_array('mc', $management)) {
                        $query->where('is_others', 1)
                            ->orWhere('is_mc', 1);
                    } else if (in_array('agent', $management) && in_array('mc', $management)) {
                        $query->where('is_agent', 1)
                            ->orWhere('is_mc', 1);
                    } else if (in_array('jmb', $management) && in_array('others', $management)) {
                        $query->where('is_jmb', 1)
                            ->orWhere('is_others', 1);
                    } else if (in_array('jmb', $management) && in_array('agent', $management)) {
                        $query->where('is_jmb', 1)
                            ->orWhere('is_agent', 1);
                    } else if (in_array('jmb', $management) && in_array('mc', $management)) {
                        $query->where('is_jmb', 1)
                            ->orWhere('is_mc', 1);
                    } else if (in_array('others', $management)) {
                        $query->where('is_others', 1)
                            ->where('is_mc', 0);
                    } else if (in_array('agent', $management)) {
                        $query->where('is_agent', 1)
                            ->where('is_mc', 0);
                    } else if (in_array('mc', $management)) {
                        $query->where('is_mc', 1);
                    } else if (in_array('jmb', $management)) {
                        $query->where('is_jmb', 1)
                            ->where('is_mc', 0)
                            ->where('is_agent', false);
                    } else if (in_array('is_developer', $management)) {
                        $query->where('is_developer', true);
                    }
                }
            })
            ->where('files.is_deleted', false)
            ->get();
        return $models;
    }

    public function statisticsReport($request = [])
    {
        $condition = function ($query) use ($request) {
            if (!empty($request['year'])) {
                $query->where('files.year', $request['year']);
            }
        };
        $jumlah_petak = Files::file()
            ->join('strata', 'files.id', '=', 'strata.file_id')
            ->leftJoin('state', 'state.id', '=', 'strata.state')
            ->leftJoin('residential_block', 'files.id', '=', 'residential_block.file_id')
            ->leftJoin('residential_block_extra', 'files.id', '=', 'residential_block_extra.file_id')
            ->leftJoin('commercial_block', 'files.id', '=', 'commercial_block.file_id')
            ->leftJoin('commercial_block_extra', 'files.id', '=', 'commercial_block_extra.file_id')
            ->selectRaw("SUM(residential_block.unit_no) as residential_block_unit, SUM(residential_block_extra.unit_no) as residential_block_extra_unit," .
                "SUM(commercial_block.unit_no) as commercial_block_unit, SUM(commercial_block_extra.unit_no) as commercial_block_extra_unit, state.name as state")
            ->where($condition)
            ->groupBy(['strata.state'])
            ->get();
        $jumlah_skim = Files::file()
            ->join('strata', 'files.id', '=', 'strata.file_id')
            ->leftJoin('state', 'state.id', '=', 'strata.state')
            ->selectRaw("COUNT(strata.id) as total, state.name as state")
            ->where($condition)
            ->groupBy(['strata.state'])
            ->get();
        $jumlah_developer = Developer::join('state', 'state.id', '=', 'developer.state')
            ->where('developer.is_active', true)
            ->where('developer.is_deleted', false)
            ->selectRaw("COUNT(developer.id) as total, state.name as state")
            ->groupBy(['developer.state'])
            ->get();
        $jumlah_developer_skim = Files::file()
            ->join('strata', 'files.id', '=', 'strata.file_id')
            ->join('management', 'files.id', '=', 'management.file_id')
            ->leftJoin('state', 'state.id', '=', 'strata.state')
            ->selectRaw("COUNT(management.id) as total, state.name as state")
            ->where('management.is_developer', true)
            ->where('management.is_jmb', false)
            ->where('management.is_mc', false)
            ->where('management.is_others', false)
            ->where($condition)
            ->groupBy(['strata.state'])
            ->get();
        $jumlah_liquidator = Liquidator::join('state', 'state.id', '=', 'liquidators.city')
            ->where('liquidators.is_active', true)
            ->where('liquidators.is_deleted', false)
            ->selectRaw("COUNT(liquidators.id) as total, state.name as state")
            ->groupBy(['liquidators.city'])
            ->get();
        $jumlah_liquidator_skim = Files::file()
            ->join('strata', 'files.id', '=', 'strata.file_id')
            ->join('house_scheme', 'files.id', '=', 'house_scheme.file_id')
            ->leftJoin('state', 'state.id', '=', 'strata.state')
            ->selectRaw("COUNT(house_scheme.id) as total, state.name as state")
            ->where('house_scheme.is_deleted', 0)
            ->where('house_scheme.is_liquidator', 1)
            ->where($condition)
            ->groupBy(['strata.state'])
            ->get();
        $jumlah_jmb_skim = Files::file()
            ->join('strata', 'files.id', '=', 'strata.file_id')
            ->join('management', 'files.id', '=', 'management.file_id')
            ->leftJoin('state', 'state.id', '=', 'strata.state')
            ->selectRaw("COUNT(management.id) as total, state.name as state")
            ->where('management.is_jmb', true)
            ->where('management.is_mc', false)
            ->where('management.is_others', false)
            ->where($condition)
            ->groupBy(['strata.state'])
            ->get();
        $jumlah_mc_skim = Files::file()
            ->join('strata', 'files.id', '=', 'strata.file_id')
            ->join('management', 'files.id', '=', 'management.file_id')
            ->leftJoin('state', 'state.id', '=', 'strata.state')
            ->selectRaw("COUNT(management.id) as total, state.name as state")
            ->where('management.is_mc', true)
            ->where($condition)
            ->groupBy(['strata.state'])
            ->get();
        $jumlah_no_management_skim = Files::file()
            ->join('strata', 'files.id', '=', 'strata.file_id')
            ->join('management', 'files.id', '=', 'management.file_id')
            ->leftJoin('state', 'state.id', '=', 'strata.state')
            ->selectRaw("COUNT(management.id) as total, state.name as state")
            ->where('management.is_jmb', false)
            ->where('management.is_mc', false)
            ->where('management.is_agent', false)
            ->where('management.is_others', false)
            ->where('management.is_developer', false)
            ->where($condition)
            ->groupBy(['strata.state'])
            ->get();
        $jumlah_under_10_units = Files::file()
            ->join('strata', 'files.id', '=', 'strata.file_id')
            ->leftJoin('state', 'state.id', '=', 'strata.state')
            ->leftJoin('residential_block', 'files.id', '=', 'residential_block.file_id')
            ->selectRaw("COUNT(files.id) as total, state.name as state")
            ->where('residential_block.unit_no', '<', 10)
            ->where($condition)
            ->groupBy(['strata.state'])
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
        foreach ($butirs as $key => $butir) {
            $butir_data = $butir['data'];
            $jumlah_tebrau = $butir_data->filter(function ($item) {
                return Str::lower($item->town) == "tebrau";
            })->first();
            $jumlah_bandar = $butir_data->filter(function ($item) {
                return Str::lower($item->town) == "bandar";
            })->first();
            $jumlah_pulai = $butir_data->filter(function ($item) {
                return Str::lower($item->town) == "pulai";
            })->first();
            $jumlah_plentong = $butir_data->filter(function ($item) {
                return Str::lower($item->town) == "plentong";
            })->first();
            $new_data[$i]['Butir'] = $butir['title'];
            if ($butir['name'] == 'jumlah_petak') {
                $new_data[$i]['TEBRAU'] = $jumlah_tebrau ? (round($jumlah_tebrau->residential_block_unit) + round($jumlah_tebrau->residential_block_extra_unit) + round($jumlah_tebrau->commercial_block_unit) + round($jumlah_tebrau->commercial_block_extra_unit)) : 0;
                $new_data[$i]['BANDAR'] = $jumlah_bandar ? (round($jumlah_bandar->residential_block_unit) + round($jumlah_bandar->residential_block_extra_unit) + round($jumlah_bandar->commercial_block_unit) + round($jumlah_bandar->commercial_block_extra_unit)) : 0;
                $new_data[$i]['PULAI'] = $jumlah_pulai ? (round($jumlah_pulai->residential_block_unit) + round($jumlah_pulai->residential_block_extra_unit) + round($jumlah_pulai->commercial_block_unit) + round($jumlah_pulai->commercial_block_extra_unit)) : 0;
                $new_data[$i]['PLENTONG'] = $jumlah_plentong ? (round($jumlah_plentong->residential_block_unit) + round($jumlah_plentong->residential_block_extra_unit) + round($jumlah_plentong->commercial_block_unit) + round($jumlah_plentong->commercial_block_extra_unit)) : 0;
                $new_data[$i]['Jumlah Keseluruhan'] = ($new_data[$i]['TEBRAU'] + $new_data[$i]['BANDAR'] + $new_data[$i]['PULAI'] + $new_data[$i]['PLENTONG']);
            } else {
                $new_data[$i]['TEBRAU'] = $jumlah_tebrau ? $jumlah_tebrau->total : 0;
                $new_data[$i]['BANDAR'] = $jumlah_bandar ? $jumlah_bandar->total : 0;
                $new_data[$i]['PULAI'] = $jumlah_pulai ? $jumlah_pulai->total : 0;
                $new_data[$i]['PLENTONG'] = $jumlah_plentong ? $jumlah_plentong->total : 0;
                $new_data[$i]['Jumlah Keseluruhan'] = ($new_data[$i]['TEBRAU'] + $new_data[$i]['BANDAR'] + $new_data[$i]['PULAI'] + $new_data[$i]['PLENTONG']);
            }

            $data = array_merge($data, $new_data);
        }

        return $data;
    }
}
