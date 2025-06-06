$(document).ready(async function () {

    var today = new Date();
    var year = today.getFullYear();
    var month = String(today.getMonth() + 1).padStart(2, '0'); // Agrega ceros a la izquierda si es necesario
    var day = String(today.getDate()).padStart(2, '0'); // Agrega ceros a la izquierda si es necesario

    document.getElementById('fechareserva').min = `${year}-${month}-${day}`;

    // Cargar horarios del área y luego inicializar selects
    await cargarHorarioArea(id);
    
    listarCalenda(id, idS);
});
function listarCalenda(id, idS) {
    let ladata = new FormData();
    ladata.append('ind', 5);
    ladata.append('idArea', id);
    ladata.append('idUser', idS);
    fetch('backend/calendarBackend.php', {
        method: 'POST',
        body: ladata,
    }).then(function (response) {
        return response.json();
    }).then(function (data) {
        $('#tablaAreas tbody').empty();
        $("#paginacion").empty();
        let htmlTags = "";
        let eventos = [];
        data.rta.forEach(rta => {
            let eventData = {
                title: rta.comentario, // Título del evento
                start: rta.fecha_reserva + 'T' + rta.hora_inicio, // Fecha y hora de inicio
                end: rta.fecha_reserva + 'T' + rta.hora_fin, // Fecha y hora de fin
                description: rta.descripcion,
                area: "Test"
            };
            eventos.push(eventData);
        });

        $('#calendar').fullCalendar({
            locale: 'es',
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            events: eventos,
            eventRender: function (event, element) {
                element.qtip({
                    content: {
                        text: event.description
                    },
                    style: {
                        classes: 'qtip-dark',
                        tip: {
                            corner: true,
                            mimic: 'center',
                            width: 16,
                            height: 8
                        }
                    }
                });
            },
            eventClick: function(event) {
                $('#eventDescription').text(event.description);
                $('#eventArea').text('Descripcion: ' + event.area);
                $('#eventStart').text('Inicio: ' + event.start.format('YYYY-MM-DD HH:mm'));
                $('#eventEnd').text('Fin: ' + event.end.format('YYYY-MM-DD HH:mm'));
                $('#eventModal').modal('show');
            }
        });
    });
}

async function cargarHorarioArea(id) {
    const formData = new FormData();
    formData.append('ind', 8);
    formData.append('idArea', id);

    try {
        const response = await fetch('backend/calendarBackend.php', {
            method: 'POST',
            body: formData,
        });

        if (!response.ok) {
            throw new Error('Error al obtener el horario del área');
        }

        const data = await response.json();
        console.log("📥 Respuesta completa del backend:", data);

        if (!data.rta || typeof data.rta !== 'object') {
            console.error("⚠️ Error: `data.rta` no es un objeto válido.", data.rta);
            return;
        }

        const horario = data.rta; 
        // Mostrar los datos en el panel
document.getElementById('areaNombre').textContent = horario.nombre || '-';
document.getElementById('areaUbicacion').textContent = horario.ubicacion || '-';
document.getElementById('areaCapacidad').textContent = horario.capacidad || 0;
document.getElementById('areaHorario').textContent = horario.horario || '-';
document.getElementById('areaHoraInicio').textContent = horario.hora_inicio || '-';
document.getElementById('areaHoraFin').textContent = horario.hora_fin || '-';
document.getElementById('areaDias').textContent = horario.dias_disponibles || '-';
document.getElementById('areaDescripcion').textContent = horario.descripcion || '-';

// Mostrar contenedor
document.getElementById('infoArea').style.display = 'block';

        const horaInicio = horario.hora_inicio;
        const horaFin = horario.hora_fin;
        const diasDisponibles = horario.dias_disponibles || "";

        if (horaInicio && horaFin) {


            const horaInicioNum = parseInt(horaInicio.split(':')[0]);
            const horaFinNum = parseInt(horaFin.split(':')[0]);

            populateSelect('horaInicio', horaInicioNum, horaFinNum);
            populateSelect('horaFin', horaInicioNum + 1, horaFinNum);
            document.getElementById('horaFin').setAttribute("data-horaFin", horaFinNum);
        } else {
            console.error("❌ Error: `hora_inicio` o `hora_fin` no están definidos en la respuesta.");
        }

        // 🔹 Llamar a la función para restringir los días permitidos en el input
        actualizarDiasPermitidos(diasDisponibles);

    } catch (error) {
        console.error('❌ Error en la solicitud:', error);
    }
}

// ⚠️ Asegurar que el input de fecha tenga la restricción de días válidos
function actualizarDiasPermitidos(diasString) {
    const mapaDias = {
        "Domingo": 0, "Lunes": 1, "Martes": 2, "Miercoles": 3, "Miércoles": 3,
        "Jueves": 4, "Viernes": 5, "Sabado": 6, "Sábado": 6
    };

    let diasPermitidos = diasString.split(',')
        .map(dia => mapaDias[dia.trim()])
        .filter(num => num !== undefined);


    const inputFecha = document.getElementById('fechareserva');

    inputFecha.addEventListener('input', function () {
        let fechaSeleccionada = new Date(this.value);
        let diaSemana = fechaSeleccionada.getDay(); // 0 = Domingo, ..., 6 = Sábado

        if (!diasPermitidos.includes(diaSemana)) {
            alert("🚫 Día no permitido. Selecciona un día válido.");
            this.value = ""; // Borra la fecha inválida
        }
    });

    // Deshabilita visualmente los días prohibidos en el calendario
    let minDate = new Date();
    let maxDate = new Date();
    maxDate.setMonth(minDate.getMonth() + 2); // Permitir reservas hasta 2 meses después

    let fechasBloqueadas = [];
    let fechaTemp = new Date(minDate);

    while (fechaTemp <= maxDate) {
        if (!diasPermitidos.includes(fechaTemp.getDay())) {
            fechasBloqueadas.push(fechaTemp.toISOString().split('T')[0]);
        }
        fechaTemp.setDate(fechaTemp.getDate() + 1);
    }

    inputFecha.setAttribute("data-fechasBloqueadas", fechasBloqueadas.join(","));
}



function populateSelect(selectId, startHour, endHour) {
    var select = document.getElementById(selectId);
    select.innerHTML = '<option value="">Selecciona una hora</option>';

    for (var hour = startHour; hour <= endHour; hour++) {
        var option = document.createElement('option');
        option.value = hour.toString().padStart(2, '0') + ':00:00';
        option.text = hour.toString().padStart(2, '0') + ':00:00';
        select.appendChild(option);
    }
}

function updateHoraFin() {
    var horaInicio = document.getElementById('horaInicio').value;
    if (!horaInicio) return;

    var selectedHoraInicio = parseInt(horaInicio.split(':')[0]);
    var horaFinMax = Math.min(selectedHoraInicio + 2, parseInt(document.getElementById('horaFin').getAttribute("data-horaFin")));

    populateSelect('horaFin', selectedHoraInicio + 1, horaFinMax);
}

// Asociar el evento change al select de horaInicio
document.getElementById('horaInicio').addEventListener('change', updateHoraFin);

async function Save_Calendario() {
    var fechareservaInput = document.getElementById('fechareserva').value;
    let can =await canAddEvent(fechareservaInput);
    console.log("")
    console.log(can)
   
    var horaInicioSelect = document.getElementById('horaInicio').value;
    var horaFinSelect = document.getElementById('horaFin').value;
    var comentarioInput = document.getElementById('comentario').value;


    if (!fechareservaInput || !horaInicioSelect || !horaFinSelect || !comentarioInput) {
        Swal.fire({
            icon: 'warning',
            title: 'Campos incompletos',
            text: 'Por favor, completa todos los campos antes de continuar.',
        });
        return;
    }
    if (can) {
        // Mostrar alerta con Toastr
        new Noty({
            type: 'error',
            text: 'Ya no puedes agregar más eventos esta semana',
            layout: 'topRight', // Posición de la notificación en la pantalla
            theme: 'sunset', // Tema de la notificación
            timeout: 3000, // Duración de la notificación en milisegundos (3 segundos en este caso)
            progressBar: true, // Mostrar barra de progreso para la duración de la notificación


        }).show();
        
        
        return;
    } 
    
    
    const formData = new FormData();
    formData.append("ind", 3)
    formData.append('idArea', id)
    formData.append('idUser', idS)
    formData.append('fechareserva', fechareservaInput);
    formData.append('horaInicio', horaInicioSelect);
    formData.append('horaFin', horaFinSelect);
    formData.append('comentario', comentarioInput);


    fetch('backend/calendarBackend.php', {
        method: 'POST',
        body: formData,
    })
    .then(response => {
        if (response.ok) {
            return response.json();
        } else {
            throw new Error('Error al actualizar el área');
        }
    })
    .then(data => {
        if (data.rta === 'ok') {
            // Limpiar los campos del formulario

        } else {
            throw new Error('Error al actualizar el área: ' + data.error);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        // Mostrar SweetAlert de error en caso de fallo en la solicitud
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.message || 'Hubo un problema al guardar el evento',
        });
    });
}

function limpiarCampos() {
  document.getElementById('fechareserva').value = '';
  document.getElementById('horaInicio').value = '';
  document.getElementById('horaFin').value = '';
  document.getElementById('comentario').value = '';
}

async function canAddEvent(fecha){
    numEvent = 1;
    numEventper =await getNumberofEvents(idS,id,fecha)
    console.log(" data")
    console.log(numEventper)
    return numEventper > numEvent ? true : false;
}

async function getNumberofEvents(idUser, idArea, fecha) {
    const formData = new FormData();
    formData.append('idUser', idUser)
    formData.append('idArea', idArea)
    formData.append('fecha', fecha)
    formData.append('ind', 6)

      for (let [key, value] of formData.entries()) {
    console.log(`${key}: ${value}`);
  }
    return await fetch('backend/calendarBackend.php', {
        method: 'POST',
        body: formData,
    })
    .then(response => {
        if (response.ok) {
            return response.json();
        } else {
            throw new Error('Error al actualizar el área');
        }
    })
    .then(data => {
 // Verificar la estructura de la respuesta JSON

        if (data.rta && data.rta.num) {
            return parseInt(data.rta.num);
        } else {
            return 0;
        }
    })
    .catch(error => {
        console.error("Error al procesar la respuesta del servidor:", error);
        // Manejar el error adecuadamente, puedes lanzar una alerta o devolver 0, dependiendo de lo que necesites
        return 0;
    });
}
