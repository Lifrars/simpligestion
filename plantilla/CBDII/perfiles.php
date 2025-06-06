<?php
session_start();
include "assets/config.php";
// include "auth/logincheck.php";

$moduloid = "2";
$modulo = "Administracion";
$vistaid = "4";
$vista = "Perfilar";

$idPerfil = $_SESSION['pi']['act_idPerfil'];

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="description" content="Sistema de inventarios y facturación.">
    <meta name="author" content="Creative innovation Company">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="keywords" content="admin, estimates, bootstrap, business, corporate, creative, management, minimal, modern,  html5, responsive">
    <meta name="robots" content="noindex, nofollow">
    <title>PPI</title>
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="https://fonts.googleapis.com/css?family=Quicksand:400,500,600,700&display=swap" rel="stylesheet">
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.0/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <link href="assets/css/plugins.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css">

  <!-- Main CSS -->
  <link rel="stylesheet" href="https://ppi.miclickderecho.com/plantilla/assets/css/styles.css">

<!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM STYLES -->
<link href="plugins/apex/apexcharts.css" rel="stylesheet" type="text/css">
<link href="assets/css/dashboard/dash_2.css" rel="stylesheet" type="text/css" />


    <style>
         .has-error label.error {
            color: #f33923 !important;
            font-size: 11px !important;
        }

        .has-error .form-control {
            border-color: #a94442 !important;
        }

        label.error {
            color: #f33923 !important;
            font-size: 11px !important;
        }

        input.has-error,
        select.has-error {
            border: solid 2px #a94442 !important;
        }

        textarea {
            resize: none;
        }

        .inptxt {
            font-size: 14px;
            font-weight: bolder;
            color: white;
            padding-left: 10px;
            width: 100%;
            max-width: 100%;
            border-radius: 5px;
            border-color: #aaaaaa;
            border-width: 1px;
            border-style: solid;
            box-shadow: 3px 3px 2px #888888;
        }

        /* swichts */
        .switch input[type="checkbox"]:checked+.slider {
            background-color: #582BFE;
        }

        .switch input[type="checkbox"]:not(:checked)+.slider {
            background-color: #A289FF;
        }
    </style>
</head>

<body>

    <?php include "menu.php"; ?>


    <div class="container">
            <div class="page-wrapper page-wrapper-three">
                <div class="content">
                    <!-- aqui va ek cintenido de la pagina que vamos a usar -->
                    <div class="" id="div_tabla">
                        <div class="page-header">
                            <div class="page-title">
                                <h4 style="font-weight:bold;color:white;">Perfiles</h4>
                            </div>
                            <div class="page-btn">
                                <button onclick="nuevoProfile();" id="crearPerfil" name="crearPerfil"
                                    class="btn btn-primary" data-validar="SI" data-tipo="MODAL"
                                    data-apodo="Crear perfil"><img src="img/icons/plus.svg" alt="img"
                                        class="me-2">Crear Perfil</button>
                            </div>
                        </div>
                        <!-- /product list -->
                        <div class="card">
                            <div class="card-body" id="divGeneral">
                                <div class="table-top">
                                   
                                </div>
                                <!-- /Filter -->
                                <div class="card" id="filter_inputs">
                                    <div class="card-body pb-0">
                                        <div class="row">
                                            <div class="col-md-3 col-lg-3 col-sm-4 col-12">
                                                <div class="form-group">
                                                    <label for="" style="font-weight: bold;">Nombre - Id</label>
                                                    <input type="text" id="filtroPerfil"
                                                        placeholder="Ingrese nombre del perfil o Id"
                                                        name="filtroPerfil">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /Filter -->

                                <div class="panel-wrapper">
                                    <div class="panel-body" id="panel-bodyprincipal">
                                        <div class="responsive">
                                            <div class="table-wrapper">
                                                <table id="tablaPerfiles" class="table table-hover" style="width:100%">
                                                    <thead class="table-header">
                                                        <tr>
                                                            <th
                                                                style="text-align:center;font-weight:bold;color:white; font-size:14px; width: 250px;">
                                                                ID</th>
                                                            <th
                                                                style="font-weight:bold;color:white; font-size:14px;text-align: center;">
                                                                PERFIL</th>
                                                            <th
                                                                style="font-weight:bold;color:white; font-size:14px;;text-align: center;">
                                                                DESCRIPCIÓN</th>
                                                            <th
                                                                style="width:20%;font-weight:bold;color:white; font-size:14px;text-align: center;">
                                                                ESTADO</th>
                                                            <th
                                                                style="width:10%;font-weight:bold;color:white; font-size:14px;text-align: center;">
                                                                ACCIONES</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                                <br><br>
                                            </div>
                                        </div>
                                        <div id="paginacion" style="float: right;"></div>
                                    </div>
                                    <div>
                                    </div>
                                </div>
                            </div>
                            <!-- /product list -->
                        </div>
                    </div>
                </div>
            </div>


            <div class="row" style="display: none;">
                <td style="font-weight:bold;color:white;height:1rem !important;">
                    <div style="display:flex;gap:1rem;align-items:center;height:1rem !important;padding:5px;"> <a
                            id="editarPerfilador" name="editarPerfilador" data-validar="SI" data-tipo="TABLA_MODAL"
                            data-apodo="Editar perfilar"
                            href="javascript:openProfile(' + respuesta[0][i].id_perfil + ');"><svg
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="white" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="feather feather-edit">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                            </svg></a> <button id="duplicar" name="duplicar" data-validar="SI" data-tipo="TABLA_MODAL"
                            data-apodo="Duplicar perfil" class="bg-lightpurple badges" style="border:none;"
                            onclick="openProfileCopy(' + respuesta[0][i].id_perfil + ')">DUPLICAR</button></div>
                </td>
            </div>
        </div>

            <!-- modal -->

    <div class="modal fade" id="ModalProfile" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #2A3E4C">
                    <h5 class="modal-title" style="color: white; text-align: left; flex: 1;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="feather feather-file-plus">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="12" y1="18" x2="12" y2="12"></line>
                            <line x1="9" y1="15" x2="15" y2="15"></line>
                        </svg>
                        Información del Perfil
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">×</span></button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="form-group">
                            <div class="modal-header" style="padding:2px">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <div class="table table-striped">
                                            <input type="hidden" id="inp_dataidperfil" name="inp_dataidperfil" value=""
                                                style="display:none">
                                            <div class="contenedorbotones" style="border: none;">
                                                <div class="nombreperfil" style="width: 100%;padding:3px;"><b
                                                        row-estadoid="lbl1">Nombre Perfil:</b>
                                                    <input list="listaInpPerfil" name="inp_nombreperfil"
                                                        id="inp_nombreperfil" value="" type="text" maxlength="30"
                                                        autocomplete="off"
                                                        style="text-transform:uppercase; font-weight:bold; font-size:14px; color:black; padding: 3px; padding-left:5px; width: 300px; height:36px; border-radius: 5px; border-color: #aaaaaa; border-width: 1px; border-style: solid; box-shadow: 3px 3px 2px #888888;">
                                                    <datalist id="listaInpPerfil"></datalist>
                                                    <div
                                                        style="text-align: left; padding:3px; display:flex; gap: 1rem;">
                                                        <div class="pull-right" style="display: flex; gap: 1rem;">
                                                            <button type="button" id="btn-addPerfil"
                                                                name="btn-addPerfil" class="btn btn-success btn-Guardar"
                                                                style="height:38px;" title="Presione para Guardar">Crear
                                                                Perfil</button>
                                                            <div class="botonesadmin">
                                                                <button id="btnEditarPerfil" name="btnEditarPerfil"
                                                                    class="btn btn-warning">Editar Perfil</button>
                                                                <button
                                                                    style="opacity:1;width:100px;height:38px;font-size:16px;padding:5px;display:none;"
                                                                    id="btnGuardarCambiosPerfil"
                                                                    name="btnGuardarCambiosPerfil"
                                                                    class="btn btn-warning">Guardar</button>
                                                                <button type="button"
                                                                    style="opacity:1;width:100px;height:38px;font-size:16px;padding:5px;display:none;"
                                                                    id="cancelarEdicion" class="btn btn-danger"
                                                                    data-bs-dismiss="modal">Cancelar</button>
                                                                <button id="btnEliminarPerfil" name="btnEliminarPerfil"
                                                                    class="btn btn-danger">Eliminar Perfil</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div style="border: none;">
                                                <div style="width: 70px;padding:3px;"><b id="lbl2"
                                                        style="font-weight:bolder;">Descripción</b></div>
                                                <div style="text-align: left; padding:3px">
                                                    <textarea name="txt_observa" id="txt_observa" maxlength="46"
                                                        rows="1"
                                                        style="font-size:14px; color:black; padding: 5px; width: 100%; max-width: 100%;border-radius: 10px; border-color: #aaaaaa; border-width: 1px; border-style: solid; box-shadow: 5px 5px 4px #888888;"></textarea>
                                                </div>
                                            </div>
                                            <div id="row-estado">
                                                <div class="checkbox">
                                                    <div class="form-group-sm">
                                                        <div class="estado"><b id="lbl3"
                                                                style="font-weight:bolder;">Estado</b></div>
                                                        <label class="switch s-icons s-outline mr-2 mb-2">
                                                            <input type="checkbox" class="bs-switch" id="checkestado"
                                                                name="checkestado" checked data-on-text="Activo"
                                                                data-on-color="success" data-off-text="Inactivo"
                                                                value="1" checked>
                                                            <span class="slider round"></span>
                                                        </label>
                                                        <input type="hidden" value="1" id="estado" name="estado">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group" style="max-height: 300px; border: none;">
                                            <p>
                                                <img id="imgExclamation" src="#" alt=""
                                                    style="width:30px; display:none">
                                                <span id="spanlcd" class="label label-danger"
                                                    style="color: white; font-size: 14px; display: none;" width="100%">
                                                </span>
                                            </p>
                                            <p>
                                                <img id="imgExclamation2" src="#" alt=""
                                                    style="width:30px; display:none">
                                                <span id="spanlcd2" class="label label-danger"
                                                    style="color: white; font-size: 14px; display: none;" width="100%">
                                                </span>
                                            </p>
                                        </div>

                                        <div id="div_permisosAdd" name="div_permisosAdd" style="display: none;">
                                            <h4>Restricciones <span id="spanCountRestricciones"></span></h4>
                                            <div class="container">

                                                <div id="div_filtros" class="row d-flex justify-content-between mt-3 ">

                                                    <div id="div_filtro_modulo"
                                                        class="col-xl-2 col-lg-3  col-md-12">
                                                        <h5 class="nombre">Módulo</h5>
                                                        <select name="filtro_modulo" id="filtro_modulo">

                                                        </select>
                                                    </div>

                                                    <div id="div_filtro_vista"
                                                        class="col-xl-2 col-lg-3 col-sm-12 col-md-12">
                                                        <h5 class="nombre">Vista</h5>
                                                        <select name="filtro_vista" id="filtro_vista"
                                                            style="font-size:16px; color:white; padding: 3px; border-radius: 5px; border-color: #aaaaaa; border-width: 1px; border-style: solid; box-shadow: 3px 3px 2px #888888;width:100%">
                                                        </select>
                                                    </div>

                                                    <div id="div_filtro_nivel"
                                                        class="col-xl-2 col-lg-3 col-sm-12 col-md-12"
                                                        style="display:none;">
                                                        <h5 class="nombre">Elementos</h5>
                                                        <select name="filtro_nivel" id="filtro_nivel"
                                                            style="font-size:16px; color:white; padding: 3px; border-radius: 5px; border-color: #aaaaaa; border-width: 1px; border-style: solid; box-shadow: 3px 3px 2px #888888;width:100%">
                                                        </select>
                                                    </div>

                                                    <div id="addboton"
                                                        class="col-xl-6 col-lg-3 col-sm-12 col-md-12 text-center d-flex align-items-center justify-content-center mb-md-3 mt-lg-3 order-lg-last order-sm-first">
                                                        <button type="button" id="btn-AddPermiso" name="btn-AddPermiso"
                                                            class="btn btn-success btn-Guardar"
                                                            style="height:38px;margin-right:auto;"
                                                            title="Presione para Guardar">Agregar Restricción</button>
                                                    </div>

                                                </div>
                                            </div>


                                            <div id="div_permisosDatos">
                                                <table class="table table-striped table-responsive"
                                                    style="border-radius: 30px; border-color: #aaaaaa; border-width: 1px; border-style: solid;">
                                                    <!-- restricion boton  administrador agregar restrincion-->
                                                    <tr>
                                                        <th style="width: 70px;padding:3px;"><b id="lbl2"
                                                                style="font-weight:bolder;  font-size: 12px">Nivel</b>
                                                        </th>
                                                        <td style="text-align: left; padding:3px">
                                                            <select id="sel_mod" name="sel_mod" value="" type="text"
                                                                placeholder="Modulo"
                                                                style="font-size:16px; color:black; padding: 3px; width: 25%; max-width: 50%; border-radius: 5px; border-color: #aaaaaa; border-width: 1px; border-style: solid; box-shadow: 3px 3px 2px #888888;">
                                                                <option value="0" style="font-size: 16pt;"></option>
                                                            </select>
                                                            <select id="sel_vista" name="sel_vista" placeholder="Vista"
                                                                value="" type="text"
                                                                style="font-size:16px; color:black; padding: 3px; width: 25%; max-width: 50%; border-radius: 5px; border-color: #aaaaaa; border-width: 1px; border-style: solid; box-shadow: 3px 3px 2px #888888;">
                                                                <option value="0" style="font-size: 16pt;"></option>
                                                            </select>

                                                            <select id="sel_elem" name="sel_elem" placeholder="Elemento"
                                                                value="" type="text"
                                                                style="font-size:16px; color:black; padding: 3px; width: 25%; max-width: 50%; border-radius: 5px; border-color: #aaaaaa; border-width: 1px; border-style: solid; box-shadow: 3px 3px 2px #888888;">
                                                                <option value="0" style="font-size: 16pt;"></option>
                                                            </select>
                                                        </td>

                                                    </tr>
                                                    <tr>
                                                        <th style="width: 70px;padding:3px;"><b id="lbl6"
                                                                style="font-weight:bolder; font-size: 12px">Tipo
                                                                Restricción</b>
                                                        </th>
                                                        <td style="text-align: left; padding:3px width=100px">
                                                            <select id="sel_tipoRestriccion" name="sel_tipoRestriccion"
                                                                value="" type="text"
                                                                style="font-size:16px; color:white; padding: 3px; width: 50%; max-width: 50%; border-radius: 5px; border-color: #aaaaaa; border-width: 1px; border-style: solid; box-shadow: 3px 3px 2px #888888;">
                                                                <option value="0" style="font-size: 16pt;"></option>
                                                                <option value="1" style="font-size: 16pt;">Bloquear
                                                                </option>
                                                                <option value="2" style="font-size: 16pt;">Ocultar
                                                                </option>
                                                                <option value="3" style="font-size: 16pt;">Ofuscar
                                                                </option>
                                                            </select>
                                                            &nbsp;&nbsp;
                                                            <button type="button" id="btn-GuardarPermiso"
                                                                name="btn-GuardarPermiso"
                                                                class="btn btn-success btn-Guardar" data-toggle='modal'
                                                                title="Presione para Guardar">Agregar
                                                                Restricción</button>
                                                            <button type="button" id="btn-CancelarAgregarPermiso"
                                                                name="btn-CancelarAgregarPermiso"
                                                                class="btn btn-success btn-danger" data-toggle='modal'
                                                                title="Presione para Guardar">Cancelar</button>
                                                        </td>
                                                    </tr>
                                                </table>
                                                <div>
                                                </div>
                                                <table class="table table-striped"
                                                    style="border-radius: 30px; border-color: #aaaaaa; border-width: 1px; border-style: solid;">
                                                    <tr>
                                                        <td style="text-align: left; padding:3px">
                                                            <iframe id="frameshow" src="" frameborder="0" width="100%"
                                                                height="400px"></iframe>
                                                            &nbsp;&nbsp;
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>

                                            <div id="div_permisosTabla" name="div_permisosTabla">
                                                <table class="table table-striped table-responsive" id="tableData2"
                                                    style="margin-top:10px">
                                                    <thead class="table-header">
                                                        <tr>
                                                            <th style="color:whitesmoke; font-weight:bolder;"
                                                                scope="col">Nivel de Restricción</th>
                                                            <th style="color:whitesmoke; font-weight:bolder;"
                                                                scope="col">Módulo</th>
                                                            <th style="color:whitesmoke; font-weight:bolder;"
                                                                scope="col">Vista</th>
                                                            <th style="color:whitesmoke; font-weight:bolder;"
                                                                scope="col">Elemento</th>
                                                            <th style="color:whitesmoke; font-weight:bolder;"
                                                                scope="col">Permiso</th>
                                                            <th style="color:whitesmoke; font-weight:bolder;"
                                                                scope="col">Eliminar</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                                <br>
                                                <div id="paginacion2" style="float: right;"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" modal-footer">
                            <!--<button type="button" class="label label-success Btn-calc"  id="Btn-calc" onclick="ConZHor()">calcular</button>-->
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
    <!-- fin modal -->

    <div class="modal fade" id="ModalProfileCopy" data-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document" style="width: 100% !important;">
            <div class="modal-content">
                <div class="modal-header" id="modal-agregarRestriciones" style="background-color:#2A3E4C">
                    <h5 class="modal-title" style="color: white; text-align: left; flex: 1;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="feather feather-file-plus">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="12" y1="18" x2="12" y2="12"></line>
                            <line x1="9" y1="15" x2="15" y2="15"></line>
                        </svg>
                        Información del Perfil
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <div class="col-12">
                        <div class="col-12">
                            <div class="form-group">
                                <div class="modal-header" style="padding:2px">
                                    <div class="col-12">
                                        <div class="form-group" id="contenedorcopia">
                                            <table class="table table-striped" id="tablecopiaperfil">
                                                <input type="hidden" id="inp_dataidperfilcopy"
                                                    name="inp_dataidperfilcopy" value="" style="display:none">
                                                <tr>
                                                    <th style="width: 70px;padding:3px;"><b id="lbl1"
                                                            style="font-weight:bolder;">Nombre Perfil</b></th>
                                                    <td
                                                        style="text-align: left; padding:3px; gap: 1rem;  flex-grow: 1; display: flex; align-items: center;">
                                                        <input list="listaInpPerfilCopy" name="inp_nombreperfilcopy"
                                                            id="inp_nombreperfilcopy" value="" type="text"
                                                            maxlength="30" autocomplete="off"
                                                            style="text-transform:uppercase; font-weight:bold; font-size:14px; color:black; padding: 3px; padding-left:5px; width: 300px; height:36px; border-radius: 5px; border-color: #aaaaaa; border-width: 1px; border-style: solid; box-shadow: 3px 3px 2px #888888;">
                                                        <input type="hidden" id="idPerfil">
                                                        <datalist id="listaInpPerfilCopy"></datalist>
                                                        <div class="pull-right" id="btn-copiarperfil"
                                                            style="display: flex; gap: 1rem;">
                                                            <button type="button" id="btn-addPerfilCopy"
                                                                name="btn-addPerfilCopy"
                                                                class="btn btn-success btn-Guardar" style="height:38px;"
                                                                title="Presione para Guardar">Copiar Perfil</button>
                                                            <button type="button"
                                                                style="opacity:1;width:100px;height:38px;font-size:16px;padding:5px;"
                                                                id="closemodal" class="btn btn-success"
                                                                data-bs-dismiss="modal" aria-label="Close"
                                                                style="height:38px;">Cancelar</button>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th style="width: 100px;padding:3px;"><b id="lbl2"
                                                            style="font-weight:bolder;">Descripcion</b></th>
                                                    <td style="text-align: left; padding:3px">
                                                        <textarea name="txt_observacopy" id="txt_observacopy"
                                                            maxlength="46" rows="2"
                                                            style="font-size:14px; color:black; padding: 5px; width: 100%; max-width: 100%;border-radius: 10px; border-color: #aaaaaa; border-width: 1px; border-style: solid; box-shadow: 5px 5px 4px #888888;"></textarea>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="estado" style="width: 70px;padding:2px;"><b id="lbl3"
                                                            style="font-weight:bolder;">Estado</b></th>
                                                    <td style="text-align: left;">
                                                        <div class="row mt-10">
                                                            <div class="col-md-6">
                                                                <div class="form-group-sm ">
                                                                    <input type="hidden" value="1" id="estadocopy"
                                                                        name="estadocopy">
                                                                    <div>
                                                                        <label
                                                                            class="switch s-icons s-outline mr-2 mb-2">
                                                                            <input type="checkbox" class="bs-switch"
                                                                                id="checkestadocopy"
                                                                                name="checkestadocopy" checked
                                                                                data-on-text="Activo"
                                                                                data-on-color="success"
                                                                                data-off-text="Inactivo" value="1"
                                                                                checked>
                                                                            <span class="slider round"></span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                            <div id="div_permisosAddCopy" name="div_permisosAddCopy">
                                                <h4>Restricciones <span id="spanCountRestriccionesCopy"></span></h4>
                                                <div id="div_permisosTablaCopy" name="div_permisosTablaCopy">
                                                    <table class="table table-striped" id="tableData2Copy"
                                                        style="margin-top:10px">
                                                        <thead class="table-header">
                                                            <tr>
                                                                <th style="color:whitesmoke; font-size:16px;font-weight:bolder;"
                                                                    scope="col">Nivel de Restricción</th>
                                                                <th style="color:whitesmoke; font-weight:bolder;"
                                                                    scope="col">Módulo</th>
                                                                <th style="color:whitesmoke; font-weight:bolder;"
                                                                    scope="col">Vista</th>
                                                                <th style="color:whitesmoke; font-weight:bolder; text-align:center;"
                                                                    scope="col">Elemento</th>
                                                                <th style="color:whitesmoke; font-weight:bolder;"
                                                                    scope="col">Permiso/Restricción</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        </tbody>
                                                    </table>
                                                    <div class="form-group" style="max-height: 200px;">
                                                        <p>
                                                            <img id="imgExclamationCopy" src="#" alt=""
                                                                style="width:30px; display:none">
                                                            <span id="spanlcdcopy" class="label label-danger"
                                                                style="color: white; font-size: 14px; display: none;"
                                                                width="100%">
                                                            </span>
                                                        </p>
                                                    </div>
                                                    <br>
                                                    <div id="paginacion2Copy" style="float: right;">

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <!--<button type="button" class="label label-success Btn-calc"  id="Btn-calc" onclick="ConZHor()">calcular</button>-->
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="plugins/jquery-ui/jquery-ui.min.js"></script>
    <script src="plugins/highlight/highlight.pack.js"></script>


    <!-- Sweetalert 2 -->
    <script src="assets/plugins/sweetalert/sweetalert2.all.min.js"></script>
    <script src="assets/plugins/sweetalert/sweetalerts.min.js"></script>

    <script src="perfilar.js"></script>

    <script>

        var idperfil = '<?php echo ($_SESSION['pi']['act_idPerfil']); ?>';
        var moduloid = '<?php echo ($moduloid); ?>';
        var vistaid = '<?php echo ($vistaid); ?>';

        function limpiarFiltro() {
            document.getElementById("filtroPerfil").value = "";

            loadProfiles(1, 5, '', '');
            perfilar(idperfil, moduloid, vistaid);

        }



        $(document).ready(function () {
            loadProfiles(1, 5, '', '');
            loadModulosList();

        })


        $('#checkestado').click(function () {
            if ($(this).is(':checked')) {
                var estado = '1';
            } else {
                var estado = '0';
            }
            $('#estado').val(estado);

            $.ajax({
                url: "backend/perfilesBackend.php",
                type: "POST",
                dataType: "json",
                data: ({
                    ind: "13",
                    idperfil: $("#inp_dataidperfil").val(),
                    estado: estado
                })
            }).done(function (resp) {
                loadProfiles(1, 5, '', '');
            })
        });

        $("#checkestado").prop('checked', true);

        $("#checkestado").on("click", function () {
            if ($("#checkestado").is(":checked")) {
                $("#checkestado").prop('checked', true);
            } else {
                $("#checkestado").prop('checked', false);
            }
        });

        //restablecer filtros
        $('#text_filtros').click(function () {
            $('#filtroPerfil').val('');
            loadProfiles(1, 5, '', '');
        });

        function loadProfiles(inicio, nroreg, tipofiltro, filtro) {
            let nuevoinicio = (inicio - 1) * nroreg;
            $.ajax({
                url: "backend/perfilesBackend.php",
                type: "POST",
                dataType: "json",
                data: ({
                    ind: "1",
                    tipofiltro: tipofiltro,
                    filtro: filtro,
                    inicio: nuevoinicio,
                    nroreg: nroreg
                }),
            })
                .done(function (respuesta) {
                    let htmlTags = '';
                    let ImgArrayData = '';
                    $('#tablaPerfiles tbody').empty();
                    $("#paginacion").empty();
                    for (let i = 0; i < respuesta[0].length; i++) {

                        let estadoperfil = "";
                        let estadoperfil_color = "";


                        if (respuesta[0][i].status == "1") {
                            estadoperfil = "ACTIVO";
                            estadoperfil_color = "success"
                        }
                        if (respuesta[0][i].status == "0") {
                            estadoperfil = "INACTIVO";
                            estadoperfil_color = "warning"
                        }

                        htmlTags += '<tr>';
                        htmlTags += '   <th style=" border-bottom: 1px solid #E9ECEF; font-size:13px;color:white;text-align:center;padding:5px;"><span class="badge" style="font-size:13px; font-weight:bold; color:white; background-color:#61329C">' + respuesta[0][i].id_perfil + '</th>';
                        htmlTags += '   <td style="padding:5px;text-align:center;"><span onclick="openProfile(' + respuesta[0][i].id_perfil + ')" class="badge_badge" style="cursor:pointer;font-size:13px; font-weight:bold;  color:white">' + respuesta[0][i].nombre_perfil + '</span></td>';
                        htmlTags += '   <td style="padding:5px; color:#ffffff; padding-left: 100px;">' + respuesta[0][i].descripcion + '</td>';
                        htmlTags += '   <td style="text-align:center;padding:5px;"><span class="bg-' + estadoperfil_color + ' badges" style="font-size:13px; font-weight:bold; color:white;">' + estadoperfil + '</span></td>';
                        htmlTags += '   <td style="font-weight:bold;color:white;height:1rem !important;"> <div style="display:flex;gap:1rem;align-items:center;height:1rem !important;padding:5px;"> <a id="editarPerfilador" name="editarPerfilador" data-validar="SI" data-tipo="TABLA_MODAL" data-apodo="Editar perfilar" href="javascript:openProfile(' + respuesta[0][i].id_perfil + ');"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#fff" class="bi bi-pen" viewBox="0 0 16 16"><path d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001zm-.644.766a.5.5 0 0 0-.707 0L1.95 11.756l-.764 3.057 3.057-.764L14.44 3.854a.5.5 0 0 0 0-.708l-1.585-1.585z"/></svg></a>  <button id="duplicar" name="duplicar" data-validar="SI" data-tipo="TABLA_MODAL" data-apodo="Duplicar perfil" class="bg-lightpurple badges" style="border:none;" onclick="openProfileCopy(' + respuesta[0][i].id_perfil + ')">Duplicar</button></div></td>';
                        htmlTags += '</tr>';

                    }

                    $('#tablaPerfiles tbody').append(htmlTags);



                    //Paginador
                    let paginador = "";

                    paginador = "";

                    paginador += '<ul class="pagination justify-content-end">';

                    paginador += '<li style="margin-left: 0px;margin-right: 0px;"><span class="label label pagina">' + respuesta[1].count + ' Registros</li></span>';

                    if (inicio > 1) {
                        ``
                        paginador += '<li class="notDisable" style="margin-left: 0px;margin-right: 0px;"><a class="pagina" href="javascript:void(0)" onclick="loadProfiles(' + 1 + ',' + nroreg + ',\'' + tipofiltro + '\',\'' + filtro + '\')">&laquo;</a></li>';
                        paginador += '<li class="notDisable" style="margin-left: 0px;margin-right: 0px;"><a class="pagina" href="javascript:void(0)" onclick="loadProfiles(' + (inicio - 1) + ',' + nroreg + ',\'' + tipofiltro + '\',\'' + filtro + '\')">&lsaquo;</a></li>';

                    } else {
                        paginador += '<li class="disabled" style="margin-left: 0px;margin-right: 0px;" ><a class="pagina" href="javascript:void(0)">&laquo;</a></li>';
                        paginador += '<li class="disabled" style="margin-left: 0px;margin-right: 0px;"><a class="pagina" href="javascript:void(0)">&lsaquo;</a></li>';
                    }
                    var limit1 = inicio - nroreg;
                    var limit2 = inicio + nroreg;

                    if (inicio <= parseInt(nroreg)) {
                        limit1 = 1;
                    }
                    if ((inicio + nroreg) >= Math.ceil(respuesta[1].count / parseInt(nroreg))) {
                        limit2 = Math.ceil(respuesta[1].count / parseInt(nroreg));
                    }
                    for (let i = limit1; i <= limit2; i++) {
                        if (i === inicio) {
                            paginador += '<li class="active" style="margin-left: 0px;margin-right: 0px;"><a class="pagina" href="javascript:void(0)">' + i + '</a></li>';
                        } else {
                            paginador += '<li style="margin-left: 0px;margin-right: 0px;"><a class="pagina" href="javascript:void(0)"  onclick="loadProfiles(' + i + ',' + nroreg + ',\'' + tipofiltro + '\',\'' + filtro + '\')">' + i + '</a></li>';
                        }

                    }
                    if (inicio < Math.ceil(respuesta[1].count / parseInt(nroreg))) {
                        paginador += '<li class="notDisable" style="margin-left: 0px;margin-right: 0px;"><a class="pagina" href="javascript:void(0)" onclick="loadProfiles(' + (inicio + 1) + ',' + nroreg + ',\'' + tipofiltro + '\',\'' + filtro + '\')">&rsaquo;</a></li>';

                        paginador += '<li class="notDisable" style="margin-left: 0px;margin-right: 0px;"><a class="pagina" href="javascript:void(0)" onclick="loadProfiles(' + Math.ceil(respuesta[1].count / nroreg) + ',' + nroreg + ',\'' + tipofiltro + '\',\'' + filtro + '\')">&raquo;</a></li>';
                    } else {
                        paginador += '<li class="disabled" style="margin-left: 0px;margin-right: 0px;"><a class="pagina" href="javascript:void(0)">&rsaquo;</a></li>';
                        paginador += '<li class="disabled" style="margin-left: 0px;margin-right: 0px;"><a class="pagina" href="javascript:void(0)">&raquo;</a></li>';
                    }
                    paginador += '<li style="margin-left: 0px;margin-right: 0px;"><span class="label label pagina">' + Math.ceil(respuesta[1].count / parseInt(nroreg)) + ' Páginas</span></li>';

                    paginador += '</ul>';
                    //Fin del paginador

                    $("#paginacion").append(paginador);

                })
                .fail(function (resp) {
                    console.log("fail:" + resp.responseText);
                    console.log("fail:" + resp);
                })
            perfilar(idperfil, moduloid, vistaid);

        }

        function loadListPermisos(inicio, nroreg, idperfil, id_vista, tipo) {
            id_vista == undefined ? id_vista = "" : id_vista = id_vista;
            tipo == undefined ? tipo = "" : tipo = tipo;
            $("#spanCountRestricciones").text("(0)");
            $("#spanCountRestricciones").attr("data-count", "0");
            let nuevoinicio = (inicio - 1) * nroreg;
            $.ajax({
                url: "backend/perfilesBackend.php",
                type: "POST",
                dataType: "json",
                data: ({
                    ind: "2",
                    idperfil: idperfil,
                    id_vista: id_vista,
                    tipo: tipo,
                    inicio: nuevoinicio,
                    nroreg: nroreg
                }),
            })
                .done(function (respuesta) {
                    console.log(respuesta);
                    let htmlTags = '';
                    let ImgArrayData = '';
                    $('#tableData2 tbody').empty();
                    $("#paginacion2").empty();
                    for (let i = 0; i < respuesta[0].length; i++) {
                        let color_fondo = "lightyellow";
                        let color_font = "white";

                        if (respuesta[0][i].permiso == "Permitir") {
                            color_fondo = "lightgreen";
                            color_font = "white";
                        }

                        htmlTags += '<tr>';
                        htmlTags += '   <td><span class="badge badge" style="font-size:15px; font-weight:bold; color:white">' + respuesta[0][i].tipoelemento + '</span></td>';
                        htmlTags += '   <td><span style="font-size:15px; font-weight:bold; color:white">' + respuesta[0][i].modulo + '</span></td>';
                        htmlTags += '   <td><span style="font-size:15px; font-weight:bold; color:white">' + respuesta[0][i].nombrevista + '</span></td>';
                        htmlTags += '   <td style="text-align:left"><span class="badge badge" style="font-size:16px; font-weight:bold; color:' + color_font + ';">' + respuesta[0][i].elemento + '</span></td>';
                        htmlTags += '   <td style="text-align:left"><span class="bg-' + color_fondo + '" style="font-size:16px; font-weight:bold; color:' + color_font + ';">' + respuesta[0][i].permiso + '</span></td>';
                        htmlTags += '   <td style="text-align:center"><span onclick="deletePermiso(' + respuesta[0][i].idpermiso + ')" style="cursor:pointer; font-size:15px; font-weight:bold; color:white;"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="red" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></span></td>';
                        htmlTags += '</tr>';

                    }

                    $('#tableData2 tbody').append(htmlTags);

                    //Paginador
                    let paginador = "";

                    paginador = "";

                    paginador = '<ul class="pagination justify-content-end">';

                    paginador += '<li style="margin-left: 0px;margin-right: 0px;"><span class="label label pagina">' + respuesta[1].count + ' Registros</li></span>';

                    if (inicio > 1) {
                        paginador += '<li class="notDisable" style="margin-left: 0px;margin-right: 0px;"><a class="pagina" href="javascript:void(0)" onclick="loadListPermisos(' + 1 + ',' + nroreg + ',\'' + idperfil + '\')">&laquo;</a></li>';

                        paginador += '<li class="notDisable" style="margin-left: 0px;margin-right: 0px;"><a class="pagina" href="javascript:void(0)" onclick="loadListPermisos(' + (inicio - 1) + ',' + nroreg + ',\'' + idperfil + '\')">&lsaquo;</a></li>';
                    } else {
                        paginador += '<li class="disabled" style="margin-left: 0px;margin-right: 0px;" ><a class="pagina" href="javascript:void(0)">&laquo;</a></li>';
                        paginador += '<li class="disabled" style="margin-left: 0px;margin-right: 0px;"><a class="pagina" href="javascript:void(0)">&lsaquo;</a></li>';
                    }
                    let limit1 = inicio - nroreg;
                    let limit2 = inicio + nroreg;

                    if (inicio <= nroreg) {
                        limit1 = 1;
                    }
                    if ((inicio + nroreg) >= Math.ceil(respuesta[1].count / nroreg)) {
                        limit2 = Math.ceil(respuesta[1].count / nroreg);
                    }

                    for (let i = limit1; i <= limit2; i++) {
                        if (i === inicio) {
                            paginador += '<li class="active" style="margin-left: 0px;margin-right: 0px;"><a class="pagina" href="javascript:void(0)">' + i + '</a></li>';

                        } else {
                            paginador += '<li style="margin-left: 0px;margin-right: 0px;"><a class="pagina" href="javascript:void(0)" onclick="loadListPermisos(' + i + ',' + nroreg + ',\'' + idperfil + '\')">' + i + '</a></li>';
                        }
                    }

                    if (inicio < Math.ceil(respuesta[1].count / nroreg)) {
                        paginador += '<li class="notDisable" style="margin-left: 0px;margin-right: 0px;"><a class="pagina" href="javascript:void(0)" onclick="loadListPermisos(' + (inicio + 1) + ',' + nroreg + ',\'' + idperfil + '\')">&rsaquo;</a></li>';

                        paginador += '<li class="notDisable" style="margin-left: 0px;margin-right: 0px;"><a class="pagina" href="javascript:void(0)" onclick="loadListPermisos(' + Math.ceil(respuesta[1].count / nroreg) + ',' + nroreg + ',\'' + idperfil + '\')">&raquo;</a></li>';
                    } else {
                        paginador += '<li class="disabled" style="margin-left: 0px;margin-right: 0px;"><a class="pagina" href="javascript:void(0)">&rsaquo;</a></li>';
                        paginador += '<li class="disabled" style="margin-left: 0px;margin-right: 0px;"><a class="pagina" href="javascript:void(0)">&raquo;</a></li>';
                    }
                    paginador += '<li style="margin-left: 0px;margin-right: 0px;"><span class="label label pagina">' + Math.ceil(respuesta[1].count / nroreg) + ' Páginas</li></span>';

                    paginador += '</ul>';
                    //Fin del paginador


                    $("#spanCountRestricciones").text("(" + respuesta[1].count + ")");
                    $("#spanCountRestricciones").attr("data-count", respuesta[1].count);

                    $("#paginacion2").append(paginador);
                    //document.addEventListener('click', spanPhone, true);

                })
                .fail(function (resp) {
                    console.log("fail:" + resp.responseText);
                    console.log("fail:" + resp);
                })
        }

        function loadListPermisosCopy(inicio, nroreg, idperfil) {
            $("#spanCountRestriccionesCopy").text("(0)");
            $("#spanCountRestriccionesCopy").attr("data-count", "0");
            let nuevoinicio = (inicio - 1) * nroreg;
            $.ajax({
                url: "backend/perfilesBackend.php",
                type: "POST",
                dataType: "json",
                data: ({
                    ind: "2",
                    idperfil: idperfil,
                    inicio: nuevoinicio,
                    nroreg: nroreg
                }),
            })
                .done(function (respuesta) {
                    let htmlTags = '';
                    let ImgArrayData = '';
                    $('#tableData2Copy tbody').empty();
                    $("#paginacion2Copy").empty();
                    for (let i = 0; i < respuesta[0].length; i++) {
                        let color_fondo = "warning";
                        let color_font = "white";

                        if (respuesta[0][i].permiso == "Permitir") {
                            color_fondo = "success";
                            color_font = "white";
                        }

                        htmlTags += '<tr>';
                        htmlTags += '   <td><span class="badge badge" style="font-size:15px; font-weight:bold; color:white">' + respuesta[0][i].tipoelemento + '</span></td>';
                        htmlTags += '   <td><span style="font-size:15px; font-weight:bold; color:white">' + respuesta[0][i].modulo + '</span></td>';
                        htmlTags += '   <td><span style="font-size:15px; font-weight:bold; color:white">' + respuesta[0][i].nombrevista + '</span></td>';
                        htmlTags += '   <td style="text-align:center"><span class="badge badge" style="font-size:16px; font-weight:bold; color:' + color_font + ';">' + respuesta[0][i].elemento + '</span></td>';
                        htmlTags += '   <td style="text-align:center"><span class="badge badge-' + color_fondo + '" style="font-size:16px; font-weight:bold; color:' + color_font + ';">' + respuesta[0][i].permiso + '</span></td>';
                        htmlTags += '</tr>';

                    }

                    $('#tableData2Copy tbody').append(htmlTags);

                    //Paginador
                    let paginador = "";

                    paginador = "";

                    paginador = '<ul class="pagination justify-content-end">';

                    paginador += '<li style="margin-left: 0px;margin-right: 0px;"><span class="label label pagina">' + respuesta[1].count + ' Registros</li></span>';

                    if (inicio > 1) {
                        paginador += '<li class="notDisable" style="margin-left: 0px;margin-right: 0px;"><a class="pagina" href="javascript:void(0)" onclick="loadListPermisosCopyy(' + 1 + ',' + nroreg + ',\'' + idperfil + '\')">&laquo;</a></li>';

                        paginador += '<li class="notDisable" style="margin-left: 0px;margin-right: 0px;"><a class="pagina" href="javascript:void(0)" onclick="loadListPermisosCopy(' + (inicio - 1) + ',' + nroreg + ',\'' + idperfil + '\')">&lsaquo;</a></li>';
                    } else {
                        paginador += '<li class="disabled" style="margin-left: 0px;margin-right: 0px;" ><a class="pagina" href="javascript:void(0)">&laquo;</a></li>';
                        paginador += '<li class="disabled" style="margin-left: 0px;margin-right: 0px;"><a class="pagina" href="javascript:void(0)">&lsaquo;</a></li>';
                    }
                    let limit1 = inicio - nroreg;
                    let limit2 = inicio + nroreg;

                    if (inicio <= nroreg) {
                        limit1 = 1;
                    }
                    if ((inicio + nroreg) >= Math.ceil(respuesta[1].count / nroreg)) {
                        limit2 = Math.ceil(respuesta[1].count / nroreg);
                    }

                    for (let i = limit1; i <= limit2; i++) {
                        if (i === inicio) {
                            paginador += '<li class="active" style="margin-left: 0px;margin-right: 0px;"><a class="pagina" href="javascript:void(0)">' + i + '</a></li>';

                        } else {
                            paginador += '<li style="margin-left: 0px;margin-right: 0px;"><a class="pagina" href="javascript:void(0)" onclick="loadListPermisosCopy(' + i + ',' + nroreg + ',\'' + idperfil + '\')">' + i + '</a></li>';
                        }
                    }

                    if (inicio < Math.ceil(respuesta[1].count / nroreg)) {
                        paginador += '<li class="notDisable" style="margin-left: 0px;margin-right: 0px;"><a class="pagina" href="javascript:void(0)" onclick="loadListPermisosCopy(' + (inicio + 1) + ',' + nroreg + ',\'' + idperfil + '\')">&rsaquo;</a></li>';

                        paginador += '<li class="notDisable" style="margin-left: 0px;margin-right: 0px;"><a class="pagina" href="javascript:void(0)" onclick="loadListPermisosCopy(' + Math.ceil(respuesta[1].count / nroreg) + ',' + nroreg + ',\'' + idperfil + '\')">&raquo;</a></li>';
                    } else {
                        paginador += '<li class="disabled" style="margin-left: 0px;margin-right: 0px;"><a class="pagina" href="javascript:void(0)">&rsaquo;</a></li>';
                        paginador += '<li class="disabled" style="margin-left: 0px;margin-right: 0px;"><a class="pagina" href="javascript:void(0)">&raquo;</a></li>';
                    }
                    paginador += '<li style="margin-left: 0px;margin-right: 0px;"><span class="label label pagina">' + Math.ceil(respuesta[1].count / nroreg) + ' Páginas</li></span>';

                    paginador += '</ul>';
                    //Fin del paginador
                    $("#spanCountRestriccionesCopy").text("(" + respuesta[1].count + ")");
                    $("#spanCountRestriccionesCopy").attr("data-count", respuesta[1].count);

                    $("#paginacion2Copy").append(paginador);
                    //document.addEventListener('click', spanPhone, true);

                })
                .fail(function (resp) {
                    console.log("fail:" + resp.responseText);
                    console.log("fail:" + resp);
                })
        }

        function openProfile(idperfil) {
            $("#div_filtro_nivel").show();
            $("#div_filtro_modulo").show();
            $("#div_filtro_vista").show();

            $("#filtro_nivel").val("");
            $("#filtro_modulo").val("");
            $("#filtro_vista").val("");



            $("#imgExclamation").hide();
            $("#spanlcd").text('');
            $("#spanlcd").hide();
            $("#cancelarEdicion").hide();

            $("#lbl1").css("color", "#989B94");
            $("#lbl2").css("color", "#989B94");
            $("#lbl3").css("color", "#989B94");

            $("#txt_observa").css("background-color", "black")
            $("#inp_nombreperfil").css("background-color", "black")
            $("#sel_status").css("background-color", "black")


            $("#ModalProfile").modal("show");

            $("#imgExclamation").hide();
            $("#spanlcd").text('');
            $("#spanlcd").hide();

            $("#btnGuardarCambiosPerfil").hide();
            $("#btnEliminarPerfil").text("Eliminar Perfil");
            $("#sel_mod").css("background-color", "black");

            $("#btnEditarPerfil").show();

            $("#exampleModalLabel").empty();
            $("#exampleModalLabel").append(`<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-plus"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="12" y1="18" x2="12" y2="12"></line><line x1="9" y1="15" x2="15" y2="15"></line></svg>`);
            $("#exampleModalLabel").append("Información Del Perfil");

            $("#div_permisosAdd").show();
            $("#div_permisosDatos").hide();
            $("#row-estado").show();

            $("#btn-addPerfil").hide();

            $("#inp_nombreperfil").val("");
            $("#txt_observa").val("");
            $("#sel_status").val("0");

            $.ajax({
                url: "backend/perfilesBackend.php",
                type: "POST",
                dataType: "json",
                data: ({
                    ind: "4",
                    idperfil: idperfil
                }),
            })
                .done(function (respuesta) {
                    let htmlTags = '';
                    let idciudad = "";
                    if (respuesta != 'NO') {
                        respuesta.forEach(respuesta => {

                            $("#inp_nombreperfil").val(respuesta.nombre_perfil);
                            $("#txt_observa").val(respuesta.descripcion);
                            $("#estado").val(respuesta.status);

                            //como seria las siguientes lineas de codigo sin bootstrapSwitch

                            if (respuesta.status == "1") {
                                $("#checkestado").prop("checked", true);
                                $("#estado").val("1");
                                perfilar(idperfil, moduloid, vistaid);

                            } else {
                                $("#checkestado").prop("checked", false);
                                $("#estado").val("0");
                                perfilar(idperfil, moduloid, vistaid);

                            }

                            if (respuesta.status == "0") {
                                $("#radioStatusNO").prop("checked", true);
                                $("#spanstatusno").removeClass();
                                $("#spanstatusokcopy").removeClass();
                                $("#spanstatusno").addClass("badge badge-warning");
                                $("#spanstatusokcopy").addClass("badge");
                                perfilar(idperfil, moduloid, vistaid);

                            }

                            if (respuesta.status == "1") {
                                $("#radioStatusOK").prop("checked", true);
                                $("#spanstatusno").removeClass();
                                $("#spanstatusokcopy").removeClass();
                                $("#spanstatusokcopy").addClass("badge badge-success");
                                $("#spanstatusno").addClass("badge");
                                perfilar(idperfil, moduloid, vistaid);

                            }
                            perfilar(idperfil, moduloid, vistaid);

                        });

                    }

                    perfilar(idperfil, moduloid, vistaid);
                    traerModulos();



                })
                .fail(function (resp) {
                    // console.log("fail:" + resp.responseText);
                });

            $("#btn-addPerfil").hide();
            $("#inp_nombreperfil").attr('disabled', true);
            $("#txt_observa").attr('disabled', true);

            $("#radioStatusNO").attr('disabled', true);
            $("#radioStatusOK").attr('disabled', true);

            $("#btn-AddPermiso").show();
            $("#btnEliminarPerfil").show();

            $("#inp_dataidperfil").val(idperfil);
            $("#div_permisosTabla").show();
            loadListPermisos(1, 5, idperfil);
            perfilar(idperfil, moduloid, vistaid);

        }

        function openProfileCopy(idperfil) {
            $("#imgExclamation").hide();
            $("#spanlcd").text('');
            $("#spanlcd").hide();

            $("#lbl1").css("color", "#989B94");
            $("#lbl2").css("color", "#989B94");
            $("#lbl3").css("color", "#989B94");

            $("#txt_observacopy").css("background-color", "black")
            $("#inp_nombreperfilcopy").css("background-color", "black")
            $("#sel_status").css("background-color", "black")


            $("#ModalProfileCopy").modal("show");

            $("#imgExclamation").hide();
            $("#spanlcd").text('');
            $("#spanlcd").hide();

            $("#btnGuardarCambiosPerfilCopy").show();
            $("#sel_mod").css("background-color", "white");

            $("#exampleModalLabel").empty();
            $("#exampleModalLabel").append(`<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-plus"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="12" y1="18" x2="12" y2="12"></line><line x1="9" y1="15" x2="15" y2="15"></line></svg>`);
            $("#exampleModalLabel").append("Información Del Perfil");

            $("#div_permisosAdd").show();
            $("#div_permisosDatos").hide();

            $("#btn-addPerfil").hide();

            $("#inp_nombreperfil").val("");
            $("#txt_observa").val("");
            $("#estadocopy").val("1");

            $.ajax({
                url: "backend/perfilesBackend.php",
                type: "POST",
                dataType: "json",
                data: ({
                    ind: "4",
                    idperfil: idperfil
                }),
            })
                .done(function (respuesta) {
                    let htmlTags = '';
                    let idciudad = "";
                    if (respuesta != 'NO') {
                        respuesta.forEach(respuesta => {
                            $("#inp_nombreperfilcopy").val(respuesta.nombre_perfil + " COPIA");
                            $("#txt_observacopy").val(respuesta.descripcion);
                            $("#estadocopy").val(respuesta.status);
                            if (respuesta.status == "1") {
                                $("#checkestadocopy").prop("checked", true);
                                $("#estadocopy").val("1");
                            } else {
                                $("#checkestadocopy").prop("checked", false);
                                $("#estadocopy").val("0");
                            }
                        });

                    }

                })
                .fail(function (resp) {
                    // console.log("fail:" + resp.responseText);
                });

            $("#btn-addPerfil").hide();
            $("#inp_nombreperfil").attr('disabled', true);
            $("#txt_observa").attr('disabled', true);

            $("#radioStatusNO").attr('disabled', true);
            $("#radioStatusOK").attr('disabled', true);

            $("#btn-AddPermiso").show();
            $("#div_filtros").show();

            $("#btnEliminarPerfil").show();

            $("#inp_dataidperfilcopy").val(idperfil);
            $("#div_permisosTabla").show();
            loadListPermisosCopy(1, 5, idperfil);
            perfilar(idperfil, moduloid, vistaid);

        }

        function nuevoProfile() {

            $("#ModalProfile").modal("show");


            $("#exampleModalLabel").empty();
            $("#exampleModalLabel").append(`<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-plus"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="12" y1="18" x2="12" y2="12"></line><line x1="9" y1="15" x2="15" y2="15"></line></svg>`);
            $("#exampleModalLabel").append("Registro de Nuevo Perfil");


            $("#div_permisosAdd").hide();
            $("#div_permisosDatos").hide();
            $("#row-estado").hide();

            $("#btn-addPerfil").show();
            $("#cancelarEdicion").show();

            $("#inp_nombreperfil").val("");
            $("#txt_observa").val("");

            $("#radioStatusNO").attr('disabled', false);
            $("#radioStatusOK").attr('disabled', false);

            $("#radioStatusOK").prop("checked", true);
            $("#spanstatusno").removeClass();
            $("#spanstatusokcopy").removeClass();
            $("#spanstatusokcopy").addClass("badge badge-success");
            $("#spanstatusno").addClass("badge");

            $("#inp_nombreperfil").attr('disabled', false)
            $("#txt_observa").attr('disabled', false)
            //$("#sel_status").attr('disabled',true)

            $("#btnEditarPerfil").hide();

            $("#btnEliminarPerfil").hide();
            $("#btnGuardarCambiosPerfil").hide();

            $("#inp_dataidperfil").val("");
            autocompletarPerfiles();

            perfilar(idperfil, moduloid, vistaid);

        }

        function loadModulosList() {
            select = $("#sel_mod");
            $.ajax({
                url: "backend/perfilesBackend.php",
                type: "POST",
                dataType: "json",
                data: ({
                    ind: "5"
                }),
            })
                .done(function (respuesta) {

                    let htmlTags = '';
                    select.empty();
                    select.append('<option value="0"></option>');
                    if (respuesta != 'NO') {
                        for (let i in respuesta) {
                            htmlTags = '<option value="' + respuesta[i].id + '">' + respuesta[i].modulo + '</option>';
                            select.append(htmlTags);
                            htmlTags = '';
                        }
                    }
                })
                .fail(function (resp) {
                    console.log("fail:" + resp.responseText);
                    console.log("fail:" + resp);
                })
        }

        function loadVistasList() {
            select = $("#sel_vista");
            select.empty();
            $.ajax({
                url: "backend/perfilesBackend.php",
                type: "POST",
                dataType: "json",
                data: ({
                    ind: "6",
                    modulo: $("#sel_mod").val()
                }),
            })
                .done(function (respuesta) {

                    let htmlTags = '';
                    select.empty();
                    select.append('<option value="0"></option>');
                    if (respuesta != 'NO') {
                        for (let i in respuesta) {
                            htmlTags = '<option data-idmodulo="' + respuesta[i].idmodulo + '" data-file="' + respuesta[i].file + '" value="' + respuesta[i].id + '">' + respuesta[i].nombrevista + '</option>';
                            select.append(htmlTags);
                            htmlTags = '';
                        }
                    }
                })
                .fail(function (resp) {
                    console.log("fail:" + resp.responseText);
                    console.log("fail:" + resp);
                })
        }

        $("#btn-AddPermiso").click(function () {

            $("#div_permisosDatos").show();
            $("#div_permisosTabla").hide();
            $("#btn-AddPermiso").hide();
            $("#div_filtros").hide();
            $("#row-estado").hide();
            $("#div_filtro_modulo").hide();
            $("#div_filtro_vista").hide();
            $("#div_filtro_nivel").hide();

            $("#btnEditarPerfil").hide();

            $("#sel_elem").empty();
            $("#sel_tipoRestriccion").empty();
            $("#btnEliminarPerfil").hide();
        })

        $("#btn-addPerfil").click(function () {
            $("#imgExclamation").hide();
            $("#spanlcd").text('');
            $("#spanlcd").hide();

            $("#lbl1").css("color", "#989B94");
            $("#lbl2").css("color", "#989B94");
            $("#lbl3").css("color", "#989B94");

            $("#txt_observa").css("background-color", "black")
            $("#inp_nombreperfil").css("background-color", "black")
            $("#sel_status").css("background-color", "black")


            if ($("#inp_nombreperfil").val() == "") {
                $("#imgExclamation").show();
                $("#spanlcd").text("Escriba el nombre del perfil");
                $("#lbl1").css("color", "red");
                $("#inp_nombreperfil").css("background-color", "#FFBDAE");
                $("#spanlcd").fadeIn(1000);
            }


            if ($("#txt_observa").val() == "") {
                $("#imgExclamation2").show();
                $("#spanlcd2").text("Escriba la descripción del perfil");
                $("#lbl2").css("color", "red");
                $("#txt_observa").css("background-color", "#FFBDAE");
                $("#spanlcd2").fadeIn(1000);
            }


            if (!$("#imgExclamation").is(":hidden")) { }

            let status = "";
            if ($('input:radio[name=radioStatus]:checked').val() == "ACTIVO") {
                status = '1'
            }
            if ($('input:radio[name=radioStatus]:checked').val() == "INACTIVO") {
                status = '0'
            }

            if ($("#inp_nombreperfil").val() != "" && $("#txt_observa").val() != "") {

                $.ajax({
                    url: "backend/perfilesBackend.php",
                    type: "POST",
                    dataType: "json",
                    data: ({
                        ind: "3",
                        inp_nombreperfil: $("#inp_nombreperfil").val().toUpperCase(),
                        txt_observa: $("#txt_observa").val().toUpperCase(),
                        estado: '1',
                        sel_status: status
                    }),
                })
                    .done(function (respuesta) {
                        let htmlTags = '';
                        if (respuesta.respuesta == 'SI') {
                            Swal.fire(
                                'Nació un nuevo perfil' + ' \n ' + $("#inp_nombreperfil").val().toUpperCase(),
                                'Ahora puede agregar permisos.',
                                "success"
                            );
                            getID();
                        }

                        if (respuesta.respuesta == 'NO') {
                            Swal.fire(
                                'No se puede realizar esta acción',
                                'Ya existe un perfil con este nombre, se mostrará a continuación.',
                                "error"
                            );

                        } else {


                            $("#btn-addPerfil").hide();
                            $("#inp_nombreperfil").attr('disabled', true)
                            $("#txt_observa").attr('disabled', true)
                            $("#sel_status").attr('disabled', true)

                            $("#div_permisosAdd").show();
                            $("#div_permisosDatos").hide();
                            $("#div_permisosTabla").show();
                            $("#btn-AddPermiso").show();
                            $("#div_filtros").show();
                            $("#btnEliminarPerfil").show();
                            $("#btnEditarPerfil").show();
                            $("#exampleModalLabel").empty();
                            $("#exampleModalLabel").append(`<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-plus"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="12" y1="18" x2="12" y2="12"></line><line x1="9" y1="15" x2="15" y2="15"></line></svg>`);
                            $("#exampleModalLabel").append("Información Del Perfil");

                            loadProfiles(1, 5, '', '');
                        }
                    })
                    .fail(function (resp) {
                        // console.log("fail:" + resp.responseText);
                    })
            }

            function getID() {
                $.ajax({
                    url: "backend/perfilesBackend.php",
                    type: "POST",
                    dataType: "json",
                    data: ({
                        ind: "3.1"
                    }),
                })
                    .done(function (respuesta) {

                        if (respuesta != 'NO') {
                            for (let i in respuesta) {
                                $("#inp_dataidperfil").val(respuesta[i].id_perfil);

                            }

                        }

                    })
                    .fail(function (resp) {
                        // console.log("fail:" + resp.responseText);
                    })
            }
        })

        $("#btn-addPerfilCopy").click(function () {
            $("#imgExclamation").hide();
            $("#spanlcd").text('');
            $("#spanlcd").hide();

            $("#lbl1").css("color", "#989B94");
            $("#lbl2").css("color", "#989B94");
            $("#lbl3").css("color", "#989B94");

            $("#txt_observacopy").css("background-color", "black")
            $("#inp_nombreperfilcopy").css("background-color", "black")
            $("#sel_status").css("background-color", "black")

            if ($("#inp_nombreperfilcopy").val() == "") {
                $("#imgExclamation").show();
                $("#spanlcdcopy").text("Escriba el nombre del perfil");
                $("#lbl1").css("color", "red");
                $("#inp_nombreperfilcopy").css("background-color", "#FFBDAE");
                $("#spanlcdcopy").fadeIn(1000);
                return;
            }

            if (!$("#imgExclamationCopy").is(":hidden")) { }

            $.ajax({
                url: "backend/perfilesBackend.php",
                type: "POST",
                dataType: "json",
                data: ({
                    ind: "3",
                    inp_nombreperfil: $("#inp_nombreperfilcopy").val().toUpperCase(),
                    txt_observa: $("#txt_observacopy").val().toUpperCase(),
                    estado: $("#estadocopy").val()
                }),
            })
                .done(function (respuesta) {
                    if (respuesta.respuesta == 'NO') {
                        Swal.fire(
                            'No se puede realizar esta acción',
                            'Ya existe un perfil con este nombre, por favor escriba otro nombre.',
                            "error"
                        );

                    }

                    if (respuesta.respuesta == 'SI') {
                        let idperfil = $("#inp_dataidperfilcopy").val();
                        $.ajax({
                            url: "backend/perfilesBackend.php",
                            type: "POST",
                            dataType: "json",
                            data: ({
                                ind: "3.2",
                                idperfil: idperfil
                            })
                        }).done(function (resp) {
                            if (resp.respuesta == "NO") {
                                Swal.fire(
                                    "Error",
                                    'No se pudieron copiar los permisos.',
                                    "error"
                                );

                            }
                        })
                        Swal.fire(
                            'Nació un nuevo perfil' + ' \n ' + $("#inp_nombreperfilcopy").val(),
                            'Ahora puede agregar permisos.',
                            "success"
                        );

                        getID();
                    }

                    $("#btn-addPerfilCopy").hide();
                    $("#inp_nombreperfilcopy").attr('disabled', true)
                    $("#txt_observacopy").attr('disabled', true)
                    $("#sel_status").attr('disabled', true)


                    $("#div_permisosAddCopy").show();
                    $("#div_permisosTablaCopy").show();
                    $("#exampleModalLabelCopy").empty();
                    $("#exampleModalLabelCopy").append(`<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-plus"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="12" y1="18" x2="12" y2="12"></line><line x1="9" y1="15" x2="15" y2="15"></line></svg>`);
                    $("#exampleModalLabelCopy").append("Información Del Perfil");

                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                    loadProfiles(1, 5, '', '');
                })
                .fail(function (resp) {
                    // console.log("fail:" + resp.responseText);
                })

            function getID() {
                $.ajax({
                    url: "backend/perfilesBackend.php",
                    type: "POST",
                    dataType: "json",
                    data: ({
                        ind: "3.1"
                    }),
                })
                    .done(function (respuesta) {

                        if (respuesta != 'NO') {
                            for (let i in respuesta) {
                                $("#inp_dataidperfil").val(respuesta[i].id_perfil);
                            }
                        }

                    })
                    .fail(function (resp) {
                        // console.log("fail:" + resp.responseText);
                    })
            }
        })

        $("#btn-GuardarPermiso").click(function () {
            $("#imgExclamation").hide();
            $("#spanlcd").text('');
            $("#spanlcd").hide();

            $("#sel_mod").css("background-color", "white");

            if ($("#sel_mod").val() == 0) {
                $("#imgExclamation").show();
                $("#spanlcd").text("Seleccione un modulo");
                $("#sel_mod").css("background-color", "#FFBDAE");
                $("#spanlcd").fadeIn(1000);
            };

            if ($("#sel_tipoRestriccion").val() == 0) {
                if ($("#sel_mod").val() != '0' || $("#imgExclamation").val() != '0' || $("#imgExclamation").val() != '0') {
                    $("#imgExclamation").show();
                    $("#spanlcd").text("Seleccione el tipo de restricción");
                    $("#sel_mod").css("background-color", "#FFBDAE");
                    $("#spanlcd").fadeIn(1000);
                    return;
                }
            };

            let tipoelemento = "";
            if ($("#sel_mod").val() != "0") {
                tipoelemento = "MODULO"
            }
            if ($("#sel_vista").val() != "0") {
                tipoelemento = "VISTA"
            }
            if ($("#sel_elem").val() != "0") {
                tipoelemento = $("#sel_elem").find("option:selected").text().split(":")[0];
            }

            let elemento = $("#sel_elem").find("option:selected").val();
            if (elemento == "0") {
                if (tipoelemento == "MODULO") {
                    elemento = $("#sel_mod").find("option:selected").text();
                }
                if (tipoelemento == "VISTA") {
                    elemento = $("#sel_vista").find("option:selected").text();
                }
            }

            if ($("#sel_mod").val() != 0) {
                $("#div_permisosDatos").hide();
                $("#div_permisosTabla").show();
                $("#btn-AddPermiso").show();
                $("#div_filtro_modulo").show();
                $("#div_filtro_nivel").show();
                $("#div_filtro_vista").show();

                // $("#div_filtros").show();

                $("#btnEditarPerfil").show();
                $("#btnEliminarPerfil").show();

                let validarYaIngresado;
                $.ajax({
                    url: "backend/perfilesBackend.php",
                    type: "POST",
                    dataType: "json",
                    data: ({
                        ind: "2.1",
                        idperfil: $("#inp_dataidperfil").val(),
                        modulo: $("#sel_mod").find("option:selected").val(),
                        vista: $("#sel_vista").find("option:selected").val()
                    }),
                })
                    .done(function (respuesta) {
                        for (let i in respuesta) {
                            if ($("#sel_elem").find("option:selected").val() != 0) {
                                if (respuesta[i].elemento == $("#sel_elem").find("option:selected").val()) {
                                    validarYaIngresado = true;
                                    break;
                                } else {
                                    validarYaIngresado = false;
                                    break;
                                }
                            } else {
                                if (respuesta[i].idmodulo == $("#sel_mod").find("option:selected").val() && respuesta[i].idvista == $("#sel_vista").find("option:selected").val() && respuesta[i].elemento == respuesta[i].nombrevista) {
                                    Swal.fire(
                                        'La vista que intenta restringir ya fué perfilada.',
                                        'debe eliminar la restricción actual o elegir otra vista para perfilar.',
                                        "warning"
                                    );

                                    $("#sel_mod").val('0');
                                    $("#sel_vista").val('0');
                                    $("#sel_elem").val('0');
                                    $("#sel_tipoRestriccion").empty();
                                    validarYaIngresado = "NO";
                                    break;
                                } else {
                                    $.ajax({
                                        url: "backend/perfilesBackend.php",
                                        type: "POST",
                                        dataType: "json",
                                        data: ({
                                            ind: "7",
                                            idperfil: $("#inp_dataidperfil").val(),
                                            idmodulo: $("#sel_mod").find("option:selected").val(),
                                            idvista: $("#sel_vista").find("option:selected").val(),
                                            elemento: elemento,
                                            permiso: $("#sel_tipoRestriccion").find("option:selected").text(),
                                            tipoelemento: tipoelemento.trim()
                                        }),
                                    })
                                        .done(function (respuesta) {
                                            let htmlTags = '';
                                            if (respuesta.respuesta == 'SI') {
                                                Swal.fire(
                                                    'Se Agregó el nuevo permiso al perfil',
                                                    'Ahora puede agregar permisos.',
                                                    "success"
                                                );

                                                loadListPermisos(1, 5, $("#inp_dataidperfil").val());
                                                $("#sel_mod").val('0');
                                                $("#sel_vista").val('0');
                                                $("#sel_elem").val('0');
                                                $("#sel_tipoRestriccion").empty();
                                                validarYaIngresado = "NO";
                                            }
                                        })
                                        .fail(function (resp) {
                                            console.log("fail:" + resp.responseText);
                                        })
                                    break;
                                }
                            }
                        }

                        if (validarYaIngresado == true) {
                            Swal.fire(
                                'El elemento que intenta restringir ya fué perfilado.',
                                'debe eliminar la restricción actual o elegir otro elemento para perfilar.',
                                "warning"
                            );

                            $("#sel_mod").val('0');
                            $("#sel_vista").val('0');
                            $("#sel_elem").val('0');
                            $("#sel_tipoRestriccion").empty();
                        } else if (validarYaIngresado == false) {
                            $.ajax({
                                url: "backend/perfilesBackend.php",
                                type: "POST",
                                dataType: "json",
                                data: ({
                                    ind: "7",
                                    idperfil: $("#inp_dataidperfil").val(),
                                    idmodulo: $("#sel_mod").find("option:selected").val(),
                                    idvista: $("#sel_vista").find("option:selected").val(),
                                    elemento: elemento,
                                    permiso: $("#sel_tipoRestriccion").find("option:selected").text(),
                                    tipoelemento: tipoelemento.trim()
                                }),
                            })
                                .done(function (respuesta) {
                                    let htmlTags = '';
                                    if (respuesta.respuesta == 'SI') {
                                        Swal.fire(
                                            'Se Agregó el nuevo permiso al perfil',
                                            'Ahora puede agregar permisos.',
                                            "success"
                                        );

                                        loadListPermisos(1, 5, $("#inp_dataidperfil").val());
                                        $("#sel_mod").val('0');
                                        $("#sel_vista").val('0');
                                        $("#sel_elem").val('0');
                                        $("#sel_tipoRestriccion").empty();
                                    }
                                })
                                .fail(function (resp) {
                                    console.log("fail:" + resp.responseText);
                                })
                        }
                    })
                    .fail(function (resp) {
                        console.log("fail:" + resp.responseText);
                        console.log("fail:" + resp);
                    })
            }
        })

        $("#btn-CancelarAgregarPermiso").click(function () {
            $("#imgExclamation").hide();
            $("#spanlcd").text('');
            $("#spanlcd").hide();

            $("#sel_mod").css("background-color", "white");

            $("#div_permisosDatos").hide();
            $("#div_permisosTabla").show();
            $("#btn-AddPermiso").show();
            $("#div_filtros").show();
            $("#row-estado").show();
            $("#div_filtro_modulo").show();
            $("#div_filtro_vista").show();
            $("#div_filtro_nivel").show();

            $("#sel_mod").val("0");
            $("#sel_vista").val("0");
            $("#sel_elem").val("0");
            $("#sel_tipoRestriccion").val("0");

            $("#sel_elem").empty();
            $("#sel_elem").append('<option value="0"></option>');
            $("#sel_vista").empty();
            $("#sel_vista").append('<option value="0"></option>');
            $("#btnEditarPerfil").show();
            $("#btnEliminarPerfil").show();



        })

        $("#closemodal").click(function () {
            $("#div_permisosDatos").hide();
            $("#div_permisosTabla").show();
            $("#btn-AddPermiso").show();
            $("#div_filtros").show();

            $("#sel_mod").val("0");
            $("#sel_vista").val("0");
            $("#sel_elem").val("0");
            $("#sel_tipoRestriccion").val("0");
        })

        $("#sel_mod").change(function () {
            $("#sel_vista").empty();
            $("#sel_vista").append('<option value="0"></option>');

            $("#sel_elem").empty();
            $("#sel_elem").append('<option value="0"></option>');

            if ($("#sel_mod").val() == "0") {
                $("#frameshow").prop("src", "about:blank");
            } else {
                loadVistasList();
            }

            $("#sel_elem").empty();
            $("#sel_elem").append('<option value="0"></option>');

            $("#sel_tipoRestriccion").empty();
            $("#sel_tipoRestriccion").append('<option selected value="0"></option>');
        })

        $("#sel_vista").change(function () {
            if ($("#sel_vista").val() == "0") {
                $("#sel_elem").empty();
                $("#sel_elem").append('<option value="0"></option>');
                $("#frameshow").prop("src", "about:blank");
            }

            $("#frameshow").prop("src", "../" + $("#sel_vista").find("option:selected").attr("data-file"));


            Swal.fire({
                title: 'Cargando!',
                timer: 3500,
                padding: '2em',
                didOpen: () => {
                    Swal.showLoading()
                }
            }).then(function (result) {
                setTimeout(() => {
                    console.log('entro');
                    $("#txt_observa").val("");
                    let Iframehandle = document.getElementById('frameshow');
                    let content = Iframehandle.contentWindow.document.body.innerHTML;

                    $("#frameshow")[0].contentWindow.document.lastChild.style.zoom = "0.7";
                    let a = "";
                    let i = 0;
                    $("#frameshow").contents().find("body").contents().find("table").each(function (i) {
                        a = a + $(this).html();
                        i = i + 1;

                        //$("#txt_observa").val(a);
                        let contador = 1;
                        let namesArray = [];
                        htmlTags = "";
                        $("#sel_elem").empty();
                        htmlTags = '<option value="0"></option>';
                        $('#sel_elem').append(htmlTags);
                        let EleTagName = document.getElementById('frameshow').contentWindow.document.querySelectorAll('*')
                        EleTagName.forEach(element => {
                            if (element.getAttribute("data-validar") == "SI") {
                                let variable = element.getAttribute("name");
                                let incluido = namesArray.includes(variable);
                                if (!incluido) {
                                    namesArray.push(variable)
                                    htmlTags = '<option value="' + element.getAttribute("name") + '"> ' + element.getAttribute("data-tipo") + ': ' + contador + " - " + element.getAttribute("data-apodo") + '</option>';
                                    $('#sel_elem').append(htmlTags);
                                    htmlTags = '';
                                    contador += 1;
                                }
                            }
                        });

                        // contador = 1;}
                        //elimino los elemtnor repetidos del select
                        let seen = {};
                        $('#sel_elem option').each(function () {
                            let txt = $(this).text();
                            if (seen[txt])
                                $(this).remove();
                            else
                                seen[txt] = true;
                        });
                    });

                }, 2000);

            })


            $("#sel_tipoRestriccion").empty();
            $("#sel_tipoRestriccion").append('<option value="0"></option>');
            $("#sel_tipoRestriccion").append('<option value="2">Bloquear</option>');
        })

        $("#sel_elem").change(function () {
            let EleTagName = document.getElementById('frameshow').contentWindow.document.getElementsByTagName('*')
            for (let i = 0; i < EleTagName.length; i++) {
                let element = EleTagName[i];
                if (element.getAttribute("data-paint") == "SI") {
                    element.setAttribute('data-paint', 'NO');
                    element.style.border = "";
                }
            }
            let el = $("#sel_elem").val();
            let optseleccionado = document.querySelector("#sel_elem").options.selectedIndex;
            let textoValidar = document.querySelector("#sel_elem").options[optseleccionado].text;



            if (textoValidar.search("BOTON") != "-1") {
                $("#sel_tipoRestriccion").empty();
                $("#sel_tipoRestriccion").append('<option value="0"></option>');
                $("#sel_tipoRestriccion").append('<option value="1">Bloquear</option>');
                $("#sel_tipoRestriccion").append('<option value="2">Ocultar</option>');
            } else if (textoValidar.search("TEXTBOX") != "-1") {
                $("#sel_tipoRestriccion").empty();
                $("#sel_tipoRestriccion").append('<option value="0"></option>');
                $("#sel_tipoRestriccion").append('<option value="2">Ocultar</option>');
                $("#sel_tipoRestriccion").append('<option value="3">Ofuscar</option>');
            } else if (textoValidar.search("LABEL") != "-1") {
                $("#sel_tipoRestriccion").empty();
                $("#sel_tipoRestriccion").append('<option value="0"></option>');
                $("#sel_tipoRestriccion").append('<option value="2">Ocultar</option>');
                $("#sel_tipoRestriccion").append('<option value="3">Ofuscar</option>');
            } else if (textoValidar.search("ARCHIVO") != "-1") {
                $("#sel_tipoRestriccion").empty();
                $("#sel_tipoRestriccion").append('<option value="0"></option>');
                $("#sel_tipoRestriccion").append('<option value="1">Bloquear</option>');
                $("#sel_tipoRestriccion").append('<option value="2">Ocultar</option>');
            } else if (textoValidar.search("TABLA_CONTENEDOR") != "-1") {
                $("#sel_tipoRestriccion").empty();
                $("#sel_tipoRestriccion").append('<option value="0"></option>');
                $("#sel_tipoRestriccion").append('<option value="2">Ocultar</option>');
                $("#sel_tipoRestriccion").append('<option value="3">Ofuscar</option>');
            } else if (textoValidar.search("CONTENEDOR") != "-1") {
                $("#sel_tipoRestriccion").empty();
                $("#sel_tipoRestriccion").append('<option value="0"></option>');
                $("#sel_tipoRestriccion").append('<option value="2">Ocultar</option>');
            } else if (textoValidar.search("SELECTOR") != "-1") {
                $("#sel_tipoRestriccion").empty();
                $("#sel_tipoRestriccion").append('<option value="0"></option>');
                $("#sel_tipoRestriccion").append('<option value="1">Bloquear</option>');
                $("#sel_tipoRestriccion").append('<option value="2">Ocultar</option>');
            } else if (textoValidar.search("SECCION") != "-1") {
                $("#sel_tipoRestriccion").empty();
                $("#sel_tipoRestriccion").append('<option value="0"></option>');
                $("#sel_tipoRestriccion").append('<option value="1">Bloquear</option>');
                $("#sel_tipoRestriccion").append('<option value="2">Ocultar</option>');
            } else {
                $("#sel_tipoRestriccion").empty();
                $("#sel_tipoRestriccion").append('<option value="0"></option>');
                $("#sel_tipoRestriccion").append('<option value="1">Bloquear</option>');
                $("#sel_tipoRestriccion").append('<option value="2">Ocultar</option>');
            }

            let TagName = document.getElementById('frameshow').contentWindow.document.getElementById(el);
            document.getElementById('frameshow').contentWindow.document.getElementById(el).style.border = "red dotted 3px";
            TagName.setAttribute('data-paint', 'SI');
        })

        function deletePermiso(id) {

            Swal.fire({
                title: '¿Estás seguro?',
                text: 'No hay vuelta atrás!',
                icon: 'warning', // 'type' se cambia por 'icon'
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar permiso',
                padding: '2em'
            }).then((result) => {
                if (result.value) {
                    Swal.fire(
                        'Eliminado!',
                        'el permiso ha sido eliminado.',
                        "success"
                    );
                    $.ajax({
                        url: "backend/perfilesBackend.php",
                        type: "POST",
                        dataType: "json",
                        data: ({
                            ind: "8",
                            id: id
                        }),
                    })
                        .done(function (respuesta) {
                            let id_vista = document.getElementById("filtro_vista").value;
                            loadListPermisos(1, 5, $("#inp_dataidperfil").val(), id_vista);
                        })
                        .fail(function (resp) {
                            console.log("fail:" + resp.responseText);
                        })

                } else
                    Swal.fire(
                        'Cancelado',
                        'todo sigue igual :)',
                        "error"
                    );
            })
        }

        $("#btnEditarPerfil").click(function () {
            $("#inp_nombreperfil").attr("disabled", false);
            $("#txt_observa").attr("disabled", false);
            $("#sel_status").attr("disabled", false);
            $("#btnGuardarCambiosPerfil").show();
            $("#btnEditarPerfil").hide();
            $("#btn-AddPermiso").hide();
            $("#div_filtros").hide();
            $("#btnEliminarPerfil").hide();
            $("#cancelarEdicion").show();

            $("#radioStatusNO").attr('disabled', false);
            $("#radioStatusOK").attr('disabled', false);

        })

        $("#btnEliminarPerfil").click(function () {

            if ($("#btnEliminarPerfil").text() == "Cancelar") {
                $("#inp_nombreperfil").attr("disabled", true);
                $("#txt_observa").attr("disabled", true);
                $("#sel_status").attr("disabled", true);
                $("#btnGuardarCambiosPerfil").hide();
                $("#btn-AddPermiso").show();
                $("#div_filtros").show();
                $("#btnEditarPerfil").show();
                $("#btnEliminarPerfil").text("Eliminar Perfil");

                $("#radioStatusNO").attr('disabled', true);
                $("#radioStatusOK").attr('disabled', true);
            }

            if ($("#btnEliminarPerfil").text() == "Eliminar Perfil") {

                if (parseInt($("#spanCountRestricciones").attr("data-count")) > 0) {
                    Swal.fire(
                        'No se puede eliminar!!!',
                        'El perfil tiene cargos asignados',
                        "error"
                    );
                };

                Swal.fire({
                    title: 'Confirma Eliminar el perfil ' + $("#inp_nombreperfil").val().toUpperCase(),
                    text: 'No hay vuelta atrás!',
                    icon: 'warning', // 'type' se cambia por 'icon'
                    showCancelButton: true,
                    confirmButtonText: 'Sí, eliminar permiso',
                    padding: '2em'
                }).then((result) => {
                    if (result.value) {

                        $.ajax({
                            url: "backend/perfilesBackend.php",
                            type: "POST",
                            dataType: "json",
                            data: ({
                                ind: "10",
                                idperfil: $("#inp_dataidperfil").val()
                            }),
                        })
                            .done(function (respuesta) {
                                if (respuesta.respuesta == 'SI') {
                                    Swal.fire(
                                        'Hecho!!!',
                                        'El perfil ha sido eliminado.',
                                        "success"
                                    );

                                    $("#ModalProfile").modal("hide");
                                    loadProfiles(1, 5, '', '');
                                }
                                if (respuesta.respuesta == 'NO') {
                                    // if (respuesta.error.includes("foreign key")) {

                                    // }
                                    Swal.fire(
                                        'No se pudo eliminar!!!',
                                        'El perfil a eliminar está siendo usado',
                                        "error"
                                    );
                                }
                            })
                            .fail(function (resp) {
                                console.log("fail:" + resp.responseText);
                            })


                    } else
                        Swal.fire(
                            'Cancelado !!!',
                            'todo sigue igual :)',
                            "error"
                        );
                })
            }

        })

        $("#btnGuardarCambiosPerfil").click(function () {
            $("#imgExclamation").hide();
            $("#spanlcd").text('');
            $("#spanlcd").hide();

            $("#lbl1").css("color", "#989B94");
            $("#lbl2").css("color", "#989B94");
            $("#lbl3").css("color", "#989B94");

            $("#txt_observa").css("background-color", "black")
            $("#inp_nombreperfil").css("background-color", "black")
            $("#sel_status").css("background-color", "black")


            if ($("#inp_nombreperfil").val() == "") {
                $("#imgExclamation").show();
                $("#spanlcd").text("Escriba el nombre del perfil");
                $("#lbl1").css("color", "red");
                $("#inp_nombreperfil").css("background-color", "#FFBDAE");
                $("#spanlcd").fadeIn(1000);
            }


            if ($("#txt_observa").val() == "") {
                $("#imgExclamation").show();
                $("#spanlcd").text("Escriba la descripción del perfil");
                $("#lbl2").css("color", "red");
                $("#txt_observa").css("background-color", "#FFBDAE");
                $("#spanlcd").fadeIn(1000);
            }

            let status = "";
            if ($('input:radio[name=radioStatus]:checked').val() == "ACTIVO") {
                status = '1'
            }
            if ($('input:radio[name=radioStatus]:checked').val() == "INACTIVO") {
                status = '0'
            }

            $("#radioStatusNO").attr('disabled', true);
            $("#radioStatusOK").attr('disabled', true);

            $.ajax({
                url: "backend/perfilesBackend.php",
                type: "POST",
                dataType: "json",
                data: ({
                    ind: "11",
                    idperfil: $("#inp_dataidperfil").val(),
                    perfil: $("#inp_nombreperfil").val().toUpperCase(),
                    descripcion: $("#txt_observa").val().toUpperCase(),
                    status: status
                }),
            })
                .done(function (respuesta) {
                    if (respuesta.respuesta == 'SI') {
                        Swal.fire(
                            'Bien Hecho!',
                            'Se Actualizo la información del perfil.',
                            "success"
                        );

                        loadProfiles(1, 5, '', '');
                    }

                    if (respuesta.respuesta == 'NO') {
                        if (!respuesta.error) {
                            Swal.fire(
                                'Revisemos Primero!',
                                'El nombre del perfil que intenta asignar ya esta siendo usado',
                                "warning"
                            );

                        } else if (respuesta.error.includes('Duplicate entry')) {
                            Swal.fire(
                                'Revisemos Primero!',
                                'El nombre del perfil que intenta asignar ya esta siendo usado',
                                "warning"
                            );
                        }
                    }

                })
                .fail(function (resp) {
                    console.log("fail:" + resp.responseText);
                })
        })

        $("#btnfiltrar").click(function () {
            let tipofiltro = "";
            let filtro = "";


            if ($("#txtNombre_f").val() != "") {
                tipofiltro = "nombre";
                filtro = $("#txtNombre_f").val();
            }

            loadProfiles(1, 5, tipofiltro, filtro);

        })

        $("#inp_nombreperfil").keyup(function () {
            let start = this.selectionStart,
                end = this.selectionEnd;

            $(this).val($(this).val().toUpperCase());
            this.value = this.value.replace(/[^0-9A-Z ]/g, '');

            this.setSelectionRange(start, end);

        })

        $("#txt_observa").keyup(function () {

            let start = this.selectionStart,
                end = this.selectionEnd;
            $(this).val($(this).val().toUpperCase());
            this.value = this.value.replace(/[^0-9A-Z,. ]/g, '');

            this.setSelectionRange(start, end);

        })

        $('input:radio[name=radioStatus]').change(function () {



            if ($('input:radio[name=radioStatus]:checked').val() == "ACTIVO") {
                $("#spanstatusno").removeClass();
                $("#spanstatusokcopy").removeClass();
                $("#spanstatusokcopy").addClass("bg-success badges");
                $("#spanstatusno").addClass("badge");
            }

            if ($('input:radio[name=radioStatus]:checked').val() == "INACTIVO") {
                $("#spanstatusno").removeClass();
                $("#spanstatusokcopy").removeClass();
                $("#spanstatusno").addClass("bg-warning badges");
                $("#spanstatusokcopy").addClass("badges");
            }
        })

        function autocompletarPerfiles() {

            $.ajax({
                url: "backend/perfilesBackend.php",
                type: "POST",
                dataType: "json",
                data: ({
                    ind: "12"
                }),
            })
                .done(function (respuesta) {

                    let select = $("#listaInpPerfil");


                    let htmlTags = '';
                    select.empty();
                    if (respuesta.respuesta != 'NO') {
                        for (let i in respuesta) {
                            htmlTags = '<option value="' + respuesta[i].nombre_perfil + '">' + respuesta[i].nombre_perfil + '</option>';
                            select.append(htmlTags);
                            htmlTags = '';
                        }
                    }

                })
                .fail(function (resp) {
                    // console.log("fail:" + resp.responseText);
                })


        }

        $('#filtroPerfil').keyup(function () {
            if (Number(this.value)) {
                let tipofiltro = "id"
                let filtro = this.value;
                loadProfiles(1, 5, tipofiltro, filtro);
            } else {
                let tipofiltro = "nombre";
                let filtro = this.value;
                loadProfiles(1, 5, tipofiltro, filtro);
            }
        })

        document.getElementById("filtro_modulo").addEventListener("change", (e) => {
            let id_modulo = e.target.value;

            $.ajax({
                url: "backend/perfilesBackend.php",
                type: "POST",
                dataType: "json",
                data: ({
                    ind: "14",
                    id_modulo: id_modulo
                }),
            })
                .done(function (respuesta) {
                    let options = `<option value=""> </option>`;
                    respuesta.forEach((element) => {
                        options += `<option value="${element[0]}">${element[2]}</option>`;
                        document.getElementById("filtro_vista").innerHTML = options;
                    })
                })
        })

        document.getElementById("filtro_vista").addEventListener("change", (e) => {
            let id_vista = e.target.value;
            let id_perfil = document.getElementById("inp_dataidperfil").value;

            loadListPermisos(1, 5, id_perfil, id_vista);

            // document.getElementById("div_filtro_nivel").style.display = "grid";

            $.ajax({
                url: "backend/perfilesBackend.php",
                type: "POST",
                dataType: "json",
                data: ({
                    ind: "16",
                    id_vista: id_vista,
                    id_perfil: id_perfil
                }),
            })
                .done(function (respuesta) {
                    let options = `<option value=""> </option>`;
                    respuesta.forEach((element) => {
                        options += `<option value="${element[0]}">${element[0]}</option>`;
                        document.getElementById("filtro_nivel").innerHTML = options;
                    })
                })
        })

        document.getElementById("filtro_nivel").addEventListener("change", (e) => {
            let id_vista = document.getElementById("filtro_vista").value;
            let id_perfil = document.getElementById("inp_dataidperfil").value;
            let tipo = e.target.value;

            loadListPermisos(1, 5, id_perfil, id_vista, tipo);
        })

        function traerModulos() {
            $.ajax({
                url: "backend/perfilesBackend.php",
                type: "POST",
                dataType: "json",
                data: ({
                    ind: "17"
                }),
            })

                .done(function (respuesta) {

                    let options = '<option value=""> </option>';

                    respuesta.rta2.forEach(function (element) {
                        options += '<option value="' + element.id + '">' + element.modulo + '</option>';
                    });
                    document.getElementById("filtro_modulo").innerHTML = options;
                })
        }
    </script>



</body>

</html>