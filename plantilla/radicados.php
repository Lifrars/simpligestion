<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>Gestor Documental</title>
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico" />
    <link href="assets/css/loader.css" rel="stylesheet" type="text/css" />
    <script src="assets/js/loader.js"></script>

    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Quicksand:400,500,600,700&display=swap" rel="stylesheet">
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />


    <link href="assets/css/plugins.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="assets/css/radicados.css">
    <!-- END GLOBAL MANDATORY STYLES -->

    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM STYLES -->
    <link href="plugins/apex/apexcharts.css" rel="stylesheet" type="text/css">
    <link href="assets/css/dashboard/dash_2.css" rel="stylesheet" type="text/css" />
    <!-- END PAGE LEVEL PLUGINS/CUSTOM STYLES -->



</head>

<body>

    <?php

    include 'menu.php';

    ?>


    <div class="container">
        <div class="card rounded mt-5">
            <div class="card-body ">
                <div class="table-top">

                    <div class="row">

                        <div class="col-4 text-center">
                            <h5 class="text-center mb-4 fs-1">Radicado</h5>
                            <input type="radio" class="mx-1" id="check" name="check"><b class="mr-3 text-black"
                                onclick="toggleRadio('check')" style="cursor:pointer;">Ver
                                Todos</b>
                            <input type="radio" class="mr-2 ml-3" id="check2" name="check"><b class="text-black"
                                onclick="toggleRadio('check2')" style="cursor:pointer;">Anular
                                Filtro</b>
                        </div>

                        <div class="col-4 text-center">
                            <label class="text-black"><b>Selecciona criterio de búsqueda</b></label>
                            <select class="form-select">
                                <option> Seleccionar </option>
                            </select>
                        </div>

                        <div class="col-4 d-flex justify-content-center pt-3">
                            <button type="boton" class="btn btn-primary my-3  boton-block rounded" style="width:200px"
                                data-toggle="modal" data-target="#largeModal">Agregar</button>
                        </div>

                    </div>

                </div>
                <div style="width: 100%;" class="mt-4">
                    <div class="table-responsive">
                        <table
                            class="table table-bordered table-hover table-striped table-checkable table-highlight-head m-0"
                            style="width:100%;">
                            <thead>
                                <tr>
                                    <th class="checkbox-column">
                                    </th>
                                    <th class="">#Rad</th>
                                    <th class="">fecha</th>
                                    <th class="">tipo</th>
                                    <th class="">solicita</th>
                                    <th class="">asunto</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="checkbox-column">

                                    </td>
                                    <td>
                                        <p class="mb-0">Shaun Park</p>
                                    </td>
                                    <td>10/08/2020</td>
                                    <td>320</td>
                                    <td>320</td>

                                    <td class="text-center">
                                        <ul class="table-controls list-unstyled d-flex">
                                            <li class="mr-2"><a href="javascript:void(0);" data-toggle="tooltip"
                                                    data-placement="top" title="Settings"><svg
                                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="feather feather-settings text-primary">
                                                        <circle cx="12" cy="12" r="3"></circle>
                                                        <path
                                                            d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z">
                                                        </path>
                                                    </svg></a> </li>
                                            <li class="mr-2"><a href="javascript:void(0);" data-toggle="tooltip"
                                                    data-placement="top" title="Edit"><svg
                                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="feather feather-edit-2 text-success">
                                                        <path
                                                            d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z">
                                                        </path>
                                                    </svg></a></li>
                                            <li><a href="javascript:void(0);" data-toggle="tooltip" data-placement="top"
                                                    title="Delete"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                        height="24" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        class="feather feather-trash-2 text-danger">
                                                        <polyline points="3 6 5 6 21 6"></polyline>
                                                        <path
                                                            d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                                        </path>
                                                        <line x1="10" y1="11" x2="10" y2="17"></line>
                                                        <line x1="14" y1="11" x2="14" y2="17"></line>
                                                    </svg></a></li>
                                        </ul>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="checkbox-column">

                                    </td>
                                    <td>
                                        <p class="mb-0">Alma Clarke</p>
                                    </td>
                                    <td>11/08/2020</td>
                                    <td>420</td>
                                    <td>320</td>

                                    <td class="text-center">
                                        <ul class="table-controls list-unstyled d-flex">
                                            <li class="mr-2"><a href="javascript:void(0);" data-toggle="tooltip"
                                                    data-placement="top" title="Settings"><svg
                                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="feather feather-settings text-primary">
                                                        <circle cx="12" cy="12" r="3"></circle>
                                                        <path
                                                            d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z">
                                                        </path>
                                                    </svg></a> </li>
                                            <li class="mr-2"><a href="javascript:void(0);" data-toggle="tooltip"
                                                    data-placement="top" title="Edit"><svg
                                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="feather feather-edit-2 text-success">
                                                        <path
                                                            d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z">
                                                        </path>
                                                    </svg></a></li>
                                            <li class="mr-2"><a href="javascript:void(0);" data-toggle="tooltip"
                                                    data-placement="top" title="Delete"><svg
                                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="feather feather-trash-2 text-danger">
                                                        <polyline points="3 6 5 6 21 6"></polyline>
                                                        <path
                                                            d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                                        </path>
                                                        <line x1="10" y1="11" x2="10" y2="17"></line>
                                                        <line x1="14" y1="11" x2="14" y2="17"></line>
                                                    </svg></a></li>
                                        </ul>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="checkbox-column">

                                    </td>
                                    <td>
                                        <p class="mb-0">Kelly Young</p>
                                    </td>
                                    <td>12/08/2020</td>
                                    <td>130</td>
                                    <td>320</td>

                                    <td class="text-center">
                                        <ul class="table-controls list-unstyled d-flex">
                                            <li class="mr-2"><a href="javascript:void(0);" data-toggle="tooltip"
                                                    data-placement="top" title="Settings"><svg
                                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="feather feather-settings text-primary">
                                                        <circle cx="12" cy="12" r="3"></circle>
                                                        <path
                                                            d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z">
                                                        </path>
                                                    </svg></a> </li>
                                            <li class="mr-2"><a href="javascript:void(0);" data-toggle="tooltip"
                                                    data-placement="top" title="Edit"><svg
                                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="feather feather-edit-2 text-success">
                                                        <path
                                                            d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z">
                                                        </path>
                                                    </svg></a></li>
                                            <li class="mr-2"><a href="javascript:void(0);" data-toggle="tooltip"
                                                    data-placement="top" title="Delete"><svg
                                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="feather feather-trash-2 text-danger">
                                                        <polyline points="3 6 5 6 21 6"></polyline>
                                                        <path
                                                            d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                                        </path>
                                                        <line x1="10" y1="11" x2="10" y2="17"></line>
                                                        <line x1="14" y1="11" x2="14" y2="17"></line>
                                                    </svg></a></li>
                                        </ul>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="checkbox-column">

                                    </td>
                                    <td>
                                        <p class="mb-0">Vincent Carpenter</p>
                                    </td>
                                    <td>13/08/2020</td>
                                    <td>260</td>
                                    <td>320</td>

                                    <td class="text-center">
                                        <ul class="table-controls list-unstyled d-flex">
                                            <li class="mr-2"><a href="javascript:void(0);" data-toggle="tooltip"
                                                    data-placement="top" title="Settings"><svg
                                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="feather feather-settings text-primary">
                                                        <circle cx="12" cy="12" r="3"></circle>
                                                        <path
                                                            d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z">
                                                        </path>
                                                    </svg></a> </li>
                                            <li class="mr-2"><a href="javascript:void(0);" data-toggle="tooltip"
                                                    data-placement="top" title="Edit"><svg
                                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="feather feather-edit-2 text-success">
                                                        <path
                                                            d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z">
                                                        </path>
                                                    </svg></a></li>
                                            <li class="mr-2"><a href="javascript:void(0);" data-toggle="tooltip"
                                                    data-placement="top" title="Delete"><svg
                                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="feather feather-trash-2 text-danger">
                                                        <polyline points="3 6 5 6 21 6"></polyline>
                                                        <path
                                                            d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                                        </path>
                                                        <line x1="10" y1="11" x2="10" y2="17"></line>
                                                        <line x1="14" y1="11" x2="14" y2="17"></line>
                                                    </svg></a></li>
                                        </ul>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="checkbox-column">

                                    </td>
                                    <td>
                                        <p class="mb-0">Andy King</p>
                                    </td>
                                    <td>14/08/2020</td>
                                    <td>180</td>
                                    <td>320</td>

                                    <td class="text-center">
                                        <ul class="table-controls list-unstyled d-flex">
                                            <li class="mr-2"><a href="javascript:void(0);" data-toggle="tooltip"
                                                    data-placement="top" title="Settings"><svg
                                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="feather feather-settings text-primary">
                                                        <circle cx="12" cy="12" r="3"></circle>
                                                        <path
                                                            d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z">
                                                        </path>
                                                    </svg></a> </li>
                                            <li class="mr-2"><a href="javascript:void(0);" data-toggle="tooltip"
                                                    data-placement="top" title="Edit"><svg
                                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="feather feather-edit-2 text-success">
                                                        <path
                                                            d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z">
                                                        </path>
                                                    </svg></a></li>
                                            <li class="mr-2"><a href="javascript:void(0);" data-toggle="tooltip"
                                                    data-placement="top" title="Delete"><svg
                                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="feather feather-trash-2 text-danger">
                                                        <polyline points="3 6 5 6 21 6"></polyline>
                                                        <path
                                                            d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                                        </path>
                                                        <line x1="10" y1="11" x2="10" y2="17"></line>
                                                        <line x1="14" y1="11" x2="14" y2="17"></line>
                                                    </svg></a></li>
                                        </ul>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>



                    <!-- Large Modal -->
                    <div class="modal fade" id="largeModal" data-bs-backdrop="static" tabindex="-1" role="dialog"
                        aria-labelledby="largeModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-xl" role="document">
                            <div class="modal-content p-lg-4 p-xl-3 p-sm-1 p-md-2">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="largeModalLabel"></h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="container">
                                        <div class="row mb-5">
                                            <div class="col-lg-4 col-md-12 col-sm-12">
                                                <div class="form-group">
                                                    <h1 class="h3 h3-sm text-danger">Radicado # 7</h1>
                                                    <span style="color:red;">28/10/2014 - 10:54 a.m.</span>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-0 col-sm-0"></div>
                                            <div class="col-lg-4 col-md-12 col-sm-4 d-flex align-items-center">
                                                <h5>Serie Documental: -- - --- -----</h5>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="row">
                                        <div class="col-lg-4 col-sm-12">
                                            <div class="form-group">
                                                <label><b>Tipo Documento</b></label><br>
                                                <select name="" id="" class="form-select inp-radicado border-gray">
                                                    <option value="0">Seleccionar</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-sm-12">
                                            <div class="form-group">
                                                <label><b>Solicitante</b></label><br>
                                                <input type="text" class="form-control border-gray d-flex flex-column">
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-sm-12">
                                            <div class="form-group">
                                                <label><b>Dependencia</b></label><br>
                                                <select name="" id="" class="form-select inp-radicado border-gray">
                                                    <option value="0">Seleccionar</option>
                                                    <option value="Presidencia">Presidencia</option>
                                                    <option value="Secretaria General">Secretaria General</option>
                                                    <option value="Banco De Datos">Banco De Datos</option>
                                                    <option value="Contabilidad">Contabilidad</option>
                                                    <option value="Control Interno">Control Interno</option>
                                                    <option value="Archivo">Archivo</option>
                                                    <option value="Recepción">Recepción</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <div class="col-lg-4 col-sm-12">
                                            <div class="form-group">
                                                <label><b>Asunto</b></label><br>
                                                <textarea id="mensaje" name="mensaje" rows="5" cols="50"
                                                    placeholder="Escribe Tu Mensaje Aqui.........."
                                                    class="form-control border-gray">
                                                        </textarea>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-sm-12">
                                            <div class="form-group">
                                                <label><b>Observaciones</b></label><br>
                                                <textarea id="mensaje" name="mensaje" rows="5" cols="50"
                                                    placeholder="Escribe Tu Mensaje Aqui.........."
                                                    class="form-control border-gray">
                                                        </textarea>
                                            </div>
                                        </div>

                                        <div class="col-lg-4 col-sm-12">
                                            <div class="form-group">
                                                <label><b>Dirección de Notificación</b></label><br>
                                                <input type="text" class="form-control border-gray">
                                            </div>


                                            <div class="form-group">
                                                <label><b>E-mail</b></label><br>
                                                <input type="text" class="form-control border-gray">
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div
                                            class="col-lg-3 col-xl-3 col-sm-4 d-flex justify-content-center align-items-center">
                                            <div class="text-center"> 
                                                <input id="inp-Anexo" type="text" value="0"
                                                    class="form-control border-gray text-center p-3">
                                                <label class="mb-4"><b>Anexos </b></label>
                                            </div>
                                        </div>
                                
                                        <div class="col-lg-3 col-sm-4 col-xl-2 d-flex justify-content-center align-items-center">
                                            <a>
                                                <div class="text-center">
                                                    <span
                                                        class="material-symbols-outlined btn-icon icon-lg">barcode_scanner</span>
                                                    <label><b>Imprimir Por Scanner</b></label>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="col-lg-2 col-sm-4 col-xl-3 d-flex justify-content-center align-items-center">
                                            <a>
                                                <div class="text-center"> <span
                                                        class="material-symbols-outlined btn-icon icon-lg">
                                                        print
                                                    </span><br>
                                                    <label><b>Imprimir <br> Stricker</b></label>
                                                </div>
                                            </a>
                                        </div>


                                        <div class="col-lg-4 col-sm-12 col-xl-4">
                                            <div class="position-relative">
                                                <div class="label">
                                                    <b>Archivos Adjuntos</b>
                                                </div>
                                                <div class="drag-drop" id="droppableArea">
                                                <b class="mb-3"> Suelta Aqui </b>
                                                <ul id="fileList" class="file-list"></ul>
                                                </div>


                                            </div>
                                        </div>

                                    </div>

                                </div>


                                <div class="modal-footer">
                                    <div class="container text-right">

                                        <button type="button" class="btn btn-danger"
                                            data-dismiss="modal">Cancelar</button>
                                        <button type="button" class="btn btn-primary ">Guardar</button>
                                    </div>

                                </div>
                            </div>


                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>


    <script src="./js/radicados.js"></script>

</body>


</html>