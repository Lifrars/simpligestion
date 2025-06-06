$(document).ready(function () {
  let nuevoinicio = 1; //Variable de sesion?
  let nroreg = 5; //Variable de sesion?
  listarElecciones(1, nuevoinicio, nroreg);
});

document.getElementById('buscador').addEventListener('input', function () {
    const filtro = this.value.toLowerCase();
    const filas = document.querySelectorAll('#tablaVotar tbody tr');

    filas.forEach(fila => {
        const textoFila = fila.textContent.toLowerCase();
        fila.style.display = textoFila.includes(filtro) ? '' : 'none';
    });
});

function listarElecciones(ind, inicio, nroreg) {
  let nuevoinicio = (inicio - 1) * parseInt(nroreg);//ni idea para que es
  let ladata = new FormData();
  ladata.append('ind', ind);//indicador
  ladata.append('nuevoinicio', nuevoinicio);
  ladata.append('nroreg', nroreg);
  fetch('backend/votacionesBackend.php', { //Pa donde vamos
    method: 'POST',
    body: ladata,
  }).then(function (response) {
    return response.json();
  }).then(function (data) {
    $('#tablaVotar tbody').empty();//Que quiere
    $("#paginacion").empty();
    let htmlTags = "";
    console.log(data);
    let i = 0;
    data.rta2.forEach(function (item) {
      console.log(item)
      i = i + 1;
      htmlTags += '<tr>';
      htmlTags += '     <td>' + item.titulo + '</td>';//Campos de la consulta
      htmlTags += '     <td>' + item.descripcion2 + '</td>';
      
      htmlTags += '     <td>' + (item.estado === "1" ? "Sin Ganador" : item.descripcion) + '</td>';
      htmlTags += '     <td>' + item.fecha_cierre_votacion + '</td>';
      htmlTags += '<td>';
      htmlTags += '<a href="grafico.php?dato=' + item.id + '" class="me-3" id="detail" onclick="detailvotacion(' + item.id + ');" name="detail" data-validar="SI" ';
      htmlTags += 'data-tipo="TABLA_MODAL" data-apodo="Detalles Votacion" style="cursor: pointer;" title="Detalles Votación"><img src="//ppi.miclickderecho.com/plantilla/assets/img/icons/eye.svg" alt="img"></a>';
      if (item.estado === "1") {
        htmlTags += '<a class="me-3" id="Votar" onclick="traervotacion(' + item.id + ');" name="Votar"';
        htmlTags += 'data-validar="SI" data-tipo="TABLA_MODAL" data-apodo="Votar" style="cursor: pointer;" title="Votar"><img src="//ppi.miclickderecho.com/plantilla/assets/img/icons/edit.svg" alt="img"></a>';
      } else {
        htmlTags += '<a class="me-3" id="Votar" onclick="Estado2(' + item.id + ');" name="Votar"';
        htmlTags += 'data-validar="SI" data-tipo="TABLA_MODAL" data-apodo="Votar" style="cursor: pointer;" title="Votar"><img src="//ppi.miclickderecho.com/plantilla/assets/img/icons/closes.svg" alt="img"></a>';
      }
      htmlTags += '</td>';
      
      htmlTags += ' </tr>';
    });

    $('#tablaVotar tbody').append(htmlTags);
    //Paginador
    let paginador = "";
    paginador += '<ul class="pagination justify-content-end">';
    paginador += '<li style="margin-left: 0px;margin-right: 0px;"><span class="label label pagina">' + data.rta + ' Registros</span></li>';
    if (inicio > 1) {
      ``
      paginador += '<li class="notDisable" style="margin-left: 0px;margin-right: 0px;"><a class="pagina" href="javascript:void(0)" onclick="listarElecciones(\'2\', 1, ' + nroreg + ', \'\', \'\', \'\', \'\', \'\', \'\')">&laquo;</a></li>';
      paginador += '<li class="notDisable" style="margin-left: 0px;margin-right: 0px;"><a class="pagina" href="javascript:void(0)" onclick="listarElecciones(\'2\', ' + (inicio - 1) + ', ' + nroreg + ', \'\', \'\', \'\', \'\', \'\', \'\')">&lsaquo;</a></li>';
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
        paginador += '<li style="margin-left: 0px;margin-right: 0px;"><a class="pagina" href="javascript:void(0)" onclick="listarElecciones(\' 1\', ' + i + ' , ' + nroreg + ' , \'\', \'\', \'\', \'\', \'\', \'\')">' + i + '</a></li>';
      }
    }
    if (inicio < Math.ceil(data.rta / parseInt(nroreg))) {
      paginador += '<li class="notDisable" style="margin-left: 0px;margin-right: 0px;"><a class="pagina" href="javascript:void(0)" onclick="listarElecciones(1' + (inicio + 1) + ',' + nroreg + ',\' \',\'\',\'\',\'\',\'\',\'\')">&rsaquo;</a></li>';
      paginador += '<li class="notDisable" style="margin-left: 0px;margin-right: 0px;"><a class="pagina" href="javascript:void(0)" onclick="listarElecciones(1' + Math.ceil(data.rta / nroreg) + ',' + nroreg + ',\' \',\'\',\'\',\'\',\'\',\'\')">&raquo;</a></li>';
    } else {
      paginador += '<li class="disabled" style="margin-left: 0px;margin-right: 0px;"><a class="pagina" href="javascript:void(0)">&rsaquo;</a></li>';
      paginador += '<li class="disabled" style="margin-left: 0px;margin-right: 0px;"><a class="pagina" href="javascript:void(0)">&raquo;</a></li>';
    }
    paginador += '<li style="margin-left: 0px;margin-right: 0px;"><span class="label label pagina">' + Math.ceil(data.rta / parseInt(nroreg)) + ' Páginas</span></li>';
    paginador += '</ul>';
    //Fin del paginador
    let tablaUsuarios = document.querySelector('#tablaVotar');
    $('#paginacion').append(paginador);
    tablaUsuarios.append();
  }).catch(function (error) {
    console.error("Error al obtener productos:", error);
  });
}

function traervotacion(id) {
  // Obtener los valores de los campos de entrada
  $('#largeModal').modal('show');
  traerPerfiles(id);
  let ladata = new FormData();
  ladata.append('id', id);
  ladata.append('ind', 3);
  // Realizar solicitud fetch al backend
  fetch('backend/votarBackend.php', {
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
      // Asegúrate de acceder al primer elemento del array
      const votacion = data.rta[0]; // Accede al primer elemento
      document.getElementById('idVotacion').value = id;
      document.getElementById('titulo').innerHTML = votacion.titulo;
      document.getElementById('descripcion').innerHTML = votacion.descripcion;
    })
    .catch(error => {
      console.error('Error:', error);
      // Mostrar SweetAlert de error en caso de fallo en la solicitud
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Hubo un problema al guardar la votación. Por favor, inténtelo de nuevo más tarde.',
      });
    });
}

//Traer los perfiles del sistema 
function traerPerfiles(id) {
  let ladata = new FormData();
  ladata.append("ind", "4");
  ladata.append("id", id);

  fetch('backend/votarBackend.php', {
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
        htmlTags += "<option value=" + item.id_opcion + ">" + item.descripcion + "</option>";
      });
      $("#sel_perfil").append(htmlTags);
    })
    .catch(function (error) { });
}
function GuardarVoto() {
  var id_opcion = document.getElementById('sel_perfil').value;
  var idVotacion = document.getElementById('idVotacion').value;
  let ladata = new FormData();
  ladata.append('id_opcion', parseInt(id_opcion));
  ladata.append('id_votacion', parseInt(idVotacion));
  ladata.append('ind', "5");
  // Realizar solicitud fetch al backend
  fetch('backend/votarBackend.php', {
    method: 'POST',
    body: ladata,
  })
    .then(response => {
      if (response.ok) {
        return response.json();
      } else {
        throw new Error('Error al guardar el voto');
      }
    })
    .then(data => {
      if (data.rtacount === "nopermitido") {
        // Ejecutar la función lisrarUsuarios() con los parámetros necesarios
        listarElecciones(1, 1, 5);
        // Cerrar la modal y sus elementos
        $('#largeModal').modal('hide');
        $('.modal-backdrop').remove();
        Swal.fire({
          icon: 'warning',
          title: '¡Ya has votado!',
          text: 'No puede votar 2 veces',
        });
      } else {
        if (data.rta === 'ok') {
          // Ejecutar la función lisrarUsuarios() con los parámetros necesarios
          listarElecciones(1, 1, 5);
          // Cerrar la modal y sus elementos
          $('#largeModal').modal('hide');
          $('.modal-backdrop').remove();
          Swal.fire({
            icon: 'success',
            title: '¡Voto guardado exitosamente!',
            text: 'El usuario ha sido guardado correctamente.',
          });
        } else {
          // Mostrar SweetAlert de error
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Hubo un problema al guardar el voto. Por favor, inténtelo de nuevo más tarde.Teta',
          });
        }
      }
    })
    .catch(error => {
      console.log('Errorreta:', error);
      // Mostrar SweetAlert de error en caso de fallo en la solicitud
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Hubo un problema al guardar el VOTAR. Por favor, inténtelo de nuevo más tarde.Teta',
      });
    });
}
function Estado2(id) {
  Swal.fire({
    title: '¡Ya se ha desactivado la votación¡',
    text: "¡No puedes dar tu voto en esta votación!",
    icon: 'error',
    confirmButtonColor: '#3085d6',
  })
}