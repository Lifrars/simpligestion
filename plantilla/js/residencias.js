$(document).ready(function () {
  let nuevoinicio = 1;
  let nroreg = 5;
  listarResidencias(2, nuevoinicio, nroreg);
  listarTorresDisponibles();
});

document.getElementById("addResidencia").addEventListener("click", function () {
  // Limpiar los campos
  limpiarCampos();
  // Ocultar el botón de actualizar
  document.getElementById("actualizarResidencia").style.display = "none";
  // Mostrar el botón de guardar
  document.getElementById("guardarResidencia").style.display = "block";
});

function listarTorresDisponibles() {
  fetch('backend/residenciasBackend.php', {
    method: 'POST',
    body: new URLSearchParams({ 'ind': '8' }),  // Usar el indicador para listar torres
  })
  .then(response => response.json())
  .then(data => {
    const selector = document.getElementById('idTorre');
    data.rta2.forEach(torre => {
      const option = document.createElement('option');
      option.value = torre.id_torre;
      option.textContent = torre.nombre_torre;
      selector.appendChild(option);
    });
  })
  .catch(error => console.error("Error al obtener torres:", error));
}

function convertirAMayusculas() {
  var direccionResidencia = document.getElementById('direccionResidencia');
  var direccion = direccionResidencia.value.toUpperCase();  // Convierte el texto a mayúsculas
  direccionResidencia.value = direccion;  // Actualiza el campo con la dirección en mayúsculas

  // Verifica si la dirección ingresada ya existe en la tabla
  var residencias = document.querySelectorAll('#tablaResidencias tbody tr');  // Selecciona todas las filas de la tabla
  var existe = false;

  // Itera sobre las filas de la tabla para verificar si la dirección ya está registrada
  residencias.forEach(function (fila) {
      var direccionResidenciaEnTabla = fila.cells[0].textContent.trim();  // Obtiene la dirección de la residencia en la tabla
      if (direccionResidenciaEnTabla === direccion) {
          existe = true;
      }
  });

  // Si la dirección ya existe, muestra una alerta
  if (existe) {
      Swal.fire({
          icon: 'error',
          title: '¡Error!',
          text: 'Ya existe una residencia con esa dirección.',
          confirmButtonText: 'Aceptar'
      });
      direccionResidencia.value = '';  // Limpia el campo de dirección
  }
}

document.addEventListener("DOMContentLoaded", function () {
  const direccionResidenciaInput = document.getElementById("direccionResidencia");
  const documentoPropietarioInput = document.getElementById("documentoPropietario");

  // Validar Dirección Residencia: Solo mayúsculas y números, máximo 10 caracteres
  direccionResidenciaInput.addEventListener("input", function () {
      this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '').slice(0, 10);
  });

  // Validar Documento Propietario: Solo números
  documentoPropietarioInput.addEventListener("input", function () {
      this.value = this.value.replace(/[^0-9]/g, ''); // Eliminar caracteres no numéricos
  });
});

function leerDatos(id) {
  // Limpiar los campos
  limpiarCampos();

  let ladata = new FormData();
  ladata.append('id', id);
  ladata.append('ind', 1);

  fetch('backend/residenciasBackend.php', {
    method: 'POST',
    body: ladata,
  })
    .then(response => response.json())
    .then(data => {
      $('#modalResidencia').modal('show');
      const residencia = data.rta[0];
      document.getElementById('direccionResidencia').value = residencia.direccion_residencia;
      document.getElementById('documentoPropietario').value = residencia.documento_usuario_propietario;
      document.getElementById('idResidencia').value = residencia.id_residencia;
      document.getElementById('idTorre').value = residencia.id_torre;

      document.getElementById("guardarResidencia").style.display = "none";
      document.getElementById("actualizarResidencia").style.display = "block";
    })
    .catch(error => {
      console.error('Error:', error);
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Hubo un problema al obtener los datos de la residencia.',
      });
    });
}

function limpiarCampos() {
  document.getElementById('direccionResidencia').value = '';
  document.getElementById('documentoPropietario').value = '';
}

function listarResidencias(ind, inicio, nroreg) {
  let nuevoinicio = (inicio - 1) * parseInt(nroreg);

  let ladata = new FormData();
  ladata.append('ind', ind);
  ladata.append('nuevoinicio', nuevoinicio.toString());
  ladata.append('nroreg', nroreg.toString());

  fetch('backend/residenciasBackend.php', {
    method: 'POST',
    body: ladata,
  })
    .then(response => response.json())
    .then(data => {
      $('#tablaResidencias tbody').empty();
      $("#paginacion").empty();
      let htmlTags = "";

      data.rta2.forEach(item => {
        htmlTags += '<tr>';
        htmlTags += `     <td>${item.direccion_residencia}</td>`;
        htmlTags += `     <td>${item.documento_usuario_propietario}</td>`;
        htmlTags += `     <td>${item.nombre_torre}</td>`;
        htmlTags += '     <td>';
        htmlTags += `         <a class="me-3" onclick="detalleResidencia(${item.id_residencia});" style="cursor: pointer;"><img src="//ppi.miclickderecho.com/plantilla/assets/img/icons/eye.svg" alt="img"></a>`;
        htmlTags += `         <a class="me-3" onclick="leerDatos(${item.id_residencia});" style="cursor: pointer;"><img src="//ppi.miclickderecho.com/plantilla/assets/img/icons/edit.svg" alt="img"></a>`;
        htmlTags += `         <a class="me-3" onclick="eliminarResidencia(${item.id_residencia});" style="cursor: pointer;"><img src="//ppi.miclickderecho.com/plantilla/assets/img/icons/delete.svg" alt="img"></a>`;
        htmlTags += '     </td>';
        htmlTags += ' </tr>';
      });

      $('#tablaResidencias tbody').append(htmlTags);
    })
    .catch(error => console.error("Error al obtener residencias:", error));
}

function guardarResidencia() {
  let direccion = document.getElementById('direccionResidencia').value;
  let documento = document.getElementById('documentoPropietario').value;
  let nombreTorre = document.getElementById('idTorre').value;

  let ladata = new FormData();
  ladata.append('direccion_residencia', direccion);
  ladata.append('documento_usuario_propietario', documento);
  ladata.append('nombre_torre', nombreTorre);
  ladata.append('ind', '3');

  fetch('backend/residenciasBackend.php', {
    method: 'POST',
    body: ladata,
  })
    .then(response => response.json())
    .then(data => {
      if (data.rta === 'ok') {
        limpiarCampos();
        listarResidencias(2, 1, 5);
        $('#modalResidencia').modal('hide');
        Swal.fire({
          icon: 'success',
          title: '¡Residencia guardada exitosamente!',
          text: 'La residencia ha sido guardada correctamente.',
        });
      }
    })
    .catch(error => {
      console.error('Error:', error);
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Hubo un problema al guardar la residencia.',
      });
    });
}

function detalleResidencia(id) {
  let ladata = new FormData();
  ladata.append('id', id);
  ladata.append('ind', 6);

  fetch('backend/residenciasBackend.php', {
    method: 'POST',
    body: ladata,
  })
    .then(response => response.json())
    .then(data => {
      $('#modalMostrarResidencia').modal('show');
      document.getElementById('showDireccionResidencia').textContent = data.rta.direccion_residencia;
      document.getElementById('showDocumentoPropietario').textContent = data.rta.documento_usuario_propietario;
      document.getElementById('showNombreTorre').textContent = data.rta.nombre_torre;
    })
    .catch(error => {
      console.error('Error:', error);
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Hubo un problema al obtener los datos de la residencia.',
      });
    });
}

function actualizarResidencia() {
  let direccion = document.getElementById('direccionResidencia').value;
  let documento = document.getElementById('documentoPropietario').value;
  let id = document.getElementById('idResidencia').value;
  let nombreTorre = document.getElementById('idTorre').value;

  let ladata = new FormData();
  ladata.append('direccion_residencia', direccion);
  ladata.append('documento_usuario_propietario', documento);
  ladata.append('nombre_torre', nombreTorre);
  ladata.append('id', id);
  ladata.append('ind', '4');

  fetch('backend/residenciasBackend.php', {
    method: 'POST',
    body: ladata,
  })
    .then(response => response.json())
    .then(data => {
      if (data.rta === 'ok') {
        limpiarCampos();
        listarResidencias(2, 1, 5);
        $('#modalResidencia').modal('hide');
        Swal.fire({
          icon: 'success',
          title: '¡Residencia actualizada exitosamente!',
          text: 'La residencia ha sido actualizada correctamente.',
        });
      }
    })
    .catch(error => {
      console.error('Error:', error);
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Hubo un problema al actualizar la residencia.',
      });
    });
}

function eliminarResidencia(id) {
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
      ladata.append('ind', '7');

      fetch('backend/residenciasBackend.php', {
        method: 'POST',
        body: ladata,
      })
        .then(response => response.json())
        .then(data => {
          if (data.rta === 'ok') {
            listarResidencias(2, 1, 5);
            Swal.fire(
              '¡Eliminada!',
              'La residencia ha sido eliminada.',
              'success'
            );
          }
        })
        .catch(error => {
          console.error('Error:', error);
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Hubo un problema al eliminar la residencia.',
          });
        });
    }
  });
}