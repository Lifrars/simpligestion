// reportes.js - Funcionalidad para el sistema de reportes

// Variables globales
let areasDisponibles = [];
let estadosDisponibles = [];
let usuariosDisponibles = [];

// Cargar datos para filtros
async function cargarDatosParaFiltros() {
    try {
        // Áreas
        const resAreas = await fetch('backend/reportesBackend.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'ind=obtener_areas'
        });
        const dataAreas = await resAreas.json();
        if (dataAreas.success) areasDisponibles = dataAreas.areas;

        // Estados
        const resEstados = await fetch('backend/reportesBackend.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'ind=obtener_estados'
        });
        const dataEstados = await resEstados.json();
        if (dataEstados.success) estadosDisponibles = dataEstados.estados;

        // Usuarios
        const resUsuarios = await fetch('backend/reportesBackend.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'ind=obtener_usuarios'
        });
        const dataUsuarios = await resUsuarios.json();
        if (dataUsuarios.success) usuariosDisponibles = dataUsuarios.usuarios;

    } catch (error) {
        console.error('Error cargando datos para filtros:', error);
        mostrarError('Error cargando datos para los filtros');
    }
}

// Inicializar eventos
function inicializarEventos() {
    document.getElementById('btnReporteReservas').addEventListener('click', mostrarModalReporteReservas);
    document.getElementById('btnReporteAreas').addEventListener('click', mostrarModalReporteAreas);
    document.getElementById('btnReporteUsuarios').addEventListener('click', mostrarModalReporteUsuarios);
}

// Mostrar error
function mostrarError(mensaje) {
    alert(mensaje); // O usar tu sistema de alertas
}

// =======================
// 🔹 MODAL: REPORTE RESERVAS
// =======================
function mostrarModalReporteReservas() {
    const modalHTML = `
        <div class="modal fade" id="modalReporteReservas" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-calendar-check"></i> Generar Reporte de Reservas
                        </h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <form id="formReporteReservas">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="fecha_inicio_reservas">Fecha Inicio:</label>
                                    <input type="date" class="form-control" id="fecha_inicio_reservas" name="fecha_inicio">
                                </div>
                                <div class="col-md-6">
                                    <label for="fecha_fin_reservas">Fecha Fin:</label>
                                    <input type="date" class="form-control" id="fecha_fin_reservas" name="fecha_fin">
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <label for="area_reservas">Área:</label>
                                    <select class="form-control" id="area_reservas" name="area_id">
                                        <option value="">Todas las áreas</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="estado_reservas">Estado:</label>
                                    <select class="form-control" id="estado_reservas" name="estado_id">
                                        <option value="">Todos los estados</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <label for="usuario_reservas">Usuario:</label>
                                    <select class="form-control" id="usuario_reservas" name="usuario_id">
                                        <option value="">Todos los usuarios</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="formato_reservas">Formato:</label>
                                    <select class="form-control" id="formato_reservas" name="formato">
                                        <option value="pdf">PDF</option>
                                        <option value="excel">Excel</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" id="generarReporteReservas">
                            <i class="fas fa-file-download"></i> Generar Reporte
                        </button>
                    </div>
                </div>
            </div>
        </div>`;

    const contenedor = document.createElement('div');
    contenedor.innerHTML = modalHTML;
    document.body.appendChild(contenedor);

    // Llenar selects
    areasDisponibles.forEach(a => {
        const opt = new Option(a.nombre, a.id);
        document.getElementById('area_reservas').appendChild(opt);
    });

    estadosDisponibles.forEach(e => {
        const opt = new Option(e.nombre, e.id);
        document.getElementById('estado_reservas').appendChild(opt);
    });

    usuariosDisponibles.forEach(u => {
        const opt = new Option(u.nombre, u.id);
        document.getElementById('usuario_reservas').appendChild(opt);
    });

    $('#modalReporteReservas').modal('show');

    document.getElementById('generarReporteReservas').addEventListener('click', async () => {
        const form = document.getElementById('formReporteReservas');
        const formData = new FormData(form);
        formData.append('ind', 'generar_reporte_reservas');

        try {
            const response = await fetch('backend/reportesBackend.php', {
                method: 'POST',
                body: formData
            });

            const blob = await response.blob();
            const formato = formData.get('formato') || 'pdf';
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `reporte_reservas.${formato === 'excel' ? 'xlsx' : 'pdf'}`;
            a.click();
            URL.revokeObjectURL(url);
            $('#modalReporteReservas').modal('hide');
        } catch (error) {
            console.error('Error al generar reporte:', error);
            mostrarError('No se pudo generar el reporte.');
        }
    });
}

// =======================
// 🔹 MODAL: REPORTE ÁREAS
// =======================
function mostrarModalReporteAreas() {
    const modalHTML = `
        <div class="modal fade" id="modalReporteAreas" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fas fa-map"></i> Reporte de Áreas</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <form id="formReporteAreas">
                            <div class="form-group">
                                <label for="formato_areas">Formato:</label>
                                <select class="form-control" id="formato_areas" name="formato">
                                    <option value="pdf">PDF</option>
                                    <option value="excel">Excel</option>
                                </select>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" id="generarReporteAreas">
                            <i class="fas fa-file-download"></i> Generar Reporte
                        </button>
                    </div>
                </div>
            </div>
        </div>`;

    const contenedor = document.createElement('div');
    contenedor.innerHTML = modalHTML;
    document.body.appendChild(contenedor);

    $('#modalReporteAreas').modal('show');

    document.getElementById('generarReporteAreas').addEventListener('click', async () => {
        const form = document.getElementById('formReporteAreas');
        const formData = new FormData(form);
        formData.append('ind', 'generar_reporte_areas');

        try {
            const response = await fetch('backend/reportesBackend.php', {
                method: 'POST',
                body: formData
            });

            const blob = await response.blob();
            const formato = formData.get('formato') || 'pdf';
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `reporte_areas.${formato === 'excel' ? 'xlsx' : 'pdf'}`;
            a.click();
            URL.revokeObjectURL(url);
            $('#modalReporteAreas').modal('hide');
        } catch (error) {
            console.error('Error al generar reporte:', error);
            mostrarError('No se pudo generar el reporte de áreas.');
        }
    });
}

// =======================
// 🔹 MODAL: REPORTE USUARIOS
// =======================
function mostrarModalReporteUsuarios() {
    const modalHTML = `
        <div class="modal fade" id="modalReporteUsuarios" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fas fa-users"></i> Reporte de Usuarios</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <form id="formReporteUsuarios">
                            <div class="form-group">
                                <label for="formato_usuarios">Formato:</label>
                                <select class="form-control" id="formato_usuarios" name="formato">
                                    <option value="pdf">PDF</option>
                                    <option value="excel">Excel</option>
                                </select>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" id="generarReporteUsuarios">
                            <i class="fas fa-file-download"></i> Generar Reporte
                        </button>
                    </div>
                </div>
            </div>
        </div>`;

    const contenedor = document.createElement('div');
    contenedor.innerHTML = modalHTML;
    document.body.appendChild(contenedor);

    $('#modalReporteUsuarios').modal('show');

    document.getElementById('generarReporteUsuarios').addEventListener('click', async () => {
        const form = document.getElementById('formReporteUsuarios');
        const formData = new FormData(form);
        formData.append('ind', 'generar_reporte_usuarios');

        try {
            const response = await fetch('backend/reportesBackend.php', {
                method: 'POST',
                body: formData
            });

            const blob = await response.blob();
            const formato = formData.get('formato') || 'pdf';
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `reporte_usuarios.${formato === 'excel' ? 'xlsx' : 'pdf'}`;
            a.click();
            URL.revokeObjectURL(url);
            $('#modalReporteUsuarios').modal('hide');
        } catch (error) {
            console.error('Error al generar reporte:', error);
            mostrarError('No se pudo generar el reporte de usuarios.');
        }
    });
}

// =======================
// 🔹 INICIALIZACIÓN
// =======================
document.addEventListener('DOMContentLoaded', async () => {
    await cargarDatosParaFiltros();
    inicializarEventos();
});
