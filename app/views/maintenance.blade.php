<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Site Maintenance :: {{ trans('app.app_name') }}</title>

        <style>
            body { 
                text-align: center; 
                padding: 20px; 
            }
            img {
                width: 100%;
            }
            @media (min-width: 768px){
                img {
                    width: auto;
                }
            }
            h1 {
                text-transform: uppercase;
                margin-top: 0px;
                margin-bottom: 0px;
                font-size: 50px;
            }
            body { 
                font: 20px Helvetica, sans-serif;
                color: #333;
            }
        </style>
    </head>

    <body>
        <div>
            <img src="{{ asset('assets/common/img/maintenance.gif') }}">
        </div>           
        <h1>Under Maintenance</h1>            
        <div>
            <p>Sorry for the inconvenience but we&rsquo;re performing some maintenance at the moment.<br/>We&rsquo;ll be back online soon!</p>
            <p>&mdash; {{ trans('app.app_name') }}</p>
        </div>
    </body>

</html>