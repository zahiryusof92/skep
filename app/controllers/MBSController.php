<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class MBSController extends BaseController {

    public static function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function resetJMB() {
        if(Auth::check() && Auth::user()->getAdmin()) {
            $cob = Company::where('short_name', 'MBS')->first();
            if(!empty($cob)) {
                $role = Role::where('name', 'JMB')->first();
                $users = User::where('role', $role->id)
                            ->where('company_id', $cob->id)
                            ->where('is_deleted',0)
                            ->get();
                            
                $data = [];
                if(count($users) > 0) {
                    foreach($users as $user) {
                        $new_password = self::generateRandomString();
                        $user->update([
                            'password' => Hash::make($new_password)
                        ]);
                        
                        $arr = [
                            'name' => $user->full_name,
                            'email' => $user->email,
                            'strata' => !empty($user->file_id)? Strata::where('file_id', $user->file_id)->first()->name: '-',
                            'username' => $user->username,
                            'password' => $new_password,
                            'phone_no' => !empty($user->phone_no)? $user->phone_no : '-',

                        ];
                        array_push($data, $arr);
                    }
                }
                
                return Excel::create('password_list-'. date('YmdHis'), function($excel) use ($data) {
                    $excel->sheet('mySheet', function($sheet) use ($data)
                    {
                        $sheet->fromArray($data);
                    });
                })->download();

            }
        } else {
            return [
                'error' => true,
                'message' => 'fail'
            ];
        }
    }
}