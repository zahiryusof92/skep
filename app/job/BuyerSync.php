<?php

namespace Job;

use AuditTrail;
use Buyer;
use Files;

class BuyerSync
{
    public function fire($job, $data)
    {
        $item = $data['item'];
        $file_id = $data['file_id'];
        $buyer = Buyer::where('email', $item['owner_email'])->first();
        if(empty($buyer)) {
            $buyer = new Buyer();
            $buyer->file_id = $file_id;
            $buyer->race_id = 0;
            $buyer->nationality_id = 0;
            $buyer->no_petak = null;
            $buyer->no_petak_aksesori = null;
            $buyer->keluasan_lantai_petak = null;
            $buyer->keluasan_lantai_petak_aksesori = null;
            $buyer->jenis_kegunaan = null;
            $buyer->caj_penyelenggaraan = null;
            $buyer->sinking_fund = null;
        }
        $buyer->unit_no = $item['unit_no'];
        $buyer->unit_share = $item['unit_share'];
        $buyer->owner_name = $item['owner_name'];
        $buyer->ic_company_no = $item['owner_ic'];
        $buyer->address = $item['owner_address'];
        $buyer->phone_no = $item['owner_phone'];
        $buyer->email = $item['owner_email'];
        $buyer->remarks = $item['remarks'];
        $buyer->nama2 = (empty($item['secondary_owner_name']))? null : $item['secondary_owner_name'];
        $buyer->ic_no2 = (empty($item['secondary_owner_ic']))? null : $item['secondary_owner_ic'];
        $buyer->alamat_surat_menyurat = (empty($item['secondary_owner_address']))? null : $item['secondary_owner_address'];
        $buyer->proxy_name = (empty($item['proxy_name']))? null : $item['proxy_name'];
        $buyer->proxy_ic = (empty($item['proxy_ic']))? null : $item['proxy_ic'];
        $buyer->proxy_email = (empty($item['proxy_email']))? null : $item['proxy_email'];
        $buyer->proxy_phone = (empty($item['proxy_phone']))? null : $item['proxy_phone'];
        $success = $buyer->save();

        if ($success) {
            # Audit Trail
            $file_name = Files::find($buyer->file_id);
            $remarks = 'COB Owner List (' . $file_name->file_no . ') for Unit' . $buyer->unit_no . ' has been imported.';
            $auditTrail = new AuditTrail();
            $auditTrail->module = "COB File";
            $auditTrail->remarks = $remarks;
            $auditTrail->audit_by = \Auth::user()->id;
            $auditTrail->save();

        }
    }
}