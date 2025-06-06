$(document).ready(function () {
  let nuevoinicio = 1;
  let nroreg = 5;
  lisrarTickets(2, nuevoinicio, nroreg);
  
  // Establecer fecha actual automáticamente
  setFechaActual();
});

// Función para establecer la fecha actual
function setFechaActual() {
  const today = new Date();
  const formattedDate = today.toISOString().split('T')[0];
  document.getElementById('fecha_creacion').value = formattedDate;
}

// Validaciones en tiempo real
document.getElementById('descripcion').addEventListener('input', function() {
  validateDescripcion(this);
});

// Función de validación para descripción
function validateDescripcion(input) {
  const value = input.value.trim();
  const minLength = 10;
  const maxLength = 500;
  
  // Remover clases previas
  input.classList.remove('is-valid', 'is-invalid');
  
  // Obtener o crear elemento de feedback
  let feedback = input.parentNode.querySelector('.invalid-feedback');
  if (!feedback) {
    feedback = document.createElement('div');
    feedback.className = 'invalid-feedback';
    input.parentNode.appendChild(feedback);
  }
  
  if (value.length === 0) {
    input.classList.add('is-invalid');
    feedback.textContent = 'La descripción es obligatoria.';
    return false;
  } else if (value.length < minLength) {
    input.classList.add('is-invalid');
    feedback.textContent = `La descripción debe tener al menos ${minLength} caracteres.`;
    return false;
  } else if (value.length > maxLength) {
    input.classList.add('is-invalid');
    feedback.textContent = `La descripción no puede exceder ${maxLength} caracteres.`;
    return false;
  } else {
    input.classList.add('is-valid');
    feedback.textContent = '';
    return true;
  }
}

// Validación completa del formulario
function validateForm() {
  const descripcion = document.getElementById('descripcion');
  
  let isValid = true;
  
  // Validar descripción
  if (!validateDescripcion(descripcion)) {
    isValid = false;
  }
  
  return isValid;
}

document.getElementById('buscador').addEventListener('input', function () {
    const filtro = this.value.toLowerCase();
    const filas = document.querySelectorAll('#tablaUsuarios tbody tr');

    filas.forEach(fila => {
        const textoFila = fila.textContent.toLowerCase();
        fila.style.display = textoFila.includes(filtro) ? '' : 'none';
    });
});

document.getElementById("addUsuario").addEventListener("click", function () {
  // Controlar la visibilidad del campo estado según el perfil
  if (idPerfil == "1") {
    $('#estadoRow').show();
  } else {
    $('#estadoRow').hide();
  }

  // Habilitar descripcion
  document.getElementById('descripcion').disabled = false;
  // La fecha de creación ya no se habilita porque será automática
  document.getElementById('fecha_creacion').disabled = true;

  // Limpiar los campos y establecer fecha actual
  limpiarCampos();
  setFechaActual();
  
  // Cambiar título del modal
  document.getElementById('largeModalLabel').textContent = 'Crear Nuevo Ticket';
  
  // Ocultar el botón de actualizar
  document.getElementById("actualizarTicket").style.display = "none";
  // Mostrar el botón de guardar
  document.getElementById("guardarTicket").style.display = "block";
});

function leerdatos(id) {
  // Limpiar los campos
  limpiarCampos();

  let ladata = new FormData();
  ladata.append('id', id);
  ladata.append('ind', 1);

  // Mostrar loading
  Swal.fire({
    title: 'Cargando...',
    text: 'Obteniendo datos del ticket',
    allowOutsideClick: false,
    didOpen: () => {
      Swal.showLoading();
    }
  });

  fetch('backend/ticketsBackend.php', {
    method: 'POST',
    body: ladata,
  })
    .then(response => {
      if (response.ok) {
        return response.json();
      } else {
        throw new Error('Error al obtener los datos del ticket');
      }
    })
    .then(data => {
      Swal.close(); // Cerrar loading
      
      if (data.rta) {
        $('#largeModal').modal('show');
        let ticket = data.rta[0];

        // Cambiar título del modal
        document.getElementById('largeModalLabel').textContent = 'Editar Ticket #' + ticket.id;

        // Llenar los campos del formulario con los datos del ticket
        document.getElementById('idTicket').value = ticket.id;
        document.getElementById('descripcion').value = ticket.descripcion;
        document.getElementById('fecha_creacion').value = ticket.fecha_creacion;
        document.getElementById('estado').value = ticket.estado;

        // Mostrar u ocultar el campo estado según el perfil
        let idPerfil = document.getElementById('idPerfil').value;
        if (idPerfil == "1") {
          $('#estadoRow').show();
          // Bloquear los demás campos
          document.getElementById('descripcion').disabled = true;
          document.getElementById('fecha_creacion').disabled = true;
        } else {
          $('#estadoRow').hide();
          // Desbloquear descripción, fecha sigue bloqueada
          document.getElementById('descripcion').disabled = false;
          document.getElementById('fecha_creacion').disabled = true;
        }

        // Ocultar el botón de guardar
        document.getElementById("guardarTicket").style.display = "none";
        // Mostrar el botón de actualizar
        document.getElementById("actualizarTicket").style.display = "block";
      } else if (data.message) {
        throw new Error(data.message);
      } else {
        throw new Error('No se encontraron datos del ticket');
      }
    })
    .catch(error => {
      console.error('Error:', error);
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: error.message,
        confirmButtonColor: '#3085d6'
      });
    });
}

function limpiarCampos() {
  document.getElementById('idTicket').value = '';
  document.getElementById('descripcion').value = '';
  document.getElementById('fecha_creacion').value = '';
  document.getElementById('estado').value = 'abierto';
  
  // Limpiar validaciones visuales
  document.getElementById('descripcion').classList.remove('is-valid', 'is-invalid');
  const feedback = document.querySelector('.invalid-feedback');
  if (feedback) {
    feedback.textContent = '';
  }
}

function lisrarTickets(ind, inicio, nroreg) {
  let nuevoinicio = (inicio - 1) * parseInt(nroreg);

  // Recogemos el id perfil oculto de idPerfil
  let idPerfil = document.getElementById('idPerfil').value;
  // Recogemos el id usuario oculto de idUsuario
  let idUsuario = document.getElementById('idUsuario').value;

  let ladata = new FormData();
  ladata.append('ind', ind);
  ladata.append('idPerfil', idPerfil);
  ladata.append('idUsuario', idUsuario);
  ladata.append('nuevoinicio', nuevoinicio);
  ladata.append('nroreg', nroreg);

  fetch('backend/ticketsBackend.php', {
    method: 'POST',
    body: ladata,
  }).then(function (response) {
    return response.json();
  }).then(function (data) {
    $('#tablaUsuarios tbody').empty();
    $("#paginacion").empty();
    let htmlTags = "";
    console.log(data);

    // Mostrar u ocultar la columna USUARIO
    if (idPerfil == "1") {
      $('.col-usuario').show();
    } else {
      $('.col-usuario').hide();
    }

    let i = 0;
    data.rta2.forEach(function (item) {
      i = i + 1;
      
      // Determinar clase de estado para styling
      let estadoClass = '';
      let estadoBadge = '';
      switch(item.estado.toLowerCase()) {
        case 'abierto':
          estadoClass = 'badge bg-success';
          estadoBadge = '<span class="' + estadoClass + '">Abierto</span>';
          break;
        case 'en_proceso':
          estadoClass = 'badge bg-warning';
          estadoBadge = '<span class="' + estadoClass + '">En Proceso</span>';
          break;
        case 'cerrado':
          estadoClass = 'badge bg-secondary';
          estadoBadge = '<span class="' + estadoClass + '">Cerrado</span>';
          break;
        default:
          estadoBadge = item.estado;
      }

      htmlTags += '<tr>';
      htmlTags += '     <td><strong>#' + item.id + '</strong></td>';
      if (idPerfil == "1") {
        htmlTags += '     <td>' + item.nombre_completo + '</td>';
      }
      htmlTags += '     <td class="text-truncate" style="max-width: 200px;" title="' + item.descripcion + '">' + item.descripcion + '</td>';
      htmlTags += '     <td>' + item.fecha_creacion + '</td>';
      htmlTags += '     <td>' + estadoBadge + '</td>';
  htmlTags += '     <td>';
htmlTags += '         <a class="me-3" id="detailUsuario" onclick="detailUsuario(' + item.id + ');" name="detailUsuario" data-validar="SI" data-tipo="TABLA_MODAL" data-apodo="Detalles Usuario" style="cursor: pointer;">';
htmlTags += '             <img src="//ppi.miclickderecho.com/plantilla/assets/img/icons/eye.svg" alt="Ver detalles">';
htmlTags += '         </a>';
htmlTags += '         <a class="me-3" id="editUsuario" onclick="leerdatos(' + item.id + ');" name="editUsuario" data-validar="SI" data-tipo="TABLA_MODAL" data-apodo="Editar Usuario" style="cursor: pointer;">';
htmlTags += '             <img src="//ppi.miclickderecho.com/plantilla/assets/img/icons/edit.svg" alt="Editar">';
htmlTags += '         </a>';
htmlTags += '         <a class="me-3" id="deleteUsuario" onclick="deleteUsuario(' + item.id + ');" name="deleteUsuario" data-validar="SI" data-tipo="TABLA_MODAL" data-apodo="Eliminar Usuario" style="cursor: pointer;">';
htmlTags += '             <img src="//ppi.miclickderecho.com/plantilla/assets/img/icons/delete.svg" alt="Eliminar">';
htmlTags += '         </a>';
htmlTags += '     </td>';

      htmlTags += ' </tr>';
    });
    $('#tablaUsuarios tbody').append(htmlTags);

    // Paginador (código original mantenido)
    let paginador = "";
    paginador += '<ul class="pagination justify-content-end">';
    paginador += '<li style="margin-left: 0px;margin-right: 0px;"><span class="label label pagina">' + data.rta + ' Registros</span></li>';

    if (inicio > 1) {
      paginador += '<li class="notDisable" style="margin-left: 0px;margin-right: 0px;"><a class="pagina" href="javascript:void(0)" onclick="lisrarTickets(\'2\', 1, ' + nroreg + ', \'\', \'\', \'\', \'\', \'\', \'\')">&laquo;</a></li>';
      paginador += '<li class="notDisable" style="margin-left: 0px;margin-right: 0px;"><a class="pagina" href="javascript:void(0)" onclick="lisrarTickets(\'2\', ' + (inicio - 1) + ', ' + nroreg + ', \'\', \'\', \'\', \'\', \'\', \'\')">&lsaquo;</a></li>';
    } else {
      paginador += '<li class="disabled" style="margin-left: 0px;margin-right: 0px;"><a class="pagina" href="javascript:void(0)">&laquo;</a></li>';
      paginador += '<li class="disabled" style="margin-left: 0px;margin-right: 0px;"><a class="pagina" href="javascript:void(0)">&lsaquo;</a></li>';
    }

    var limit1 = inicio - nroreg;
    var limit2 = inicio + nroreg;

    if (inicio <= parseInt(nroreg)) {
      limit1 = 1;
    }
    if ((inicio + nroreg) >= Math.ceil(data.rta / parseInt(nroreg))) {
      limit2 = Math.ceil(data.rta / parseInt(nroreg));
    }
    for (let i = limit1; i <= limit2; i++) {
      if (i === inicio) {
        paginador += '<li class="active" style="margin-left: 0px;margin-right: 0px;"><a class="pagina" href="javascript:void(0)">' + i + '</a></li>';
      } else {
        paginador += '<li style="margin-left: 0px;margin-right: 0px;"><a class="pagina" href="javascript:void(0)" onclick="lisrarTickets(\' 2\', ' + i + ' , ' + nroreg + ' , \'\', \'\', \'\', \'\', \'\', \'\')">' + i + '</a></li>';
      }
    }
    if (inicio < Math.ceil(data.rta / parseInt(nroreg))) {
      paginador += '<li class="notDisable" style="margin-left: 0px;margin-right: 0px;"><a class="pagina" href="javascript:void(0)" onclick="lisrarTickets(' + "2" + ',' + (inicio + 1) + ',' + nroreg + ',\' \',\'\',\'\',\'\',\'\',\'\')">&rsaquo;</a></li>';
      paginador += '<li class="notDisable" style="margin-left: 0px;margin-right: 0px;"><a class="pagina" href="javascript:void(0)" onclick="lisrarTickets(' + " 2" + ',' + Math.ceil(data.rta / nroreg) + ',' + nroreg + ',\' \',\'\',\'\',\'\',\'\',\'\')">&raquo;</a></li>';
    } else {
      paginador += '<li class="disabled" style="margin-left: 0px;margin-right: 0px;"><a class="pagina" href="javascript:void(0)">&rsaquo;</a></li>';
      paginador += '<li class="disabled" style="margin-left: 0px;margin-right: 0px;"><a class="pagina" href="javascript:void(0)">&raquo;</a></li>';
    }
    paginador += '<li style="margin-left: 0px;margin-right: 0px;"><span class="label label pagina">' + Math.ceil(data.rta / parseInt(nroreg)) + ' Páginas</span></li>';
    paginador += '</ul>';

    let tablaUsuarios = document.querySelector('#tablaUsuarios');
    $('#paginacion').append(paginador);
    tablaUsuarios.append();
  }).catch(function (error) {
    console.error("Error al obtener productos:", error);
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: 'Error al cargar los tickets. Por favor, recargue la página.',
      confirmButtonColor: '#3085d6'
    });
  });
}

function Save_ticket() {
  // Validar formulario antes de enviar
  if (!validateForm()) {
    Swal.fire({
      icon: 'warning',
      title: 'Datos Incompletos',
      text: 'Por favor, complete todos los campos correctamente.',
      confirmButtonColor: '#3085d6'
    });
    return;
  }

  // Recogemos el id usuario oculto de idUsuario
  let idUsuario = document.getElementById('idUsuario').value;
  // Obtener los valores de los campos de entrada
  var descripcion = document.getElementById('descripcion').value.trim();
  var fecha_creacion = document.getElementById('fecha_creacion').value;
  var estado = document.getElementById('estado').value || 'abierto';

  let ladata = new FormData();
  ladata.append('idUsuario', idUsuario);
  ladata.append('descripcion', descripcion);
  ladata.append('fecha_creacion', fecha_creacion);
  ladata.append('estado', estado);
  ladata.append('ind', 3);

  // Mostrar loading
  Swal.fire({
    title: 'Guardando...',
    text: 'Creando el ticket',
    allowOutsideClick: false,
    didOpen: () => {
      Swal.showLoading();
    }
  });

  // Realizar solicitud fetch al backend
  fetch('backend/ticketsBackend.php', {
    method: 'POST',
    body: ladata,
  })
    .then(response => {
      if (response.ok) {
        return response.json();
      } else {
        throw new Error('Error al guardar el ticket');
      }
    })
    .then(data => {
      if (data.rta === 'ok') {
        // Limpiar los campos del formulario
        limpiarCampos();
        // Ejecutar la función lisrarTickets() con los parámetros necesarios
        lisrarTickets(2, 1, 5);
        // Cerrar la modal y sus elementos
        $('#largeModal').modal('hide');
        $('.modal-backdrop').remove();
        Swal.fire({
          icon: 'success',
          title: '¡Éxito!',
          text: 'El ticket ha sido creado correctamente.',
          confirmButtonColor: '#3085d6',
          timer: 2000,
          timerProgressBar: true
        });
      } else {
        // Mostrar SweetAlert de error
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Hubo un problema al guardar el ticket. Por favor, inténtelo de nuevo.',
          confirmButtonColor: '#3085d6'
        });
      }
    })
    .catch(error => {
      console.error('Error:', error);
      // Mostrar SweetAlert de error en caso de fallo en la solicitud
      Swal.fire({
        icon: 'error',
        title: 'Error de Conexión',
        text: 'No se pudo conectar con el servidor. Verifique su conexión a internet.',
        confirmButtonColor: '#3085d6'
      });
    });
}

function detailUsuario(id) {
  let ladata = new FormData();
  ladata.append('id', id);
  ladata.append('ind', 6);

  // Mostrar loading
  Swal.fire({
    title: 'Cargando...',
    text: 'Obteniendo detalles del ticket',
    allowOutsideClick: false,
    didOpen: () => {
      Swal.showLoading();
    }
  });

  fetch('backend/ticketsBackend.php', {
    method: 'POST',
    body: ladata,
  })
    .then(response => {
      if (response.ok) {
        return response.json();
      } else {
        throw new Error('Error al obtener los datos del ticket');
      }
    })
    .then(data => {
      Swal.close(); // Cerrar loading
      $('#modalMostrarUsuario').modal('show');
      console.log(data);
      document.getElementById('showdescripcion').textContent = data.rta.descripcion;
      document.getElementById('showFecha_creacion').textContent = data.rta.fecha_creacion;
      document.getElementById('showEstadp').textContent = data.rta.estado || data.rta.nombre_completo;
      document.getElementById('showidUsuario').textContent = data.rta.idUsuario;
    })
    .catch(error => {
      console.error('Error:', error);
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Hubo un problema al obtener los detalles del ticket.',
        confirmButtonColor: '#3085d6'
      });
    });
}

function Update_ticket() {
  // Validar formulario antes de enviar
  if (!validateForm()) {
    Swal.fire({
      icon: 'warning',
      title: 'Datos Incompletos',
      text: 'Por favor, complete todos los campos correctamente.',
      confirmButtonColor: '#3085d6'
    });
    return;
  }

  // Obtener los valores de los campos de entrada
  var descripcion = document.getElementById('descripcion').value.trim();
  var fecha_creacion = document.getElementById('fecha_creacion').value;
  var estado = document.getElementById('estado').value;
  var id = document.getElementById('idTicket').value;

  let ladata = new FormData();
  ladata.append('descripcion', descripcion);
  ladata.append('fecha_creacion', fecha_creacion);
  ladata.append('estado', estado);
  ladata.append('id', id);
  ladata.append('ind', 4);

  // Mostrar loading
  Swal.fire({
    title: 'Actualizando...',
    text: 'Guardando cambios',
    allowOutsideClick: false,
    didOpen: () => {
      Swal.showLoading();
    }
  });

  // Realizar solicitud fetch al backend
  fetch('backend/ticketsBackend.php', {
    method: 'POST',
    body: ladata,
  })
    .then(response => {
      if (response.ok) {
        return response.json();
      } else {
        throw new Error('Error al actualizar el ticket');
      }
    })
    .then(data => {
      if (data.rta === 'ok') {
        // Limpiar los campos del formulario
        limpiarCampos();
        // Ejecutar la función lisrarTickets() con los parámetros necesarios
        lisrarTickets(2, 1, 5);
        // Cerrar la modal y sus elementos
        $('#largeModal').modal('hide');
        $('.modal-backdrop').remove();
        Swal.fire({
          icon: 'success',
          title: '¡Actualizado!',
          text: 'El ticket ha sido actualizado correctamente.',
          confirmButtonColor: '#3085d6',
          timer: 2000,
          timerProgressBar: true
        });
      } else {
        // Mostrar SweetAlert de error
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Hubo un problema al actualizar el ticket. Por favor, inténtelo de nuevo.',
          confirmButtonColor: '#3085d6'
        });
      }
    })
    .catch(error => {
      console.error('Error:', error);
      // Mostrar SweetAlert de error en caso de fallo en la solicitud
      Swal.fire({
        icon: 'error',
        title: 'Error de Conexión',
        text: 'No se pudo conectar con el servidor. Verifique su conexión a internet.',
        confirmButtonColor: '#3085d6'
      });
    });
}

function deleteUsuario(id) {
  Swal.fire({
    title: '¿Estás seguro?',
    text: "Esta acción no se puede deshacer",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    confirmButtonText: 'Sí, eliminar',
    cancelButtonText: 'Cancelar'
  }).then((result) => {
    if (result.isConfirmed) {
      let ladata = new FormData();
      ladata.append('id', id);
      ladata.append('ind', 7);

      // Mostrar loading
      Swal.fire({
        title: 'Eliminando...',
        text: 'Procesando solicitud',
        allowOutsideClick: false,
        didOpen: () => {
          Swal.showLoading();
        }
      });

      fetch('backend/ticketsBackend.php', {
        method: 'POST',
        body: ladata,
      })
        .then(response => {
          if (response.ok) {
            return response.json();
          } else {
            throw new Error('Error al eliminar el ticket');
          }
        })
        .then(data => {
          if (data.rta === 'ok') {
            lisrarTickets(2, 1, 5);
            Swal.fire({
              icon: 'success',
              title: '¡Eliminado!',
              text: 'El ticket ha sido eliminado correctamente.',
              confirmButtonColor: '#3085d6',
              timer: 2000,
              timerProgressBar: true
            });
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: 'Hubo un problema al eliminar el ticket.',
              confirmButtonColor: '#3085d6'
            });
          }
        })
        .catch(error => {
          console.error('Error:', error);
          Swal.fire({
            icon: 'error',
            title: 'Error de Conexión',
            text: 'No se pudo conectar con el servidor.',
            confirmButtonColor: '#3085d6'
          });
        });
    }
  });
}