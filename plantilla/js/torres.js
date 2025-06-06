$(document).ready(function () {
  let nuevoinicio = 1;
  let nroreg = 5;
  listarTorres(2, nuevoinicio, nroreg);
});
document.getElementById('buscador').addEventListener('input', function () {
    const filtro = this.value.toLowerCase();
    const filas = document.querySelectorAll('#tablaTorres  tbody tr');

    filas.forEach(fila => {
        const textoFila = fila.textContent.toLowerCase();
        fila.style.display = textoFila.includes(filtro) ? '' : 'none';
    });
});
document.getElementById("addTorre").addEventListener("click", function () {
  // Limpiar los campos
  limpiarCampos();
  // Ocultar el botón de actualizar
  document.getElementById("actualizarTorre").style.display = "none";
  // Mostrar el botón de guardar
  document.getElementById("guardarTorre").style.display = "block";
});

function convertirAMayusculas() {
  var nombreTorre = document.getElementById('nombreTorre');
  var nombre = nombreTorre.value.toUpperCase();  // Convierte el texto a mayúsculas
  nombreTorre.value = nombre;  // Actualiza el campo con el nombre en mayúsculas

  // Verifica si el nombre ingresado ya existe en la tabla
  var torres = document.querySelectorAll('#tablaTorres tbody tr');  // Selecciona todas las filas de la tabla
  var existe = false;

  // Itera sobre las filas de la tabla para verificar si el nombre ya está registrado
  torres.forEach(function (fila) {
    var nombreTorreEnTabla = fila.cells[0].textContent.trim();  // Obtiene el nombre de la torre en la tabla
    if (nombreTorreEnTabla === nombre) {
      existe = true;
    }
  });

  // Si el nombre ya existe, muestra una alerta
  if (existe) {
    Swal.fire({
      icon: 'error',
      title: '¡Error!',
      text: 'Ya existe una torre con ese nombre.',
      confirmButtonText: 'Aceptar'
    });
    nombreTorre.value = '';  // Limpia el campo de nombre
  }
}

document.addEventListener("DOMContentLoaded", function () {
  const nombreTorreInput = document.getElementById("nombreTorre");
  const numPisosInput = document.getElementById("numPisos");

  // Validar Nombre Torre: Solo mayúsculas y números, máximo 10 caracteres
  nombreTorreInput.addEventListener("input", function () {
    this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '').slice(0, 10);
  });

  // Validar Número de Pisos: Solo números del 1 al 5
  numPisosInput.addEventListener("input", function () {
    this.value = this.value.replace(/[^0-9]/g, ''); // Eliminar caracteres no numéricos

    if (this.value !== "") {
      let num = parseInt(this.value, 10);

      if (num > 5) {
        this.value = "5"; // Si ingresa más de 5, lo corrige automáticamente a 5
      } else if (num < 1) {
        this.value = "1"; // Si intenta poner 0 o vacío, se corrige a 1
      }
    }
  });

  // Evitar que el usuario ingrese valores inválidos en tiempo real (ejemplo: pegar "10")
  numPisosInput.addEventListener("blur", function () {
    if (this.value === "" || parseInt(this.value, 10) > 5) {
      this.value = "5";
    }
    if (parseInt(this.value, 10) < 1) {
      this.value = "1";
    }
  });
});


function leerDatos(id) {
  // Limpiar los campos
  limpiarCampos();

  let ladata = new FormData();
  ladata.append('id', id);
  ladata.append('ind', 1);

  fetch('backend/torresBackend.php', {
    method: 'POST',
    body: ladata,
  })
    .then(response => response.json())
    .then(data => {
      $('#modalTorre').modal('show');
      const torre = data.rta[0];
      document.getElementById('nombreTorre').value = torre.nombre_torre;
      document.getElementById('numPisos').value = torre.numero_pisos_torre;
      document.getElementById('idTorre').value = torre.id_torre;

      document.getElementById("guardarTorre").style.display = "none";
      document.getElementById("actualizarTorre").style.display = "block";
    })
    .catch(error => {
      console.error('Error:', error);
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Hubo un problema al obtener los datos de la torre.',
      });
    });
}

function limpiarCampos() {
  document.getElementById('nombreTorre').value = '';
  document.getElementById('numPisos').value = '';
}

function listarTorres(ind, inicio, nroreg) {
  let nuevoinicio = (inicio - 1) * parseInt(nroreg);

  let ladata = new FormData();
  ladata.append('ind', ind);
  ladata.append('nuevoinicio', nuevoinicio.toString());
  ladata.append('nroreg', nroreg.toString());

  fetch('backend/torresBackend.php', {
    method: 'POST',
    body: ladata,
  })
    .then(response => response.json())
    .then(data => {
      $('#tablaTorres tbody').empty();
      $("#paginacion").empty();
      let htmlTags = "";

      data.rta2.forEach(item => {
        htmlTags += '<tr>';
        htmlTags += `     <td>${item.torre}</td>`;
        htmlTags += `     <td>${item.piso}</td>`;
        htmlTags += `     <td>${item.residencia}</td>`;
        htmlTags += '     <td>';
        htmlTags += `         <a class="me-3" onclick="detalleResidencia(${item.id_residencia});" style="cursor: pointer;"><img src="//ppi.miclickderecho.com/plantilla/assets/img/icons/eye.svg" alt="img"></a>`;
        // htmlTags += `         <a class="me-3" onclick="leerDatos(${item.id_residencia});" style="cursor: pointer;"><img src="//ppi.miclickderecho.com/plantilla/assets/img/icons/edit.svg" alt="img"></a>`;
        htmlTags += '     </td>';
        htmlTags += ' </tr>';
      });

      $('#tablaTorres tbody').append(htmlTags);
      //Paginador
      let paginador = "";

      paginador += '<ul class="pagination justify-content-end">';

      paginador += '<li style="margin-left: 0px;margin-right: 0px;"><span class="label label pagina">' + data.rta + ' Registros</span></li>';

      if (inicio > 1) {
        ``
        paginador += '<li class="notDisable" style="margin-left: 0px;margin-right: 0px;"><a class="pagina" href="javascript:void(0)" onclick="listarTorres(\'2\', 1, ' + nroreg + ', \'\', \'\', \'\', \'\', \'\', \'\')">&laquo;</a></li>';
        paginador += '<li class="notDisable" style="margin-left: 0px;margin-right: 0px;"><a class="pagina" href="javascript:void(0)" onclick="listarTorres(\'2\', ' + (inicio - 1) + ', ' + nroreg + ', \'\', \'\', \'\', \'\', \'\', \'\')">&lsaquo;</a></li>';

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
          paginador += '<li style="margin-left: 0px;margin-right: 0px;"><a class="pagina" href="javascript:void(0)" onclick="listarTorres(\' 2\', ' + i + ' , ' + nroreg + ' , \'\', \'\', \'\', \'\', \'\', \'\')">' + i + '</a></li>';
        }

      }
      if (inicio < Math.ceil(data.rta / parseInt(nroreg))) {
        paginador += '<li class="notDisable" style="margin-left: 0px;margin-right: 0px;"><a class="pagina" href="javascript:void(0)" onclick="listarTorres(' + "2" + ',' + (inicio + 1) + ',' + nroreg + ',\' \',\'\',\'\',\'\',\'\',\'\')">&rsaquo;</a></li>';
        paginador += '<li class="notDisable" style="margin-left: 0px;margin-right: 0px;"><a class="pagina" href="javascript:void(0)" onclick="listarTorres(' + " 2" + ',' + Math.ceil(data.rta / nroreg) + ',' + nroreg + ',\' \',\'\',\'\',\'\',\'\',\'\')">&raquo;</a></li>';
      } else {
        paginador += '<li class="disabled" style="margin-left: 0px;margin-right: 0px;"><a class="pagina" href="javascript:void(0)">&rsaquo;</a></li>';
        paginador += '<li class="disabled" style="margin-left: 0px;margin-right: 0px;"><a class="pagina" href="javascript:void(0)">&raquo;</a></li>';
      }
      paginador += '<li style="margin-left: 0px;margin-right: 0px;"><span class="label label pagina">' + Math.ceil(data.rta / parseInt(nroreg)) + ' Páginas</span></li>';

      paginador += '</ul>';
      //Fin del paginador

      let tablaUsuarios = document.querySelector('#tablaTorres');
      $('#paginacion').append(paginador);
      tablaUsuarios.append();

    }).catch(function (error) {
      console.error("Error al obtener productos:", error);
    });
}

function limitarNumero() {
  var numPisos = document.getElementById('numPisos');
  var valor = numPisos.value;

  // Limitar el número de caracteres a 5 dígitos y asegurarse de que solo sean números
  if (valor.length > 5) {
    numPisos.value = valor.slice(0, 5);  // Limita el valor a 5 caracteres
  }
}

function guardarTorre() {
  let nombre = document.getElementById('nombreTorre').value;
  let numPisos = document.getElementById('numPisos').value;

  let ladata = new FormData();
  ladata.append('nombre_torre', nombre);
  ladata.append('numero_pisos_torre', numPisos);
  ladata.append('ind', '3');

  fetch('backend/torresBackend.php', {
    method: 'POST',
    body: ladata,
  })
    .then(response => response.json())
    .then(data => {
      if (data.rta === 'ok') {
        limpiarCampos();
        listarTorres(2, 1, 5);
        $('#modalTorre').modal('hide');
        Swal.fire({
          icon: 'success',
          title: '¡Torre guardada exitosamente!',
          text: 'La torre ha sido guardada correctamente.',
        });
      }
    })
    .catch(error => {
      console.error('Error:', error);
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Hubo un problema al guardar la torre.',
      });
    });
}

function detalleResidencia(id_residencia) {
  const data = new FormData();
  data.append("ind", "6");
  data.append("id", id_residencia);

  fetch("backend/torresBackend.php", {
    method: "POST",
    body: data,
  })
    .then(response => response.json())
    .then(result => {
      const r = result.rta;

      document.getElementById("showTorre").textContent = r.nombre_torre ?? "No asignada";
      document.getElementById("showPiso").textContent = r.id_piso_residencia ?? "No definido";
      document.getElementById("showNumeroResidencia").textContent = r.numero_residencia ?? "Sin número";
      document.getElementById("showPropietario").textContent = r.propietario_nombre + (r.propietario_documento ? ` (${r.propietario_documento})` : "");

      const lista = document.getElementById("listaResidentes");
      lista.innerHTML = "";

      if (r.residentes.length > 0) {
        r.residentes.forEach(residente => {
          const li = document.createElement("li");
          li.className = "list-group-item";
          li.textContent = residente.nombre_completo;
          lista.appendChild(li);
        });
      } else {
        const li = document.createElement("li");
        li.className = "list-group-item";
        li.textContent = "Sin residentes asignados.";
        lista.appendChild(li);
      }

      $('#modalMostrarResidencia').modal('show');
    })
    .catch(error => {
      console.error("Error al obtener los datos:", error);
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Hubo un problema al obtener los datos de la residencia.',
      });
    });
}

function actualizarTorre() {
  let nombre = document.getElementById('nombreTorre').value;
  let numPisos = document.getElementById('numPisos').value;
  let id = document.getElementById('idTorre').value;

  let ladata = new FormData();
  ladata.append('nombre_torre', nombre);
  ladata.append('numero_pisos_torre', numPisos);
  ladata.append('id', id);
  ladata.append('ind', '4');

  fetch('backend/torresBackend.php', {
    method: 'POST',
    body: ladata,
  })
    .then(response => response.json())
    .then(data => {
      if (data.rta === 'ok') {
        limpiarCampos();
        listarTorres(2, 1, 5);
        $('#modalTorre').modal('hide');
        Swal.fire({
          icon: 'success',
          title: '¡Torre actualizada exitosamente!',
          text: 'La torre ha sido actualizada correctamente.',
        });
      }
    })
    .catch(error => {
      console.error('Error:', error);
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Hubo un problema al actualizar la torre.',
      });
    });
}