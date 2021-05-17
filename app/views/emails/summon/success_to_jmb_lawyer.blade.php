<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <?php
        if ($order->payment_method == Orders::BANK_TRANSFER) {
            $payment_method = 'FPX';
        } else {
            $payment_method = 'Credit Card / Debit Card';
        }
        ?>

        <p>Dear {{ $model->full_name }},</p>

        <div>
            <p>There have new Payment was successful!</p>
            <p>
                We are pleased to inform you that have a new transaction.
            </p>
            <p>
                Reference No : {{ $order->reference_no }}
            </p>
            <p>
                Account Name : {{ $order->user->full_name }}
            </p>
            <p>
                Transaction Date : {{ date('d-m-Y h:i:s A', strtotime($order->created_at)) }}
            </p>
            <p>                
                Transaction Method (RM) : {{ $payment_method }}
            </p>
            <p>
                Total Amount (RM) : {{ $order->amount }}
            </p>
            <br/>
            <p>
                This is a computer generated email. Please do not reply to this email.
            </p>
        </div>
    </body>
</html>