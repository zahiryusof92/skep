<?php

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class SearchController extends BaseController
{
    public function index()
    {
        $request = Request::all();

        $rules = array(
            'keyword' => 'required|string|max:50',
        );

        $validator = Validator::make($request, $rules);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }

        $results = [];

        $keyword = $request['keyword'];

        $ocrs = Ocr::self()->get();
        if ($ocrs) {
            foreach ($ocrs as $ocr) {
                if ($ocr->meetingDocument) {
                    $meeting = $ocr->meetingDocument;

                    $url = '';
                    if ($ocr->type == 'notice_agm_egm') {
                        $type = trans('Salinan Notis Mesyuarat Agung Tahunan AGM/EGM');
                        $url = $meeting->notice_agm_egm_url;
                    } else if ($ocr->type == 'minutes_agm_egm') {
                        $type = trans('Salinan Minit Mesyuarat Agung Tahunan AGM/EGM');
                        $url = $meeting->minutes_agm_egm_url;
                    } else if ($ocr->type == 'minutes_ajk') {
                        $type = trans('Salinan Minit Mesyuarat Jawatankuasa Pengurusan (Perlantikan 3 Jawatan tertinggi)');
                        $url = $meeting->minutes_ajk_url;
                    } else if ($ocr->type == 'ajk_info') {
                        $type = trans('Maklumat Anggota Jawatankuasa Yang Dilantik (Lampiran A)');
                        $url = $meeting->ajk_info_url;
                    } else if ($ocr->type == 'report_audited_financial') {
                        $type = trans('Laporan Akaun Teraudit');
                        $url = $meeting->report_audited_financial_url;
                    } else if ($ocr->type == 'house_rules') {
                        $type = trans('Salinan Kaedah-Kaedah Dalaman Yang Diluluskan (House Rules)');
                        $url = $meeting->house_rules_url;
                    }

                    if (!empty($url)) {
                        $path = $ocr->url;
                        $content = fopen($path, 'r');
                        if ($content) {
                            while (!feof($content)) {
                                $line = preg_replace('/\s*($|\n)/', '\1', fgets($content));
                                if (!empty($line)) {
                                    if (strpos(strtolower($line), strtolower($keyword)) !== FALSE) {
                                        $results[] = array(
                                            'keyword' => $keyword,
                                            'text' => $line,
                                            'type' => $type,
                                            'url' => asset($url)
                                        );
                                    }
                                }
                            }

                            fclose($content);
                        }
                    }
                }
            }
        }

        $viewData = array(
            'title' => trans('app.menus.search'),
            'panel_nav_active' => '',
            'main_nav_active' => '',
            'sub_nav_active' => '',
            'keyword' => $keyword,
            'results' => $results,
            'image' => '',
        );

        return View::make('search.index', $viewData);
    }
}
