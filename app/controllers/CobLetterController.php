<?php

use Illuminate\Support\Facades\View;

class CobLetterController extends BaseController {

    public function index() {
        return View::make('cob_letter.mps.surat_bocor_antara_unit_kali_1_mc');
    }

}