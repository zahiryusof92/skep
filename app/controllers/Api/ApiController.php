<?php

namespace Api;

use BaseController;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use User;
use Files;
use Scoring;
use AuditTrail;

class ApiController extends BaseController {

    public function login() {
        $result = array();

        $username = Request::get('username');
        $password = Request::get('password');

        $auth = Auth::attempt(array(
                    'username' => $username,
                    'password' => $password,
                    'status' => 1,
                    'is_active' => 1,
                    'is_deleted' => 0,
                        ), false);

        if ($auth) {
            $user = User::find(Auth::user()->id);

            if ($user) {
                $result[] = array(
                    'user' => $user->toArray(),
                    'role' => $user->getRole,
                    'cob' => $user->getCOB,
                    'files' => $user->getFile
                );

                $response = array(
                    'error' => false,
                    'message' => 'Login success',
                    'result' => $result,
                );

                return Response::json($response);
            }
        }

        $response = array(
            'error' => true,
            'message' => 'Invalid user',
            'result' => false,
        );

        return Response::json($response);
    }

    public function files() {
        $result = array();

        $user_id = Request::get('user_id');

        $user = User::find($user_id);
        if ($user) {
            $page = (Request::has('page')) ? Request::get('page') : 1;
            $page_per_page = (Request::has('page_per_page')) ? Request::get('page_per_page') : 10;

            if (!Auth::user()->getAdmin()) {
                if (!empty(Auth::user()->file_id)) {
                    $files = Files::where('id', Auth::user()->file_id)
                            ->where('company_id', Auth::user()->company_id)
                            ->where('is_active', '!=', 2)
                            ->where('is_deleted', 0)
                            ->paginate($page_per_page);
                } else {
                    $files = Files::where('company_id', Auth::user()->company_id)
                            ->where('is_active', '!=', 2)
                            ->where('is_deleted', 0)
                            ->paginate($page_per_page);
                }
            } else {
                if (empty(Session::get('admin_cob'))) {
                    $files = Files::where('is_active', '!=', 2)
                            ->where('is_deleted', 0)
                            ->paginate($page_per_page);
                } else {
                    $files = Files::where('company_id', Session::get('admin_cob'))
                            ->where('is_active', '!=', 2)
                            ->where('is_deleted', 0)
                            ->paginate($page_per_page);
                }
            }

            $result = array(
                $files->toArray()
            );

            if ($files) {
                $response = array(
                    'error' => false,
                    'message' => 'Success',
                    'result' => $result,
                );

                return Response::json($response);
            }
        }

        $response = array(
            'error' => true,
            'message' => 'Error',
            'result' => false,
        );

        return Response::json($response);
    }

    public function fileDetails() {
        $result = array();

        $user_id = Request::get('user_id');
        $file_id = Request::get('file_id');

        $user = User::find($user_id);
        if ($user) {
            $file = Files::find($file_id);
            if ($file) {
                $result[] = array(
                    'files' => $file->toArray(),
                    'strata' => $file->strata
                );

                $response = array(
                    'error' => false,
                    'message' => 'Success',
                    'result' => $result,
                );

                return Response::json($response);
            }
        }

        $response = array(
            'error' => true,
            'message' => 'Error',
            'result' => false,
        );

        return Response::json($response);
    }

    public function getRating() {
        $result = array();

        $user_id = Request::get('user_id');
        $file_id = Request::get('file_id');

        $user = User::find($user_id);
        if ($user) {
            $file = Files::find($file_id);
            if ($file) {
                $rating = Scoring::where('file_id', $file->id)->where('is_deleted', 0)->orderBy('created_at', 'desc')->get();
                if (count($rating) > 0) {
                    foreach ($rating as $ratings) {
                        $ratings_A = ((($ratings->score1 + $ratings->score2 + $ratings->score3 + $ratings->score4 + $ratings->score5) / 20) * 25);
                        $ratings_B = ((($ratings->score6 + $ratings->score7 + $ratings->score8 + $ratings->score9 + $ratings->score10) / 20) * 25);
                        $ratings_C = ((($ratings->score11 + $ratings->score12 + $ratings->score13 + $ratings->score14) / 16) * 20);
                        $ratings_D = ((($ratings->score15 + $ratings->score16 + $ratings->score17 + $ratings->score18) / 16) * 20);
                        $ratings_E = ((($ratings->score19 + $ratings->score20 + $ratings->score21) / 12) * 10);

                        $rating = 0;
                        if ($ratings->total_score >= 81) {
                            $rating = 5;
                        } else if ($ratings->total_score >= 61) {
                            $rating = 4;
                        } else if ($ratings->total_score >= 41) {
                            $rating = 3;
                        } else if ($ratings->total_score >= 21) {
                            $rating = 2;
                        } else if ($ratings->total_score >= 1) {
                            $rating = 1;
                        }

                        $result[] = array(
                            'id' => $ratings->id,
                            'date' => (!empty($ratings->date) ? date('d-M-Y', strtotime($ratings->date)) : '<i>(not set)</i>'),
                            'rating_A' => number_format($ratings_A, 2),
                            'rating_B' => number_format($ratings_B, 2),
                            'rating_C' => number_format($ratings_C, 2),
                            'rating_D' => number_format($ratings_D, 2),
                            'rating_E' => number_format($ratings_E, 2),
                            'total_score' => number_format($ratings->total_score, 2),
                            'rating' => $rating,
                        );
                    }

                    $response = array(
                        'error' => false,
                        'message' => 'Success',
                        'result' => $result,
                    );

                    return Response::json($response);
                } else {
                    $response = array(
                        'error' => false,
                        'message' => 'Success',
                        'result' => $result,
                    );

                    return Response::json($response);
                }
            }
        }

        $response = array(
            'error' => true,
            'message' => 'Error',
            'result' => false,
        );

        return Response::json($response);
    }

    public function addRating() {
        $user_id = Request::get('user_id');
        $file_id = Request::get('file_id');

        $user = User::find($user_id);
        if ($user) {
            $file = Files::find($file_id);
            if ($file) {
                $survey = 'strata_management';
                $date = Request::get('date');
                $score1 = Request::get('score1');
                $score2 = Request::get('score2');
                $score3 = Request::get('score3');
                $score4 = Request::get('score4');
                $score5 = Request::get('score5');
                $score6 = Request::get('score6');
                $score7 = Request::get('score7');
                $score8 = Request::get('score8');
                $score9 = Request::get('score9');
                $score10 = Request::get('score10');
                $score11 = Request::get('score11');
                $score12 = Request::get('score12');
                $score13 = Request::get('score13');
                $score14 = Request::get('score14');
                $score15 = Request::get('score15');
                $score16 = Request::get('score16');
                $score17 = Request::get('score17');
                $score18 = Request::get('score18');
                $score19 = Request::get('score19');
                $score20 = Request::get('score20');
                $score21 = Request::get('score21');

                $ratings_A = ((($score1 + $score2 + $score3 + $score4 + $score5) / 20) * 25);
                $ratings_B = ((($score6 + $score7 + $score8 + $score9 + $score10) / 20) * 25);
                $ratings_C = ((($score11 + $score12 + $score13 + $score14) / 16) * 20);
                $ratings_D = ((($score15 + $score16 + $score17 + $score18) / 16) * 20);
                $ratings_E = ((($score19 + $score20 + $score21) / 12) * 10);

                $total_score = $ratings_A + $ratings_B + $ratings_C + $ratings_D + $ratings_E;

                $scoring = new Scoring();
                $scoring->file_id = $file->id;
                $scoring->survey = $survey;
                $scoring->date = $date;
                $scoring->score1 = $score1;
                $scoring->score2 = $score2;
                $scoring->score3 = $score3;
                $scoring->score4 = $score4;
                $scoring->score5 = $score5;
                $scoring->score6 = $score6;
                $scoring->score7 = $score7;
                $scoring->score8 = $score8;
                $scoring->score9 = $score9;
                $scoring->score10 = $score10;
                $scoring->score11 = $score11;
                $scoring->score12 = $score12;
                $scoring->score13 = $score13;
                $scoring->score14 = $score14;
                $scoring->score15 = $score15;
                $scoring->score16 = $score16;
                $scoring->score17 = $score17;
                $scoring->score18 = $score18;
                $scoring->score19 = $score19;
                $scoring->score20 = $score20;
                $scoring->score21 = $score21;
                $scoring->total_score = $total_score;
                $success = $scoring->save();

                if ($success) {
                    // Audit Trail
                    $remarks = 'COB Rating (' . $file->file_no . ') dated ' . date('d/m/Y', strtotime($scoring->date)) . ' has been added.';
                    $auditTrail = new AuditTrail();
                    $auditTrail->module = "COB File";
                    $auditTrail->remarks = $remarks;
                    $auditTrail->audit_by = $user->id;
                    $auditTrail->save();

                    $result = array(
                        'id' => $scoring->id,
                        'date' => (!empty($scoring->date) ? date('d-M-Y', strtotime($scoring->date)) : '<i>(not set)</i>'),
                        'rating_A' => number_format($ratings_A, 2),
                        'rating_B' => number_format($ratings_B, 2),
                        'rating_C' => number_format($ratings_C, 2),
                        'rating_D' => number_format($ratings_D, 2),
                        'rating_E' => number_format($ratings_E, 2),
                        'total_score' => number_format($scoring->total_score, 2)
                    );

                    $response = array(
                        'error' => false,
                        'message' => $remarks,
                        'result' => $result,
                    );

                    return Response::json($response);
                }
            }
        }

        $response = array(
            'error' => true,
            'message' => 'Error',
            'result' => false,
        );

        return Response::json($response);
    }

    public function editRating() {
        $user_id = Request::get('user_id');
        $file_id = Request::get('file_id');
        $id = Request::get('id');

        $user = User::find($user_id);
        if ($user) {
            $file = Files::find($file_id);
            if ($file) {
                $survey = 'strata_management';
                $date = Request::get('date');
                $score1 = Request::get('score1');
                $score2 = Request::get('score2');
                $score3 = Request::get('score3');
                $score4 = Request::get('score4');
                $score5 = Request::get('score5');
                $score6 = Request::get('score6');
                $score7 = Request::get('score7');
                $score8 = Request::get('score8');
                $score9 = Request::get('score9');
                $score10 = Request::get('score10');
                $score11 = Request::get('score11');
                $score12 = Request::get('score12');
                $score13 = Request::get('score13');
                $score14 = Request::get('score14');
                $score15 = Request::get('score15');
                $score16 = Request::get('score16');
                $score17 = Request::get('score17');
                $score18 = Request::get('score18');
                $score19 = Request::get('score19');
                $score20 = Request::get('score20');
                $score21 = Request::get('score21');

                $ratings_A = ((($score1 + $score2 + $score3 + $score4 + $score5) / 20) * 25);
                $ratings_B = ((($score6 + $score7 + $score8 + $score9 + $score10) / 20) * 25);
                $ratings_C = ((($score11 + $score12 + $score13 + $score14) / 16) * 20);
                $ratings_D = ((($score15 + $score16 + $score17 + $score18) / 16) * 20);
                $ratings_E = ((($score19 + $score20 + $score21) / 12) * 10);

                $total_score = $ratings_A + $ratings_B + $ratings_C + $ratings_D + $ratings_E;

                $scoring = Scoring::find($id);
                if ($scoring) {
                    $scoring->file_id = $file->id;
                    $scoring->survey = $survey;
                    $scoring->date = $date;
                    $scoring->score1 = $score1;
                    $scoring->score2 = $score2;
                    $scoring->score3 = $score3;
                    $scoring->score4 = $score4;
                    $scoring->score5 = $score5;
                    $scoring->score6 = $score6;
                    $scoring->score7 = $score7;
                    $scoring->score8 = $score8;
                    $scoring->score9 = $score9;
                    $scoring->score10 = $score10;
                    $scoring->score11 = $score11;
                    $scoring->score12 = $score12;
                    $scoring->score13 = $score13;
                    $scoring->score14 = $score14;
                    $scoring->score15 = $score15;
                    $scoring->score16 = $score16;
                    $scoring->score17 = $score17;
                    $scoring->score18 = $score18;
                    $scoring->score19 = $score19;
                    $scoring->score20 = $score20;
                    $scoring->score21 = $score21;
                    $scoring->total_score = $total_score;
                    $success = $scoring->save();

                    if ($success) {
                        // Audit Trail
                        $remarks = 'COB Rating (' . $file->file_no . ') dated ' . date('d/m/Y', strtotime($scoring->date)) . ' has been updated.';
                        $auditTrail = new AuditTrail();
                        $auditTrail->module = "COB File";
                        $auditTrail->remarks = $remarks;
                        $auditTrail->audit_by = $user->id;
                        $auditTrail->save();

                        $result = array(
                            'id' => $scoring->id,
                            'date' => (!empty($scoring->date) ? date('d-M-Y', strtotime($scoring->date)) : '<i>(not set)</i>'),
                            'rating_A' => number_format($ratings_A, 2),
                            'rating_B' => number_format($ratings_B, 2),
                            'rating_C' => number_format($ratings_C, 2),
                            'rating_D' => number_format($ratings_D, 2),
                            'rating_E' => number_format($ratings_E, 2),
                            'total_score' => number_format($scoring->total_score, 2)
                        );

                        $response = array(
                            'error' => false,
                            'message' => $remarks,
                            'result' => $result,
                        );

                        return Response::json($response);
                    }
                }
            }
        }

        $response = array(
            'error' => true,
            'message' => 'Error',
            'result' => false,
        );

        return Response::json($response);
    }

}
