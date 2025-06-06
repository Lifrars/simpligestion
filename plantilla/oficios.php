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

    <link href="assets/css/plugins.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

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

                    <ul class="nav nav-pills mb-3 mt-3 nav-fill" id="justify-pills-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="justify-pills-home-tab" data-toggle="pill"
                                href="#justify-pills-home" role="tab" aria-controls="justify-pills-home"
                                aria-selected="true">Mis Oficios</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="justify-pills-profile-tab" data-toggle="pill"
                                href="#justify-pills-profile" role="tab" aria-controls="justify-pills-profile"
                                aria-selected="false">Todos Los Oficios</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="justify-pills-contact-tab" data-toggle="pill"
                                href="#justify-pills-contact" role="tab" aria-controls="justify-pills-contact"
                                aria-selected="false">Nuevo Oficio</a>
                        </li>
                    </ul>

                    <div class="tab-content" id="justify-pills-tabContent">

                        <div class="tab-pane fade show active" id="justify-pills-home" role="tabpanel"
                            aria-labelledby="justify-pills-home-tab">
                            <table
                                class="table table-bordered table-hover table-striped table-checkable table-highlight-head m-0"
                                style="width:100%;">
                                <thead>
                                    <tr>
                                        <th class="checkbox-column">

                                        </th>
                                        <th class="">Oficio</th>
                                        <th class="">Serie</th>
                                        <th class="">Tipo Oficio</th>
                                        <th class="">Fecha</th>
                                        <th class="">Dependencia</th>
                                        <th class="">Asunto</th>
                                        <th class="">Rad</th>
                                    </tr>
                                </thead>

                            </table>
                        </div>
                        <div class="tab-pane fade" id="justify-pills-profile" role="tabpanel"
                            aria-labelledby="justify-pills-profile-tab">
                            <table
                                class="table table-bordered table-hover table-striped table-checkable table-highlight-head m-0"
                                style="width:100%;">
                                <thead>
                                    <tr>
                                        <th class="checkbox-column">

                                        </th>
                                        <th class="">Oficio</th>
                                        <th class="">Serie</th>
                                        <th class="">Fecha</th>
                                        <th class="">Tipo</th>
                                        <th class="">Dependencia</th>
                                        <th class="">Asunto</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>#</td>
                                        <td>3</td>
                                        <td></td>
                                        <td></td>
                                        <td>Oficio de Respuesta</td>
                                        <td>Contabilidad</td>
                                        <td>Respuesta a dadada</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>2</td>
                                        <td></td>
                                        <td>23/10/204</td>
                                        <td>Oficio de Respuesta</td>
                                        <td>Secretaria General</td>
                                        <td>hhhhddf</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>1</td>
                                        <td></td>
                                        <td>23/10/204</td>
                                        <td>Oficio de Respuesta</td>
                                        <td>Banco de Datos</td>
                                        <td>gggg</td>
                                    </tr>
                                </tbody>

                            </table>
                        </div>
                        <div class="tab-pane fade" id="justify-pills-contact" role="tabpanel"
                            aria-labelledby="justify-pills-contact-tab">
                            <div class="row p-5">

                                <div class="col-6 text-center">
                                    <form action="">
                                        <label style="text-align:left;width:110px">Dependencia</label>
                                        <input style="width: 250px" type="text" name="" id="">
                                        <br>

                                        <label style="text-align:left;width:110px">Firma</label>
                                        <input style="width: 250px" type="text" name="" id="">
                                        <br>

                                        <label style="text-align:left;width:110px">Fecha</label>
                                        <input style="width: 250px" type="text" name="" id="">
                                        <br>

                                        <label style="text-align:left;width:110px">Radicado</label>
                                        <input style="width: 250px" type="text" name="" id="">
                                        <br><br>

                                        <div class="d-flex justify-content-between">
                                            <div class="mx-5">
                                                <p>Consecutivo</p>
                                                <span>0</span>
                                            </div>
                                            <div class="mr-3">
                                                <p>Serie Documental</p>
                                                <span>0</span>
                                            </div>
                                        </div>
                                        <br><br>


                                        <div class="mb-3">
                                            <label for="exampleTextarea" class="form-label">Asunto</label>
                                            <textarea class="form-control" id="exampleTextarea" rows="4"
                                                placeholder="Escribe Tu Asunto............"></textarea>
                                        </div>

                                    </form>
                                </div>
                                <div class="col-6 d-flex align-items-center justify-content-center text-center">
                                    <div>
                                        <div style="width:100%">
                                            <i class="fas fa-folder fa-10x" style="color: yellow;"></i>
                                        </div>
                                        <input type="boton" class="btn btn-primary" value="Crear Nuevo Oficio">
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
</body>


</html>