<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Election</title>

        <!-- Fonts -->
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <link href="https://cdn.jsdelivr.net/npm/@mdi/font@4.x/css/materialdesignicons.min.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Tajawal&amp;display=swap" rel="stylesheet">
        <style>
            [v-cloak] { display: none; }
        </style>
        <style>
            html,
            .v-application{
                font-size: 16px !important;
                font-family: 'Tajawal', sans-serif !important;
            }
            a,a:hover{
                text-decoration: none;
            }
            .nav__tab__item{
                display: block;
                text-decoration: none;
                width: 100%;
                height: 100%;
                line-height: 50px;
            }
            .page-inner{
                padding: 20px;
            }
            .page-heading{
                margin-top: 20px;
                margin-bottom: 10px;
                font-size: 20px;
            }
            .tabs-group{
                margin-bottom: 20px;
            }
            .order-item{
                margin-bottom: 2px;
            }
            .v-application{
                font-family: 'Tajawal', sans-serif;
            }
            .v-tab{
                letter-spacing: 0 !important;
                font-weight: bold;
            }

            .order-item{
                margin-bottom: 15px;
            }
            .order-item-col{}
            .order-item-col ul{
                margin : 0;
                padding: 0;
                list-style: none;
            }
            .order-item-col ul li{
                margin-bottom: 15px;
                font-size: 14px;
                color: #000;
            }

            .table.custom th{
                width: 30%;
                background: #f2f2f2;
                text-align: right;
            }
            .danger-title{
                background: red;
                color: white;
                text-align: center;
                margin: 18px;
                border-radius: 5px;
                font-size: 20px;
                padding: 5px;
            }
            .super-item{
                position: relative;
                margin-bottom: 15px;
            }
            .super-item .overall-link{
                position: absolute;
                top: 0;
                bottom: 0; left: 0;
                right: 0;
            }
            .otherrow{
                background: #d8edf6;
            }
            .text-green{
                color: #44998b;
            }
            .supertable{
                margin: 0;
            }
            .table.supertable td,
            .table.supertable th{
                text-align: right;
            }
        </style>
    </head>
    <body class="antialiased">
        <div id="main-app" v-cloak></div>
        <script src="/js/main-app.js"></script>
    </body>
</html>
