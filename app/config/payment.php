<?php

return [
    'mbpj' => [
        'client_id' => getenv('mbpj_client_id') ?: 'MBPJ-OAPI-Tester',
        'secret_key' => getenv('mbpj_secret_key') ?: 'qdVqiV7$46W.rbBMk*HS',
        'endpoint_url' => getenv('mbpj_endpoint_url') ?: 'https://api.mbpj.gov.my/eservices/pelbagai/jana_bil?env=dev',
        'kod_jabatan' => getenv('mbpj_kod_jabatan') ?: '171500',
        'kod_hasil' => getenv('mbpj_kod_hasil') ?: '76122',
        'no_kp' => getenv('mbpj_no_kp') ?: '1302755-H',
        'pengguna' => getenv('mbpj_pengguna') ?: '1302755-H',
        'sumber' => getenv('mbpj_sumber') ?: 'eCOB',
        'payment_gateway_url' => getenv('mbpj_payment_gateway_url') ?: 'https://epay.mbpj.gov.my/dev/api/pg.php',
        'payment_gateway_secret_id' => getenv('mbpj_payment_gateway_secret_id') ?: 'BilPelbagai',
        'payment_gateway_secret_key' => getenv('mbpj_payment_gateway_secret_key') ?: 'f245e9db89a9efdbc323c3e75a2329a9',
        'email_cob' => getenv('mbpj_email_cob') ?: 'cob@mbpj.gov.my',
    ]
];
