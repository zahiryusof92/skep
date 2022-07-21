<?php

use Carbon\Carbon;
use Helper\Helper;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use yajra\Datatables\Facades\Datatables;

class EmailLogController extends BaseController {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        $disallow = Helper::isAllow(0, 0, !AccessGroup::hasAccessModule("Email Log"));
        if (Request::ajax()) {
            $model = EmailLog::with(['user'])
                                    ->self();
            return Datatables::of($model)
                            ->editColumn('user_id', function($model) {
                                return $model->fullname;
                            })
                            ->editColumn('file_no', function($model) {
                                return "<a style='text-decoration:underline;' href='" . $model->route . "'>" . $model->file->file_no . "</a>";
                            })
                            ->editColumn('created_at', function($model) {
                                $created_at = ($model->created_at ? $model->created_at->format('d-M-Y') : '');

                                return $created_at;
                            })
                            ->make(true);
        }
        $viewData = array(
            'title' => trans('app.menus.cob.email_log_list'),
            'panel_nav_active' => 'reporting_panel',
            'main_nav_active' => 'reporting_main',
            'sub_nav_active' => 'email_log_list',
            'image' => ''
        );

        return View::make('emails.log', $viewData);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store($data) {
        $rules = array(
            'user_id' => 'required',
            'company_id' => 'required',
            'file_id' => 'required',
            'route' => 'required',
            'description' => 'required',
        );
        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            false;
        }

        $email_log = EmailLog::create([
            'user_id' => $data['user_id'],
            'company_id' => $data['company_id'],
            'file_id' => $data['file_id'],
            'route' => $data['route'],
            'description' => $data['description'],
        ]);
        
        if ($email_log) {                                                                                  
            // if(getenv('MAIL_HOST', false) == 'smtp.mailtrap.io'){
            //     sleep(1); //use usleep(500000) for half a second or less
            // }        
            Mail::later(Carbon::now()->addSeconds($data['delay']), 'emails.submission.new', array('title' => $email_log->title, 'full_name' => $email_log->user->full_name, 'description' => $email_log->description, 'link' => $email_log->route), function ($message) use ($email_log, $data) {
                $message->to($email_log->user->email, $email_log->user->full_name)->subject($data['title']);
            });
            /*
            * add audit trail
            */
            $audit_name = "$email_log->description";
            $remarks = $audit_name;
            $this->addAudit($email_log->file_id, "Email Log", $remarks);
            
            return true;
        }
    }
}