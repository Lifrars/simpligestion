$(document).ready(function () {
  let nuevoinicio = 1;
  let nroreg = 5;
  listarAreas(2, nuevoinicio, nroreg);
});

document.getElementById("addUsuario").addEventListener("click", function () {
  // Limpiar los campos
  limpiarCampos();
  // Ocultar el botón de actualizar
  document.getElementById("actualizarArea").style.display = "none";
  // Mostrar el botón de guardar
  document.getElementById("guardarArea").style.display = "block";
  traerPerfiles();
});

function leerdatos(id) {
  // Limpiar los campos
  limpiarCampos();
  traerPerfiles();

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
      console.log(area)
      document.getElementById('nombre').value = area.nombre;
      document.getElementById('descripcion').value = area.descripcion;
      document.getElementById('capacidad').value = area.capacidad;
      document.getElementById('horario').value = area.horario;
      document.getElementById('ubicacion').value = area.ubicacion;
      
      document.getElementById('idArea').value = area.area_id; // Esta línea asume que has añadido un input oculto para el ID
      // Ocultar el botón de guardar
      document.getElementById("guardarArea").style.display = "none";
      // Mostrar el botón de actualizar (Asegúrate de que este botón exista o ajusta según tu código)
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
  document.getElementById('horario').value = '';
  document.getElementById('ubicacion').value = '';
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
      console.log(data);

      let i = 0;
      data.rta2.forEach(function (item) {
          i = i + 1;
          console.log(item);
          htmlTags += '<tr>';
          htmlTags += '     <td>' + item.nombre + '</td>';
          htmlTags += '     <td>' + item.descripcion + '</td>';
          htmlTags += '     <td>' + item.capacidad + '</td>';
          htmlTags += '     <td>' + item.horario + '</td>';
          htmlTags += '     <td>' + item.ubicacion + '</td>';
          htmlTags += '     <td>';
          htmlTags += '         <a class="me-3" id="detail" onclick="detailArea(' + item.area_id + ');" name="detail" data-validar="SI" data-tipo="TABLA_MODAL" data-apodo="Detalles Producto" style="cursor: pointer;"><img src="//pos.metaversomsp.com/assets/img/icons/eye.svg" alt="img"></a>';
          htmlTags += '         <a class="me-3" id="editProducto" onclick="leerdatos(' + item.area_id + ');" name="editProducto" data-validar="SI" data-tipo="TABLA_MODAL" data-apodo="Editar Producto" style="cursor: pointer;"><img src="//pos.metaversomsp.com/assets/img/icons/edit.svg" alt="img"></a>';
          if(item.estado=="0"){
            htmlTags += '         <a class="me-3" id="deleteproducto" onclick="deleteArea(' + item.area_id + ');" name="deleteproducto" data-validar="SI" data-tipo="TABLA_MODAL" data-apodo="Eliminar Producto" style="cursor: pointer;"><img src="//pos.metaversomsp.com/assets/img/icons/delete.svg" alt="img"></a>';
          }else{
            htmlTags += '         <a class="me-3" id="deleteproducto" onclick="activateArea(' + item.area_id + ');" name="activateproducto" data-validar="SI" data-tipo="TABLA_MODAL" data-apodo="Eliminar Producto" style="cursor: pointer;"><img src="/plantilla/assets/img/icons8-activar-24.svg" alt="img"></a>'
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
      // Fin del paginador

      $('#paginacion').append(paginador);

  }).catch(function (error) {
      console.error("Error al obtener productos:", error);
  });
}


//Traer los perfiles del sistema 
function traerPerfiles() {
  let ladata = new FormData();
  ladata.append("ind", "5");

  fetch('backend/usuariosBackend.php', {
    method: "POST",
    body: ladata,
  })
    .then(function (response) {
      return response.json();
    })
    .then(function (data) {
      $("#sel_perfil").empty();
      let htmlTags = '<option value="0">Seleccionar</option>';
      data.rta.forEach(function (item) {
        htmlTags += "<option value=" + item.id_perfil + ">" + item.nombre_perfil + "</option>";
      });
      $("#sel_perfil").append(htmlTags);
    })
    .catch(function (error) { });
}

function detailArea(id) {
  let ladata = new FormData();
  ladata.append('id', id);
  ladata.append('ind', 6); // Asegúrate de que el valor de 'ind' sea correcto para obtener los detalles del área

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
      $('#modalMostrarArea').modal('show'); // Ajusta el nombre del modal según tu código
      console.log(data);
      document.getElementById('showNombre').textContent = data.rta.nombre;
      document.getElementById('showDescripcion').textContent = data.rta.descripcion;
      document.getElementById('showCapacidad').textContent = data.rta.capacidad;
      document.getElementById('showHorario').textContent = data.rta.horario;
      document.getElementById('showUbicacion').textContent = data.rta.ubicacion;
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
  var horario = document.getElementById('horario').value;
  var ubicacion = document.getElementById('ubicacion').value;
  var id = document.getElementById('idArea').value;

  let ladata = new FormData();
  ladata.append('nombre', nombre);
  ladata.append('descripcion', descripcion);
  ladata.append('capacidad', capacidad);
  ladata.append('horario', horario);
  ladata.append('ubicacion', ubicacion);
  ladata.append('id', id);
  ladata.append('ind', 4);
  for (const pair of ladata.entries()) {
  console.log(pair[0] + ': ' + pair[1]);
}


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
      // Aquí puedes manejar la respuesta del backend si es necesario
      // console.log(data);
      if (data.rta === 'ok') {
        // Limpiar los campos del formulario
        limpiarCampos();
        // Ejecutar la función listarUsuarios() con los parámetros necesarios
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
  // Obtener los valores de los campos de entrada
  var nombre = document.getElementById('nombre').value;
  var descripcion = document.getElementById('descripcion').value;
  var capacidad = document.getElementById('capacidad').value;
  var horario = document.getElementById('horario').value;
  var ubicacion = document.getElementById('ubicacion').value;
  var id = document.getElementById('idArea').value;

  let ladata = new FormData();
  ladata.append('nombre', nombre);
  ladata.append('descripcion', descripcion);
  ladata.append('capacidad', capacidad);
  ladata.append('horario', horario);
  ladata.append('ubicacion', ubicacion);
  ladata.append('ind', 5);
  for (const pair of ladata.entries()) {
  console.log(pair[0] + ': ' + pair[1]);
}
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
      // Aquí puedes manejar la respuesta del backend si es necesario
      // console.log(data);
      if (data.rta === 'ok') {
        // Limpiar los campos del formulario
        limpiarCampos();
        // Ejecutar la función listarUsuarios() con los parámetros necesarios
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

function deleteArea(id) {
  Swal.fire({
    title: '¿Estás seguro?',
    text: "¡Desactivaras está area y ya no sera visible!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
  }).then((result) => {
    if (result.isConfirmed) {
      let ladata = new FormData();
      ladata.append('id', id);
      ladata.append('ind', 7);
      console.log(ladata.values)
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
              '¡Eliminado!',
              'El area ha sido eliminado.',
              'success'
            );
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: 'Hubo un problema al eliminar el area. Por favor, inténtelo de nuevo más tarde.',
            });
          }
        })
        .catch(error => {
          console.error('Error:', error);
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Hubo un problema al eliminar el area. Por favor, inténtelo de nuevo más tarde.',
          });
        });
    }
  })
  
}
function activateArea(id) {
  Swal.fire({
    title: '¿Estás seguro?',
    text: "¡Activaras está area!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
  }).then((result) => {
    if (result.isConfirmed) {
      let ladata = new FormData();
      ladata.append('id', id);
      ladata.append('ind', 8);
      console.log(ladata.values)
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
              'Activado!',
              'La area ha sido activada.',
              'success'
            );
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: 'Hubo un problema al activar el area. Por favor, inténtelo de nuevo más tarde.',
            });
          }
        })
        .catch(error => {
          console.error('Error:', error);
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Hubo un problema al activar el area. Por favor, inténtelo de nuevo más tarde.',
          });
        });
    }
  });
}
