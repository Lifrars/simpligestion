$(document).ready(function () {
  let nuevoinicio = 1;
  let nroreg = 5;
  listarAreas(2, nuevoinicio, nroreg);

  restringirHoras('horaInicio', 'horaFin', 0, 23);

  $("#horaInicio").on("change", function () {
      actualizarHoraFin();
  });
});
document.getElementById('buscador').addEventListener('input', function () {
    const filtro = this.value.toLowerCase();
    const filas = document.querySelectorAll('#tablaAreas tbody tr');

    filas.forEach(fila => {
        const textoFila = fila.textContent.toLowerCase();
        fila.style.display = textoFila.includes(filtro) ? '' : 'none';
    });
});

// Cargar opciones en los select
function restringirHoras(selectInicioId, selectFinId, horaMin, horaMax) {
    let selectInicio = document.getElementById(selectInicioId);
    let selectFin = document.getElementById(selectFinId);

    if (!selectInicio || !selectFin) {
        console.error("❌ Error: Uno de los select no existe.");
        return;
    }

    // Poblar select de horaInicio
    selectInicio.innerHTML = '<option value="">Selecciona una hora</option>';
    for (let h = horaMin; h < horaMax; h++) {
        let hora = h.toString().padStart(2, '0') + ':00';
        selectInicio.innerHTML += `<option value="${hora}">${hora}</option>`;
    }

    // Evento para actualizar horaFin cuando se selecciona horaInicio
    selectInicio.addEventListener("change", function () {
        let selectedHoraInicio = parseInt(selectInicio.value.split(":")[0]);
        selectFin.innerHTML = '<option value="">Selecciona una hora</option>';

        for (let h = selectedHoraInicio + 1; h <= horaMax; h++) {
            let hora = h.toString().padStart(2, '0') + ':00';
            selectFin.innerHTML += `<option value="${hora}">${hora}</option>`;
        }

        selectFin.disabled = false;
    });

    // Inicialmente deshabilitar horaFin hasta que se seleccione una horaInicio
    selectFin.innerHTML = '<option value="">Selecciona una hora</option>';
    selectFin.disabled = true;
}

document.getElementById("addUsuario").addEventListener("click", function () {
  // Limpiar los campos
  limpiarCampos();
  // Ocultar el botón de actualizar
  document.getElementById("actualizarArea").style.display = "none";
  // Mostrar el botón de guardar
  document.getElementById("guardarArea").style.display = "block";
});

function leerdatos(id) {
  // Limpiar los campos
  limpiarCampos();

  let ladata = new FormData();
  ladata.append('id', id);
  ladata.append('ind', 1);

  fetch('backend/areasBackend.php', {
    method: 'POST',
    body: ladata,
  })
    .then(response => {
      if (response.ok) {
        return response.json();
      } else {
        throw new Error('Error al obtener los datos del area');
      }
    })
    .then(data => {
      $('#largeModal').modal('show');
      // Asegúrate de acceder al primer elemento del array
      const area = data.rta[0]; // Accede al primer elemento
      
      // Llenar los campos básicos
      document.getElementById('nombre').value = area.nombre;
      document.getElementById('descripcion').value = area.descripcion;
      document.getElementById('capacidad').value = area.capacidad;
      document.getElementById('ubicacion').value = area.ubicacion;
      document.getElementById('idArea').value = area.area_id;
      
      // Configurar las horas
      document.getElementById('horaInicio').value = area.hora_inicio;
      
      // Actualizar horaFin después de establecer horaInicio
      setTimeout(() => {
        document.getElementById('horaFin').value = area.hora_fin;
      }, 100);
      
      // Configurar los días disponibles
      if (area.dias_disponibles) {
        const diasSeleccionados = area.dias_disponibles.split(',');
        // Limpiar todos los checkboxes primero
        document.querySelectorAll('#diasDisponibles input[type="checkbox"]').forEach(checkbox => {
          checkbox.checked = false;
        });
        
        // Marcar los días seleccionados
        diasSeleccionados.forEach(dia => {
          const diaLimpio = dia.trim();
          const checkbox = document.querySelector(`#diasDisponibles input[value="${diaLimpio}"]`);
          if (checkbox) {
            checkbox.checked = true;
          }
        });
      }
      
      // Ocultar el botón de guardar
      document.getElementById("guardarArea").style.display = "none";
      // Mostrar el botón de actualizar
      document.getElementById("actualizarArea").style.display = "block";
    })
    .catch(error => {
      console.error('Error:', error);
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Hubo un problema al obtener los datos del area. Por favor, inténtelo de nuevo más tarde.',
      });
    });
}

function limpiarCampos() {
  document.getElementById('nombre').value = '';
  document.getElementById('descripcion').value = '';
  document.getElementById('capacidad').value = '';
  document.getElementById('ubicacion').value = '';

  // Limpiar hora de inicio y fin
  document.getElementById('horaInicio').value = '';
  document.getElementById('horaFin').value = '';
  
  // Rehabilitar horaFin y limpiar opciones
  document.getElementById('horaFin').innerHTML = '<option value="">Selecciona una hora</option>';
  document.getElementById('horaFin').disabled = true;

  // Limpiar los días disponibles
  document.querySelectorAll('#diasDisponibles input[type="checkbox"]').forEach(checkbox => {
      checkbox.checked = false;
  });
}

function actualizarHoraFin() {
  const horaInicio = document.getElementById('horaInicio');
  const horaFin = document.getElementById('horaFin');
  
  if (horaInicio.value) {
      let selectedHoraInicio = parseInt(horaInicio.value.split(":")[0]);
      horaFin.innerHTML = '<option value="">Selecciona una hora</option>';

      for (let h = selectedHoraInicio + 1; h <= 23; h++) {
          let hora = h.toString().padStart(2, '0') + ':00';
          horaFin.innerHTML += `<option value="${hora}">${hora}</option>`;
      }

      horaFin.disabled = false;
  }
}

function listarAreas(ind, inicio, nroreg) {
  let nuevoinicio = (inicio - 1) * parseInt(nroreg);

  let ladata = new FormData();
  ladata.append('ind', ind);
  ladata.append('nuevoinicio', nuevoinicio);
  ladata.append('nroreg', nroreg);

  fetch('backend/areasBackend.php', {
      method: 'POST',
      body: ladata,
  }).then(function (response) {
      return response.json();
  }).then(function (data) {
      $('#tablaAreas tbody').empty();
      $("#paginacion").empty();
      let htmlTags = "";

      let i = 0;
      data.rta2.forEach(function (item) {
          i = i + 1;
          htmlTags += '<tr>';
          htmlTags += '     <td>' + item.nombre + '</td>';
          htmlTags += '     <td>' + item.descripcion + '</td>';
          htmlTags += '     <td>' + item.capacidad + '</td>';
          htmlTags += '     <td>' + item.hora_inicio +' - '+item.hora_fin+ '</td>';
          htmlTags += '<td>' + item.dias_disponibles.replace(/,/g, '<br>') + '</td>';
          htmlTags += '     <td>' + item.ubicacion + '</td>';
          htmlTags += '     <td>';
          htmlTags += '         <a class="me-3" id="detail" onclick="detailArea(' + item.area_id + ');" name="detail" data-validar="SI" data-tipo="TABLA_MODAL" data-apodo="Detalles Area" style="cursor: pointer;"><img src="//ppi.miclickderecho.com/plantilla/assets/img/icons/eye.svg" alt="img"></a>';
          htmlTags += '         <a class="me-3" id="editArea" onclick="leerdatos(' + item.area_id + ');" name="editArea" data-validar="SI" data-tipo="TABLA_MODAL" data-apodo="Editar Area" style="cursor: pointer;"><img src="//ppi.miclickderecho.com/plantilla/assets/img/icons/edit.svg" alt="img"></a>';
          if(item.estado=="0"){
            htmlTags += '         <a class="me-3" id="deletearea" onclick="deleteArea(' + item.area_id + ');" name="deletearea" data-validar="SI" data-tipo="TABLA_MODAL" data-apodo="Eliminar Area" style="cursor: pointer;"><img src="//ppi.miclickderecho.com/plantilla/assets/img/icons/delete.svg" alt="img"></a>';
          }else{
            htmlTags += '         <a class="me-3" id="activatearea" onclick="activateArea(' + item.area_id + ');" name="activatearea" data-validar="SI" data-tipo="TABLA_MODAL" data-apodo="Activar Area" style="cursor: pointer;"><img src="/plantilla/assets/img/icons8-activar-24.svg" alt="img"></a>'
          }
          htmlTags += '     </td>';
          htmlTags += ' </tr>';
      });
      $('#tablaAreas tbody').append(htmlTags);
      
      // Paginador
      let paginador = "";
      paginador += '<ul class="pagination justify-content-end">';
      paginador += '<li style="margin-left: 0px;margin-right: 0px;"><span class="label label pagina">' + data.rta + ' Registros</span></li>';

      if (inicio > 1) {
          paginador += '<li class="notDisable" style="margin-left: 0px;margin-right: 0px;"><a class="pagina" href="javascript:void(0)" onclick="listarAreas(\'2\', 1, ' + nroreg + ', \'\', \'\', \'\', \'\', \'\', \'\')">&laquo;</a></li>';
          paginador += '<li class="notDisable" style="margin-left: 0px;margin-right: 0px;"><a class="pagina" href="javascript:void(0)" onclick="listarAreas(\'2\', ' + (inicio - 1) + ', ' + nroreg + ', \'\', \'\', \'\', \'\', \'\', \'\')">&lsaquo;</a></li>';
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
              paginador += '<li style="margin-left: 0px;margin-right: 0px;"><a class="pagina" href="javascript:void(0)" onclick="listarAreas(\'2\', ' + i + ', ' + nroreg + ', \'\', \'\', \'\', \'\', \'\', \'\')">' + i + '</a></li>';
          }
      }
      if (inicio < Math.ceil(data.rta / parseInt(nroreg))) {
          paginador += '<li class="notDisable" style="margin-left: 0px;margin-right: 0px;"><a class="pagina" href="javascript:void(0)" onclick="listarAreas(2, ' + (inicio + 1) + ', ' + nroreg + ', \'\', \'\', \'\', \'\', \'\', \'\')">&rsaquo;</a></li>';
          paginador += '<li class="notDisable" style="margin-left: 0px;margin-right: 0px;"><a class="pagina" href="javascript:void(0)" onclick="listarAreas(2, ' + Math.ceil(data.rta / nroreg) + ', ' + nroreg + ', \'\', \'\', \'\', \'\', \'\', \'\')">&raquo;</a></li>';
      } else {
          paginador += '<li class="disabled" style="margin-left: 0px;margin-right: 0px;"><a class="pagina" href="javascript:void(0)">&rsaquo;</a></li>';
          paginador += '<li class="disabled" style="margin-left: 0px;margin-right: 0px;"><a class="pagina" href="javascript:void(0)">&raquo;</a></li>';
      }
      paginador += '<li style="margin-left: 0px;margin-right: 0px;"><span class="label label pagina">' + Math.ceil(data.rta / parseInt(nroreg)) + ' Páginas</span></li>';
      paginador += '</ul>';

      $('#paginacion').append(paginador);

  }).catch(function (error) {
      console.error("Error al obtener areas:", error);
  });
}

function detailArea(id) {
  let ladata = new FormData();
  ladata.append('id', id);
  ladata.append('ind', 6);

  fetch('backend/areasBackend.php', {
    method: 'POST',
    body: ladata,
  })
    .then(response => {
      if (response.ok) {
        return response.json();
      } else {
        throw new Error('Error al obtener los datos del área');
      }
    })
    .then(data => {
      $('#modalMostrarArea').modal('show');
      console.log(data);
      
      const area = data.rta;
      document.getElementById('showNombre').textContent = area.nombre;
      document.getElementById('showDescripcion').textContent = area.descripcion;
      document.getElementById('showCapacidad').textContent = area.capacidad + ' personas';
      
      // Mostrar horario completo
      const horarioCompleto = `${area.hora_inicio} - ${area.hora_fin}`;
      document.getElementById('showHorario').textContent = horarioCompleto;
      
      document.getElementById('showUbicacion').textContent = area.ubicacion;
      
      // Mostrar días disponibles formateados
      if (area.dias_disponibles) {
        const diasFormateados = area.dias_disponibles.replace(/,/g, ', ');
        document.getElementById('showDias').textContent = diasFormateados;
      }
      
      // Mostrar estado
      const estadoTexto = area.estado == 0 ? 'Activa' : 'Inactiva';
      document.getElementById('showEstado').textContent = estadoTexto;
      document.getElementById('showEstado').className = area.estado == 0 ? 'badge bg-success' : 'badge bg-danger';
    })
    .catch(error => {
      console.error('Error:', error);
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Hubo un problema al obtener los datos del área. Por favor, inténtelo de nuevo más tarde.',
      });
    });
}

function Update_usuario() {
  // Obtener los valores de los campos de entrada
  var nombre = document.getElementById('nombre').value;
  var descripcion = document.getElementById('descripcion').value;
  var capacidad = document.getElementById('capacidad').value;
  var horaInicio = document.getElementById('horaInicio').value;
  var horaFin = document.getElementById('horaFin').value;
  var ubicacion = document.getElementById('ubicacion').value;
  var id = document.getElementById('idArea').value;

  // Capturar los días seleccionados
  const checkboxes = document.querySelectorAll('#diasDisponibles input[type="checkbox"]:checked');
  const diasSeleccionados = Array.from(checkboxes).map(checkbox => checkbox.value).join(',');

  // Validaciones
  if (!nombre) {
    Swal.fire("Error", "El campo 'Nombre' es obligatorio.", "error");
    return;
  }

  if (!descripcion) {
    Swal.fire("Error", "El campo 'Descripción' es obligatorio.", "error");
    return;
  }

  if (!capacidad) {
    Swal.fire("Error", "El campo 'Capacidad' es obligatorio.", "error");
    return;
  }

  if (!horaInicio) {
    Swal.fire("Error", "Debes seleccionar una 'Hora de inicio'.", "error");
    return;
  }

  if (!horaFin) {
    Swal.fire("Error", "Debes seleccionar una 'Hora de fin'.", "error");
    return;
  }

  if (!diasSeleccionados) {
    Swal.fire("Error", "Debes seleccionar al menos un día disponible.", "error");
    return;
  }

  if (!ubicacion) {
    Swal.fire("Error", "El campo 'Ubicación' es obligatorio.", "error");
    return;
  }

  let ladata = new FormData();
  ladata.append('nombre', nombre);
  ladata.append('descripcion', descripcion);
  ladata.append('capacidad', capacidad);
  ladata.append('hora_inicio', horaInicio);
  ladata.append('hora_fin', horaFin);
  ladata.append('ubicacion', ubicacion);
  ladata.append('dias_disponibles', diasSeleccionados);
  ladata.append('id', id);
  ladata.append('ind', 4);

  // Realizar solicitud fetch al backend
  fetch('backend/areasBackend.php', {
    method: 'POST',
    body: ladata,
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
        // Ejecutar la función listarAreas() con los parámetros necesarios
        listarAreas(2, 1, 5);
        // Cerrar la modal y sus elementos
        $('#largeModal').modal('hide');
        $('.modal-backdrop').remove();
        Swal.fire({
          icon: 'success',
          title: '¡Área actualizada exitosamente!',
          text: 'El área ha sido actualizada correctamente.',
        });
      } else {
        // Mostrar SweetAlert de error
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Hubo un problema al actualizar el área. Por favor, inténtelo de nuevo más tarde.',
        });
      }
    })
    .catch(error => {
      console.error('Error:', error);
      // Mostrar SweetAlert de error en caso de fallo en la solicitud
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Hubo un problema al actualizar el área. Por favor, inténtelo de nuevo más tarde.',
      });
    });
}

function Save_usuario() {
  const nombre = document.getElementById('nombre').value;
  const descripcion = document.getElementById('descripcion').value;
  const capacidad = document.getElementById('capacidad').value;
  const horaInicio = document.getElementById('horaInicio').value;
  const horaFin = document.getElementById('horaFin').value;
  const ubicacion = document.getElementById('ubicacion').value;

  // Capturar los días seleccionados
  const checkboxes = document.querySelectorAll('#diasDisponibles input[type="checkbox"]:checked');
  const diasSeleccionados = Array.from(checkboxes).map(checkbox => checkbox.value).join(',');

   // Validaciones
   if (!nombre) {
    Swal.fire("Error", "El campo 'Nombre' es obligatorio.", "error");
    return ;
}

if (!descripcion) {
    Swal.fire("Error", "El campo 'Descripción' es obligatorio.", "error");
    return ;
}

if (!capacidad) {
    Swal.fire("Error", "El campo 'Capacidad' es obligatorio.", "error");
    return ;
}

if (!horaInicio) {
    Swal.fire("Error", "Debes seleccionar una 'Hora de inicio'.", "error");
    return ;
}

if (!horaFin) {
    Swal.fire("Error", "Debes seleccionar una 'Hora de fin'.", "error");
    return ;
}

if (!diasSeleccionados) {
    Swal.fire("Error", "Debes seleccionar al menos un día disponible.", "error");
    return ;
}

if (!ubicacion) {
    Swal.fire("Error", "El campo 'Ubicación' es obligatorio.", "error");
    return ;
}

  const formData = new FormData();
  formData.append('nombre', nombre);
  formData.append('descripcion', descripcion);
  formData.append('capacidad', capacidad);
  formData.append('hora_inicio', horaInicio);
  formData.append('hora_fin', horaFin);
  formData.append('ubicacion', ubicacion);
  formData.append('dias_disponibles', diasSeleccionados);
  formData.append('ind', 5);

  fetch('backend/areasBackend.php', {
    method: 'POST',
    body: formData,
  })
    .then(response => {
      if (response.ok) {
        return response.json();
      } else {
        throw new Error('Error al guardar el área');
      }
    })
    .then(data => {
      if (data.rta === 'ok') {
        // Limpiar los campos del formulario
        limpiarCampos();
        // Ejecutar la función listarAreas() con los parámetros necesarios
        listarAreas(2, 1, 5);
        // Cerrar la modal y sus elementos
        $('#largeModal').modal('hide');
        $('.modal-backdrop').remove();
        Swal.fire({
          icon: 'success',
          title: '¡Área guardada exitosamente!',
          text: 'El área ha sido guardada correctamente.',
        });
      } else {
        // Mostrar SweetAlert de error
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Hubo un problema al guardar el área. Por favor, inténtelo de nuevo más tarde.',
        });
      }
    })
    .catch(error => {
      console.error('Error:', error);
      // Mostrar SweetAlert de error en caso de fallo en la solicitud
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Hubo un problema al guardar el área. Por favor, inténtelo de nuevo más tarde.',
      });
    });
}

function deleteArea(id) {
  Swal.fire({
    title: '¿Estás seguro?',
    text: "¡Desactivarás está área y ya no será visible!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Sí, desactivar',
    cancelButtonText: 'Cancelar'
  }).then((result) => {
    if (result.isConfirmed) {
      let ladata = new FormData();
      ladata.append('id', id);
      ladata.append('ind', 7);
      
      fetch('backend/areasBackend.php', {
        method: 'POST',
        body: ladata,
      })
        .then(response => {
          if (response.ok) {
            return response.json();
          } else {
            throw new Error('Error al eliminar el area');
          }
        })
        .then(data => {
          if (data.rta === 'ok') {
            listarAreas(2, 1, 5);
            Swal.fire(
              '¡Desactivada!',
              'El área ha sido desactivada.',
              'success'
            );
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: 'Hubo un problema al desactivar el área. Por favor, inténtelo de nuevo más tarde.',
            });
          }
        })
        .catch(error => {
          console.error('Error:', error);
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Hubo un problema al desactivar el área. Por favor, inténtelo de nuevo más tarde.',
          });
        });
    }
  })
}

function activateArea(id) {
  Swal.fire({
    title: '¿Estás seguro?',
    text: "¡Activarás está área!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Sí, activar',
    cancelButtonText: 'Cancelar'
  }).then((result) => {
    if (result.isConfirmed) {
      let ladata = new FormData();
      ladata.append('id', id);
      ladata.append('ind', 8);
      
      fetch('backend/areasBackend.php', {
        method: 'POST',
        body: ladata,
      })
        .then(response => {
          if (response.ok) {
            return response.json();
          } else {
            throw new Error('Error al activar el area');
          }
        })
        .then(data => {
          if (data.rta === 'ok') {
            listarAreas(2, 1, 5);
            Swal.fire(
              '¡Activada!',
              'El área ha sido activada.',
              'success'
            );
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: 'Hubo un problema al activar el área. Por favor, inténtelo de nuevo más tarde.',
            });
          }
        })
        .catch(error => {
          console.error('Error:', error);
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Hubo un problema al activar el área. Por favor, inténtelo de nuevo más tarde.',
          });
        });
    }
  });
}