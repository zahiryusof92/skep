<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <?php
        if ($model->payment_method == Orders::BANK_TRANSFER) {
            $payment_method = 'FPX';
        } else {
            $payment_method = 'Credit Card / Debit Card';
        }
        ?>

        <p>Dear {{ $model->user->full_name }},</p>

        <div>
            <p>Your Payment was successful!</p>
            <p>
                We are pleased to inform you that following online payment via {{ $payment_method }} was successful.
            </p>
            <p>
                Reference No : {{ $model->reference_no }}
            </p>
            <p>
                Account Name : {{ $model->user->full_name }}
            </p>
            <p>
                Transaction Date : {{ date('d-m-Y h:i:s A', strtotime($model->created_at)) }}
            </p>
            <p>                
                Transaction Method (RM) : {{ $payment_method }}
            </p>
            <p>
                Total Amount (RM) : {{ $model->amount }}
            </p>
            <br/>
            <p>
                This is a computer generated email. Please do not reply to this email.
            </p>
        </div>
    </body>
</html>