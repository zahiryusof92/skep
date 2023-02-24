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
                    if ($ocr->type == 'minutes_meeting') {
                        $type = 'Minutes of Meetings';
                        $url = $meeting->minutes_meeting_file_url;
                    } else if ($ocr->type == 'copy_of_spa') {
                        $type = 'Copy of JMC SPA';
                        $url = $meeting->jmc_file_url;
                    } else if ($ocr->type == 'attendance') {
                        $type = 'Attendance List';
                        $url = $meeting->attendance_file_url;
                    } else if ($ocr->type == 'audited_financial') {
                        $type = 'Audited Accounts';
                        $url = $meeting->audited_financial_file_url;
                    } else if ($ocr->type == 'eligible_vote') {
                        $type = 'Eligible Voter Listing';
                        $url = $meeting->ligible_vote_url;
                    } else if ($ocr->type == 'house_rules') {
                        $type = 'Approved House Rules';
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

            // return '<pre>' . print_r($results, true) . '</pre>';
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
