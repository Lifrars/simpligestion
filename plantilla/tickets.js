$(document).ready(function () {

  // // Recogemos el id perfil oculto de idPerfil
  // let idPerfil = document.getElementById('idPerfil').value;

  // // Si el idPerfil es 1, ocultar el botón "Agregar Ticket"
  // if (idPerfil == "1") {
  //   $('#addUsuario').hide();
  // }

  let nuevoinicio = 1;
  let nroreg = 5;
  lisrarTickets(2, nuevoinicio, nroreg);
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
  // Habilitar fecha_creacion
  document.getElementById('fecha_creacion').disabled = false;

  // Limpiar los campos
  limpiarCampos();
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
      if (data.rta) {
        $('#largeModal').modal('show');
        let ticket = data.rta[0];

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

          // Desbloquear los demás campos
          document.getElementById('descripcion').disabled = false;
          document.getElementById('fecha_creacion').disabled = false;
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
      });
    });
}

function limpiarCampos() {
  document.getElementById('idTicket').value = '';
  document.getElementById('descripcion').value = '';
  document.getElementById('fecha_creacion').value = '';
  document.getElementById('estado').value = 'abierto';  // Valor predeterminado "abierto"
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

      htmlTags += '<tr>';
      htmlTags += '     <td>' + item.id + '</td>';
      if (idPerfil == "1") {
        htmlTags += '     <td>' + item.nombre_completo + '</td>'; // Ajusta para mostrar el nombre completo del usuario
      }
      htmlTags += '     <td>' + item.descripcion + '</td>';
      htmlTags += '     <td>' + item.fecha_creacion + '</td>';
      htmlTags += '     <td>' + item.estado + '</td>';
      htmlTags += '     <td>';
      htmlTags += '         <a class="me-3" id="detail" onclick="detailUsuario(' + item.id + ');" name="detail" data-validar="SI"';
      htmlTags += '         data-tipo="TABLA_MODAL" data-apodo="Detalles Producto" style="cursor: pointer;"><img src="https://unpkg.com/heroicons@1.0.1/outline/eye.svg" alt="img"></a>';
      htmlTags += '         <a class="me-3" id="editProducto" onclick="leerdatos(' + item.id + ');" name="editProducto"';
      htmlTags += '         data-validar="SI" data-tipo="TABLA_MODAL" data-apodo="Editar Producto" style="cursor: pointer;"><img src="//pos.metaversomsp.com/assets/img/icons/edit.svg" alt="img"></a>';
      htmlTags += '         <a class="me-3" id="deleteproducto" onclick="deleteUsuario(' + item.id + ');" name="deleteproducto"';
      htmlTags += '         data-validar="SI" data-tipo="TABLA_MODAL" data-apodo="Eliminar Producto" style="cursor: pointer;"><img src="//pos.metaversomsp.com/assets/img/icons/delete.svg" alt="img"></a>';
      htmlTags += '     </td>';
      htmlTags += ' </tr>';
    });
    $('#tablaUsuarios tbody').append(htmlTags);

    // Paginador
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
    // Fin del paginador

    let tablaUsuarios = document.querySelector('#tablaUsuarios');
    $('#paginacion').append(paginador);
    tablaUsuarios.append();
  }).catch(function (error) {
    console.error("Error al obtener productos:", error);
  });
}

function Save_ticket() {
  // Recogemos el id usuario oculto de idUsuario
  let idUsuario = document.getElementById('idUsuario').value;
  // Obtener los valores de los campos de entrada
  var descripcion = document.getElementById('descripcion').value;
  var fecha_creacion = document.getElementById('fecha_creacion').value;
  var estado = document.getElementById('estado').value;

  let ladata = new FormData();
  ladata.append('idUsuario', idUsuario);
  ladata.append('descripcion', descripcion);
  ladata.append('fecha_creacion', fecha_creacion);
  ladata.append('estado', estado);
  ladata.append('ind', 3);

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
      // Aquí puedes manejar la respuesta del backend si es necesario
      // console.log(data);
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
          title: '¡Ticket guardado exitosamente!',
          text: 'El ticket ha sido guardado correctamente.',
        });
      } else {
        // Mostrar SweetAlert de error
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Hubo un problema al guardar el ticket. Por favor, inténtelo de nuevo más tarde.',
        });
      }
    })
    .catch(error => {
      console.error('Error:', error);
      // Mostrar SweetAlert de error en caso de fallo en la solicitud
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Hubo un problema al guardar el ticket. Por favor, inténtelo de nuevo más tarde.',
      });
    });
}

function detailUsuario(id) {
  let ladata = new FormData();
  ladata.append('id', id);
  ladata.append('ind', 6);

  fetch('backend/ticketsBackend.php', {
    method: 'POST',
    body: ladata,
  })
    .then(response => {
      if (response.ok) {
        return response.json();
      } else {
        throw new Error('Error al obtener los datos del usuario');
      }
    })
    .then(data => {
      $('#modalMostrarUsuario').modal('show');
      console.log(data);
      document.getElementById('showdescripcion').textContent = data.rta.descripcion;
      document.getElementById('showFecha_creacion').textContent = data.rta.fecha_creacion;
      document.getElementById('showEstadp').textContent = data.rta.nombre_completo;
      document.getElementById('showidUsuario').textContent = data.rta.idUsuario;
    })
    .catch(error => {
      console.error('Error:', error);
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Hubo un problema al obtener los datos del usuario. Por favor, inténtelo de nuevo más tarde.',
      });
    });
}

function Update_ticket() {
  // Obtener los valores de los campos de entrada
  var descripcion = document.getElementById('descripcion').value;
  var fecha_creacion = document.getElementById('fecha_creacion').value;
  var estado = document.getElementById('estado').value;
  var id = document.getElementById('idTicket').value;

  let ladata = new FormData();
  ladata.append('descripcion', descripcion);
  ladata.append('fecha_creacion', fecha_creacion);
  ladata.append('estado', estado);
  ladata.append('id', id);
  ladata.append('ind', 4);

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
      // Aquí puedes manejar la respuesta del backend si es necesario
      // console.log(data);
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
          title: '¡Ticket actualizado exitosamente!',
          text: 'El ticket ha sido actualizado correctamente.',
        });
      } else {
        // Mostrar SweetAlert de error
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Hubo un problema al actualizar el ticket. Por favor, inténtelo de nuevo más tarde.',
        });
      }
    })
    .catch(error => {
      console.error('Error:', error);
      // Mostrar SweetAlert de error en caso de fallo en la solicitud
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Hubo un problema al actualizar el ticket. Por favor, inténtelo de nuevo más tarde.',
      });
    });
}

function deleteUsuario(id) {
  Swal.fire({
    title: '¿Estás seguro?',
    text: "¡No podrás revertir esto!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
  }).then((result) => {
    if (result.isConfirmed) {
      let ladata = new FormData();
      ladata.append('id', id);
      ladata.append('ind', 7);

      fetch('backend/ticketsBackend.php', {
        method: 'POST',
        body: ladata,
      })
        .then(response => {
          if (response.ok) {
            return response.json();
          } else {
            throw new Error('Error al eliminar el usuario');
          }
        })
        .then(data => {
          if (data.rta === 'ok') {
            lisrarTickets(2, 1, 5);
            Swal.fire(
              '¡Eliminado!',
              'El usuario ha sido eliminado.',
              'success'
            );
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: 'Hubo un problema al eliminar el usuario. Por favor, inténtelo de nuevo más tarde.',
            });
          }
        })
        .catch(error => {
          console.error('Error:', error);
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Hubo un problema al eliminar el usuario. Por favor, inténtelo de nuevo más tarde.',
          });
        });
    }
  });
}
