$(document).ready(function () {
  let nuevoinicio = 1;
  let nroreg = 5;
  lisrarUsuarios(2, nuevoinicio, nroreg);
});

document.getElementById("addUsuario").addEventListener("click", function () {
  // Limpiar los campos
  limpiarCampos();
  // Ocultar el botón de actualizar
  document.getElementById("actualizarUsuario").style.display = "none";
  // Mostrar el botón de guardar
  document.getElementById("guardarUsuario").style.display = "block";
  traerPerfiles();
});

function leerdatos(id) {
  // Limpiar los campos
  limpiarCampos();
  traerPerfiles();

  let ladata = new FormData();
  ladata.append('id', id);
  ladata.append('ind', 1);

  fetch('backend/usuariosBackend.php', {
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
      $('#largeModal').modal('show');
      // Asegúrate de acceder al primer elemento del array
      const usuario = data.rta[0]; // Accede al primer elemento
      document.getElementById('documento').value = usuario.documento;
      document.getElementById('telefono').value = usuario.telefono;
      document.getElementById('nombre').value = usuario.nombre_completo;
      document.getElementById('correo').value = usuario.correo;
      document.getElementById('sel_perfil').value = usuario.idPerfil; // Asegúrate de que este campo espera el ID del perfil
      // Guardar el ID del usuario en un campo oculto (Asegúrate de que el campo 'idUsuario' exista)
      document.getElementById('idUsuario').value = usuario.id; // Esta línea asume que has añadido un input oculto para el ID
      // Ocultar el botón de guardar
      document.getElementById("guardarUsuario").style.display = "none";
      // Mostrar el botón de actualizar (Asegúrate de que este botón exista o ajusta según tu código)
      document.getElementById("actualizarUsuario").style.display = "block";
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

function limpiarCampos() {
  document.getElementById('documento').value = '';
  document.getElementById('telefono').value = '';
  document.getElementById('nombre').value = '';
  document.getElementById('correo').value = '';
  document.getElementById('sel_perfil').value = '0';
}

function lisrarUsuarios(ind, inicio, nroreg) {
  let nuevoinicio = (inicio - 1) * parseInt(nroreg);

  let ladata = new FormData();
  ladata.append('ind', ind);
  ladata.append('nuevoinicio', nuevoinicio);
  ladata.append('nroreg', nroreg);

  fetch('backend/usuariosBackend.php', {
    method: 'POST',
    body: ladata,

  }).then(function (response) {

    return response.json();
  }).then(function (data) {
    $('#tablaUsuarios tbody').empty();
    $("#paginacion").empty();
    let htmlTags = "";
    console.log(data);

    let i = 0;
    data.rta2.forEach(function (item) {
      i = i + 1;

      htmlTags += '<tr>';
      htmlTags += '     <td>' + item.documento + '</td>';
      htmlTags += '     <td>' + item.nombre_completo + '</td>';
      htmlTags += '     <td>' + item.telefono + '</td>';
      htmlTags += '     <td>' + item.correo + '</td>';
      htmlTags += '     <td>';
      htmlTags += '         <a class="me-3" id="detail" onclick="detailUsuario(' + item.id + ');" name="detail" data-validar="SI"';
      htmlTags += '         data-tipo="TABLA_MODAL" data-apodo="Detalles Producto" style="cursor: pointer;"><img src="//pos.metaversomsp.com/assets/img/icons/eye.svg" alt="img"></a>';
      htmlTags += '         <a class="me-3" id="editProducto" onclick="leerdatos(' + item.id + ');" name="editProducto"';
      htmlTags += '         data-validar="SI" data-tipo="TABLA_MODAL" data-apodo="Editar Producto" style="cursor: pointer;"><img src="//pos.metaversomsp.com/assets/img/icons/edit.svg" alt="img"></a>';
      htmlTags += '         <a class="me-3" id="deleteproducto" onclick="deleteUsuario(' + item.id + ');" name="deleteproducto"';
      htmlTags += '         data-validar="SI" data-tipo="TABLA_MODAL" data-apodo="Eliminar Producto" style="cursor: pointer;"><img src="//pos.metaversomsp.com/assets/img/icons/delete.svg" alt="img"></a>';
      htmlTags += '     </td>';
      // htmlTags += '     <td><img src="esl1.png" alt="ESL"  width="100px"></td>';
      htmlTags += ' </tr>';
    });
    $('#tablaUsuarios tbody').append(htmlTags);
    //Paginador
    let paginador = "";

    paginador += '<ul class="pagination justify-content-end">';

    paginador += '<li style="margin-left: 0px;margin-right: 0px;"><span class="label label pagina">' + data.rta + ' Registros</span></li>';

    if (inicio > 1) {
      ``
      paginador += '<li class="notDisable" style="margin-left: 0px;margin-right: 0px;"><a class="pagina" href="javascript:void(0)" onclick="lisrarUsuarios(\'2\', 1, ' + nroreg + ', \'\', \'\', \'\', \'\', \'\', \'\')">&laquo;</a></li>';
      paginador += '<li class="notDisable" style="margin-left: 0px;margin-right: 0px;"><a class="pagina" href="javascript:void(0)" onclick="lisrarUsuarios(\'2\', ' + (inicio - 1) + ', ' + nroreg + ', \'\', \'\', \'\', \'\', \'\', \'\')">&lsaquo;</a></li>';

    } else {
      paginador += '<li class="disabled" style="margin-left: 0px;margin-right: 0px;" ><a class="pagina" href="javascript:void(0)">&laquo;</a></li>';
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
        paginador += '<li style="margin-left: 0px;margin-right: 0px;"><a class="pagina" href="javascript:void(0)" onclick="lisrarUsuarios(\' 2\', ' + i + ' , ' + nroreg + ' , \'\', \'\', \'\', \'\', \'\', \'\')">' + i + '</a></li>';
      }

    }
    if (inicio < Math.ceil(data.rta / parseInt(nroreg))) {
      paginador += '<li class="notDisable" style="margin-left: 0px;margin-right: 0px;"><a class="pagina" href="javascript:void(0)" onclick="lisrarUsuarios(' + "2" + ',' + (inicio + 1) + ',' + nroreg + ',\' \',\'\',\'\',\'\',\'\',\'\')">&rsaquo;</a></li>';
      paginador += '<li class="notDisable" style="margin-left: 0px;margin-right: 0px;"><a class="pagina" href="javascript:void(0)" onclick="lisrarUsuarios(' + " 2" + ',' + Math.ceil(data.rta / nroreg) + ',' + nroreg + ',\' \',\'\',\'\',\'\',\'\',\'\')">&raquo;</a></li>';
    } else {
      paginador += '<li class="disabled" style="margin-left: 0px;margin-right: 0px;"><a class="pagina" href="javascript:void(0)">&rsaquo;</a></li>';
      paginador += '<li class="disabled" style="margin-left: 0px;margin-right: 0px;"><a class="pagina" href="javascript:void(0)">&raquo;</a></li>';
    }
    paginador += '<li style="margin-left: 0px;margin-right: 0px;"><span class="label label pagina">' + Math.ceil(data.rta / parseInt(nroreg)) + ' Páginas</span></li>';

    paginador += '</ul>';
    //Fin del paginador

    let tablaUsuarios = document.querySelector('#tablaUsuarios');
    $('#paginacion').append(paginador);
    tablaUsuarios.append();

  }).catch(function (error) {
    console.error("Error al obtener productos:", error);
  });
}

function validarCampos() {

  var documento = document.getElementById('documento');
  var telefono = document.getElementById('telefono');
  var nombre = document.getElementById('nombre');
  var correo = document.getElementById('correo');
  var perfil = document.getElementById('sel_perfil');

  var campos = [documento, telefono, nombre, correo, perfil];
  var nombresCampos = ['Documento', 'Teléfono', 'Nombre', 'Correo', 'Perfil'];

  for (var i = 0; i < campos.length; i++) {
    if (campos[i].value.trim() === '' || campos[i].value === '0') {
      campos[i].style.borderColor = 'red';
      Swal.fire({
        icon: 'error',
        title: 'Campo faltante',
        text: `El campo ${nombresCampos[i]} es obligatorio.`,
      });
      return false;
    } else {
      campos[i].style.borderColor = ''; // Resetear el color del borde
    }
  }

  // Validar que el documento tenga exactamente 10 dígitos
  var documentoValor = documento.value.trim();
  if (!/^\d{10}$/.test(documentoValor)) {
    documento.style.borderColor = 'red';
    Swal.fire({
      icon: 'error',
      title: 'Documento inválido',
      text: 'El documento debe tener exactamente 10 dígitos.',
    });
    return false;
  }

  // Validar que el teléfono tenga al menos 7 dígitos y solo contenga números
  var telefonoValor = telefono.value.trim();
  if (!/^\d{7,}$/.test(telefonoValor)) {
    telefono.style.borderColor = 'red';
    Swal.fire({
      icon: 'error',
      title: 'Teléfono inválido',
      text: 'El teléfono debe tener al menos 7 dígitos y solo contener números.',
    });
    return false;
  }

  // Validar que el correo tenga un formato válido
  var correoValor = correo.value.trim();
  if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(correoValor)) {
    correo.style.borderColor = 'red';
    Swal.fire({
      icon: 'error',
      title: 'Correo inválido',
      text: 'El correo debe tener un formato válido (ej. ejemplo@dominio.com).',
    });
    return false;
  }

  return true;
}

function Save_usuario() {
  if (!validarCampos()) {
    return;
  }

  // Obtener los valores de los campos de entrada
  var documento = document.getElementById('documento').value;
  var telefono = document.getElementById('telefono').value;
  var nombre = document.getElementById('nombre').value;
  var correo = document.getElementById('correo').value;
  var perfil = document.getElementById('sel_perfil').value;

  let ladata = new FormData();
  ladata.append('documento', documento);
  ladata.append('telefono', telefono);
  ladata.append('nombre', nombre);
  ladata.append('correo', correo);
  ladata.append('perfil', perfil);
  ladata.append('ind', 3);

  // Realizar solicitud fetch al backend
  fetch('backend/usuariosBackend.php', {
    method: 'POST',
    body: ladata,
  })
    .then(response => {
      if (response.ok) {
        return response.json();
      } else {
        throw new Error('Error al guardar el usuario');
      }
    })
    .then(data => {
      // Aquí puedes manejar la respuesta del backend si es necesario
      // console.log(data);
      if (data.rta === 'ok') {
        // Limpiar los campos del formulario
        limpiarCampos();
        // Ejecutar la función lisrarUsuarios() con los parámetros necesarios
        lisrarUsuarios(2, 1, 5);
        // Cerrar la modal y sus elementos
        $('#largeModal').modal('hide');
        $('.modal-backdrop').remove();
        Swal.fire({
          icon: 'success',
          title: '¡Usuario guardado exitosamente!',
          text: 'El usuario ha sido guardado correctamente.',
        });
      } else {
        // Mostrar SweetAlert de error
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Hubo un problema al guardar el usuario. Por favor, inténtelo de nuevo más tarde.',
        });
      }
    })
    .catch(error => {
      console.error('Error:', error);
      // Mostrar SweetAlert de error en caso de fallo en la solicitud
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Hubo un problema al guardar el usuario. Por favor, inténtelo de nuevo más tarde.',
      });
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

function detailUsuario(id) {
  let ladata = new FormData();
  ladata.append('id', id);
  ladata.append('ind', 6);

  fetch('backend/usuariosBackend.php', {
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
      document.getElementById('showDocumento').textContent = data.rta.documento;
      document.getElementById('showTelefono').textContent = data.rta.telefono;
      document.getElementById('showNombre').textContent = data.rta.nombre_completo;
      document.getElementById('showCorreo').textContent = data.rta.correo;
      // Para el perfil, asumiendo que quieres mostrar el nombre del perfil y no su ID
      // Necesitarás ajustar esta línea según cómo estés manejando los perfiles
      document.getElementById('showPerfil').textContent = data.rta.nombrePerfil; // Asegúrate de que 'perfil' es la propiedad correcta
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

function Update_usuario() {
  // Obtener los valores de los campos de entrada
  var documento = document.getElementById('documento').value;
  var telefono = document.getElementById('telefono').value;
  var nombre = document.getElementById('nombre').value;
  var correo = document.getElementById('correo').value;
  var perfil = document.getElementById('sel_perfil').value;
  var id = document.getElementById('idUsuario').value;

  let ladata = new FormData();
  ladata.append('documento', documento);
  ladata.append('telefono', telefono);
  ladata.append('nombre', nombre);
  ladata.append('correo', correo);
  ladata.append('perfil', perfil);
  ladata.append('id', id);
  ladata.append('ind', 4);

  // Realizar solicitud fetch al backend
  fetch('backend/usuariosBackend.php', {
    method: 'POST',
    body: ladata,
  })
    .then(response => {
      if (response.ok) {
        return response.json();
      } else {
        throw new Error('Error al actualizar el usuario');
      }
    })
    .then(data => {
      // Aquí puedes manejar la respuesta del backend si es necesario
      // console.log(data);
      if (data.rta === 'ok') {
        // Limpiar los campos del formulario
        limpiarCampos();
        // Ejecutar la función lisrarUsuarios() con los parámetros necesarios
        lisrarUsuarios(2, 1, 5);
        // Cerrar la modal y sus elementos
        $('#largeModal').modal('hide');
        $('.modal-backdrop').remove();
        Swal.fire({
          icon: 'success',
          title: '¡Usuario actualizado exitosamente!',
          text: 'El usuario ha sido actualizado correctamente.',
        });
      } else {
        // Mostrar SweetAlert de error
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Hubo un problema al actualizar el usuario. Por favor, inténtelo de nuevo más tarde.',
        });
      }
    })
    .catch(error => {
      console.error('Error:', error);
      // Mostrar SweetAlert de error en caso de fallo en la solicitud
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Hubo un problema al actualizar el usuario. Por favor, inténtelo de nuevo más tarde.',
      });
    }
    );
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

      fetch('backend/usuariosBackend.php', {
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
            lisrarUsuarios(2, 1, 5);
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
