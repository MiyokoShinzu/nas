<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NAS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/v/dt/jszip-3.10.1/dt-2.0.8/af-2.7.0/b-3.0.2/b-colvis-3.0.2/b-html5-3.0.2/b-print-3.0.2/cr-2.0.3/date-1.5.2/fc-5.0.1/fh-4.0.1/kt-2.12.1/r-3.0.2/rg-1.5.0/rr-1.5.0/sc-2.4.3/sb-1.7.1/sp-2.3.1/sl-2.0.3/sr-1.4.1/datatables.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" />
    <!-- <link rel="shortcut icon" href="icon.png" type="image/x-icon"> -->
    <style>
        * {
            font-family: Arial, Helvetica, sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body{
            overflow-x: hidden;
        }

        .article-intro::first-letter {
            font-size: 2.5em;
            font-weight: bolder;
            color: var(--bs-primary);
            font-family: cursive;
        }

        .navbar {
            background-color: transparent;
            transition: background-color 0.3s ease;
            padding: 1em;
            z-index: 100;
        }

        .navbar.scrolled {
            background: rgb(235, 240, 246);
        }

        .navbar-brand {
            font-weight: bold;
            font-size: 1em;
            color: #000;
        }

        .navbar-nav .nav-link {
            color: #000000 !important;
            margin-right: 1em;
            font-size: 1em;
            position: relative;
            transition: color 0.3s ease;
            font-weight: bold;
        }

        .navbar-nav .nav-link::after {
            content: "";
            position: absolute;
            left: 0;
            bottom: 0;
            width: 0%;
            height: 2px;
            background-color: #1E90FF;
            transition: all 0.2s ease;
        }

        .navbar-nav .nav-link:hover::after {
            width: 100%;
        }

        .navbar-nav .nav-link:hover {
            color: #1E90FF !important;
            transform: translateY(2px);

        }

        .navbar-nav .nav-link.active::after {
            width: 100%;
            background-color: #1E90FF;
        }

        .navbar-toggler {}

        .navbar-toggler-icon {
            background-image: url("https://static.thenounproject.com/png/3889194-200.png");
        }

        .article-title {
            border-bottom: 2px solid var(--bs-primary);
            margin-bottom: 0px;
            padding: 5px;
        }
        .bg-purple{
            background: var(--bs-purple);
        }
        a{
            text-decoration: none;
        }
        @keyframes bounce {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        @media (max-width: 992px) {
            .navbar-nav {
                text-align: center;
                margin-top: 1rem;
            }

            .navbar-nav .nav-link {
                margin: 0.5rem 0;
            }
        }
        
            .dt-buttons{ width: auto; }
            .buttons-excel{ background: rgb(0, 154, 16); width: 100px; margin: 10px 10px; }
            .buttons-print{ background: rgba(1, 77, 94, 0.52); width: 100px; margin: 10px 10px; }
            .buttons-colvis{ width: auto; height: auto; margin: 10px 10px; }
            .buttons-pdf{ background: rgb(202, 8, 8); width: 100px; margin: 10px 10px; }
            .add_case{ background: rgb(9, 93, 220); color: white; width: 100px; margin: 10px 10px; }
            .add_case:hover { background: rgb(0, 32, 77); }
            .buttons-excel:hover { background: rgb(0, 77, 8); }
            .buttons-print:hover { background: rgb(0, 54, 69); }
            .buttons-pdf:hover { background: rgb(153, 6, 6); }
            .dropdown { position: relative; display: inline-block; z-index: 100; }
            .dropdown-content { display: none; position: absolute; left: 100%; background-color: #fff; border: 1px solid var(--bs-info); min-width: 160px; box-shadow: 0px 8px 16px rgba(0,0,0,0.2); padding: 12px 16px; z-index: 100; }
            .dropdown-content::before { content: ""; position: absolute; top: 40%; z-index: -10; left: -5%; transform: translate(-50%, -50%) rotate(45deg); height: 15px; width: 15px; background: var(--bs-info); }
            .dropdown:hover .dropdown-content { display: block; }
            .dropdown-item:hover { color: var(--bs-info); }
            .dt-search { display: flex; align-items: center; justify-content: flex-end; }
            div.dt-container .dt-search input { outline: none; }
            div.dt-container .dt-search input:focus { border: 1px solid var(--bs-info); }
            div.dtsb-searchBuilder button.dtsb-button { background: cadetblue; color: #fff; }
            div.dtsb-searchBuilder div.dtsb-group div.dtsb-logicContainer button.dtsb-logic { color: #000; }
            div.dt-length { display: flex; align-items: center; justify-content: flex-end; }
            td { font-size: 12px; }
            /* table.dataTable.dtr-inline.collapsed>tbody>tr>td.dtr-control::before,
            table.dataTable.dtr-inline.collapsed>tbody>tr>th.dtr-control::before {
                margin-right: .5em;
                display: inline-block;
                content: "~";
                border: 0;
            }
            table.dataTable.dtr-inline.collapsed>tbody>tr.dtr-expanded>td.dtr-control:before,
            table.dataTable.dtr-inline.collapsed>tbody>tr.dtr-expanded>th.dtr-control:before {
                border-left: 5px solid transparent;
                border-right: 5px solid transparent;
            }
            table.dataTable.dtr-inline.collapsed>tbody>tr>td.dtr-control:before,
            /* table.dataTable.dtr-inline.collapsed>tbody>tr>th.dtr-control:before {
                background-color: var(--bs-info);
                border: .15em solid #fff;
                border-radius: 1em;
                box-shadow: 0 0 .2em #444;
                color: #fff;
                content: "~";
                display: block;
                font-family: Courier New, Courier, monospace;
                height: 1em;
                width: 1em;
                position: absolute;
                text-align: center;
                top: 50%;
                left: 5px;
                margin-top: -9px;
            } */ */
        
    </style>
    <style>
        #backToTop {
            position: fixed;
            bottom: 20px;
            right: 20px;
            display: none;
            /* hidden by default */
            z-index: 999;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            font-size: 1.5rem;
            text-align: center;
            line-height: 1.2;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
        }

        #backToTop:hover {
            background-color: #0a58ca;
            /* darker blue */
        }
    </style>
    
</head>