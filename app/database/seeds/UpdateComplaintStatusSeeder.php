<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateComplaintStatusSeeder extends Seeder
{
    public function run()
    {
        $data = [];

        $complaints = Complaint::orderBy('status')->get();
        if (!empty($complaints)) {
            foreach ($complaints as $complaint) {
                if ($complaint->status == 0) {
                    if ($complaint->complaint_status == 'pending_officer') {
                        $status = 1;
                    } else if ($complaint->complaint_status == 'pending_complainant') {
                        $status = 2;
                    } else {
                        $status = 0;
                    }

                    $data[$complaint->id] = [
                        'old_status' => $complaint->status,
                        'status' => $status,
                    ];
                } else if ($complaint->status == 1) {
                    $data[$complaint->id] = [
                        'old_status' => $complaint->status,
                        'status' => 3,
                    ];
                } else if ($complaint->status == 2) {
                    $data[$complaint->id] = [
                        'old_status' => $complaint->status,
                        'status' => 4,
                    ];
                }
            }
        }

        if (!empty($data)) {
            // \Log::debug('Data: ' . print_r($data, true));

            foreach ($data as $id => $item) {
                DB::table('complaints')->where('id', $id)->update(['status' => $item['status']]);
            }
        }

        echo "Update Complaint Status seeded.\n";
    }
}
