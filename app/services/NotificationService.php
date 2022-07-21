<?php

namespace Services;

use Carbon\Carbon;
use EmailLogController;
use Illuminate\Support\Facades\Auth;
use NotificationController;
use Role;
use User;

class NotificationService {

    public function store($data, $custom_text = '', $send_cob = true) {
        $delay = 0;
        $incrementDelay = 10;
        if(Auth::user()->receive_notify) {
            $jmb_message = !empty($custom_text)? ucwords($data['strata']) ." ". $data['title'] . " has been " . $custom_text : $data['strata'] ." have submitted " . $data['title'];
            $jmb_notify['user_id'] = Auth::user()->id;
            $jmb_notify['company_id'] = Auth::user()->company_id;
            $jmb_notify['file_id'] = $data['file_id'];
            $jmb_notify['module'] = $data['module'];
            $jmb_notify['route'] = $data['route'];
            $jmb_notify['description'] = $jmb_message;
            $jmb_notification = (new NotificationController())->store($jmb_notify);
        }
        if(Auth::user()->receive_mail) {
            $jmb_message = "We are pleased to inform ";
            $jmb_message .= !empty($custom_text)? $data['strata'] ." ". $data['title'] . " has been " . $custom_text : strtolower($data['strata']) ." thanks for submitted " . $data['title'];
            $jmb_email_data['title'] = $data['title'];
            $jmb_email_data['delay'] = $delay;
            $jmb_email_data['user_id'] = Auth::user()->id;
            $jmb_email_data['company_id'] = Auth::user()->company_id;
            $jmb_email_data['file_id'] = $data['file_id'];
            $jmb_email_data['route'] = $data['route'];
            $jmb_email_data['description'] = $jmb_message;
            $jmb_email = (new EmailLogController())->store($jmb_email_data);
        }
        // COB
        if($send_cob) {
            $role_ids = Role::whereIn('name', [Role::COB, Role::COB_BASIC, Role::COB_BASIC_ADMIN, Role::COB_MANAGER, Role::COB_PREMIUM, Role::COB_PREMIUM_ADMIN])
                            ->get();
            $cobs = User::where('company_id', Auth::user()->company_id)
                        ->whereIn('role', array_pluck($role_ids, 'id'))
                        ->where('status', true)
                        ->where('is_deleted', false)
                        ->take(3)
                        ->get();
            foreach($cobs as $cob) {
                if($cob->receive_notify) {
                    $cob_message = $custom_text == 'deleted'? $data['strata_name'] ." ". $data['title'] . " has been " . $custom_text : $data['strata_name'] ." have submitted " . $data['title'];
                    $cob_notify['user_id'] = $cob->id;
                    $cob_notify['company_id'] = $cob->company_id;
                    $cob_notify['file_id'] = $data['file_id'];
                    $cob_notify['module'] = $data['module'];
                    $cob_notify['route'] = $data['cob_route'];
                    $cob_notify['description'] = $cob_message;
                    $cob_notification = (new NotificationController())->store($cob_notify);
                }
                if($cob->receive_mail) {
                    $cob_message = "We are pleased to inform you that ";
                    $cob_message .= $custom_text == 'deleted'? $data['strata_name'] ." ". $data['title'] . " has been " . $custom_text : $data['strata_name'] ." have submitted " . $data['title'];
                    $cob_email_data['title'] =  $data['title'];
                    $cob_email_data['delay'] = $delay;
                    $cob_email_data['user_id'] = $cob->id;
                    $cob_email_data['company_id'] = $cob->company_id;
                    $cob_email_data['file_id'] = $data['file_id'];
                    $cob_email_data['route'] = $data['cob_route'];
                    $cob_email_data['description'] = $cob_message;
                    $cob_email = (new EmailLogController())->store($cob_email_data);
                }
                $delay += $incrementDelay;
            }
        }
    }

    public function sendJMB($data, $custom_text = '') {
        $delay = 0;
        $incrementDelay = 10;
        $role = Role::where('name', Role::JMB)->first();
        $jmbs = User::where('file_id', $data['file_id'])
                    ->where('role', $role->id)
                    ->where('end_date', ">=", Carbon::now()->toDateString())
                    ->where('status', true)
                    // ->take(3)
                    ->get();
        foreach($jmbs as $jmb) {
            if($jmb->receive_notify) {
                $jmb_message = !empty($custom_text)? ucwords($data['strata']) ." ". $data['title'] . " changes has been " . $custom_text : $data['strata'] ." have submitted " . $data['title'];
                $jmb_notify['user_id'] = $jmb->id;
                $jmb_notify['company_id'] = $jmb->company_id;
                $jmb_notify['file_id'] = $data['file_id'];
                $jmb_notify['module'] = $data['module'];
                $jmb_notify['route'] = $data['route'];
                $jmb_notify['description'] = $jmb_message;
                $jmb_notification = (new NotificationController())->store($jmb_notify);
            }
            if($jmb->receive_mail) {
                $jmb_message = "We are pleased to inform ";
                $jmb_message .= !empty($custom_text)? $data['strata'] ." ". $data['title'] . " changes has been " . $custom_text : strtolower($data['strata']) ." thanks for submitted " . $data['title'];
                $jmb_email_data['title'] = $data['title'];
                $jmb_email_data['delay'] = $delay;
                $jmb_email_data['user_id'] = $jmb->id;
                $jmb_email_data['company_id'] = $jmb->company_id;
                $jmb_email_data['file_id'] = $data['file_id'];
                $jmb_email_data['route'] = $data['route'];
                $jmb_email_data['description'] = $jmb_message;
                $jmb_email = (new EmailLogController())->store($jmb_email_data);
            }
            $delay += $incrementDelay;
        }
    }
}