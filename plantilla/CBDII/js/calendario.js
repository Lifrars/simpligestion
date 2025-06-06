$(document).ready(function () {
    console.log(id);
    console.log(idS);
    var today = new Date();
    var year = today.getFullYear();
    var month = String(today.getMonth() + 1).padStart(2, '0'); // Agrega ceros a la izquierda si es necesario
    var day = String(today.getDate()).padStart(2, '0'); // Agrega ceros a la izquierda si es necesario

    // Establecer la fecha mínima permitida en el campo de fecha
    document.getElementById('fechareserva').min = year + '-' + month + '-' + day;
    listarCalenda(id,idS);
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
        console.log(data);
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
        console.log(eventos);

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


document.addEventListener('DOMContentLoaded', function() {
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
        var horaInicio = document.getElementById('horaInicio');
        var horaFin = document.getElementById('horaFin');
        var selectedHoraInicio = parseInt(horaInicio.value.split(':')[0]);
        populateSelect('horaFin', selectedHoraInicio + 1, 24);
    }

    populateSelect('horaInicio', 1, 24);
    populateSelect('horaFin', 1, 24);

    document.getElementById('horaInicio').addEventListener('change', updateHoraFin);
});

async function Save_Calendario() {
    var fechareservaInput = document.getElementById('fechareserva').value;
    let can =await canAddEvent(fechareservaInput);
    console.log(can)
   
    var horaInicioSelect = document.getElementById('horaInicio').value;
    var horaFinSelect = document.getElementById('horaFin').value;
    var comentarioInput = document.getElementById('comentario').value;

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

    // Iterar sobre las entradas de FormData (opcional)
    // for (let entry of formData.entries()) {
    //     console.log(entry[0] + ': ' + entry[1]);
    // }

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
            limpiarCampos();
            // Ejecutar la función listarUsuarios() con los parámetros necesarios
            listarCalenda(id, idS);
            // Cerrar la modal y sus elementos
            
            // Selecciona el botón por su ID
            location.reload();
            
        
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
    console.log(numEventper)
    return numEventper > numEvent ? true : false;
}

async function getNumberofEvents(idUser, idArea, fecha) {
    const formData = new FormData();
    formData.append('idUser', idUser)
    formData.append('idArea', idArea)
    formData.append('fecha', fecha)
    formData.append('ind', 6)

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
        console.log("Data recibida del servidor:");
        console.log(data); // Verificar la estructura de la respuesta JSON

        if (data.rta && data.rta.num) {
            console.log("Número recibido del servidor:");
            console.log(data.rta.num); // Verificar si se está accediendo correctamente al número

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
