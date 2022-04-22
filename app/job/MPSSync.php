<?php

namespace Job;

use Illuminate\Support\Facades\Queue;

class MPSSync
{
    public function fire($job, $data)
    {
        // Process the job...
        if (!empty($data)) {
            $council_code = $data['council_code'];
            $files = $data['files'];

            foreach ($files as $file) {
                if ($file['file_no'] == 'MPS 3/2 - -1406/342(KR)-2') {
                    $file_data = [
                        'council_code' => $council_code,
                        'file' => $file
                    ];

                    Queue::push(FileSync::class, $file_data);
                }
            }
        }

        $job->delete();
    }
}
