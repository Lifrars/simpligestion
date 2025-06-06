$(document).ready(function () {
  let nuevoinicio = 1;
  let nroreg = 5;
  listarVotaciones(1, nuevoinicio, nroreg);
  // Agregar evento de clic al botón

  $('#addvotacion').click(function () {

    // Define la URL a la que quieres redirigir al usuario

    var url = 'https://ppi.miclickderecho.com/plantilla/insertar.php';



    // Redirige al usuario a la URL especificada

    window.location.href = url;

  });

});
document.getElementById('buscador').addEventListener('input', function () {
    const filtro = this.value.toLowerCase();
    const filas = document.querySelectorAll('#tablaVotaciones tbody tr');

    filas.forEach(fila => {
        const textoFila = fila.textContent.toLowerCase();
        fila.style.display = textoFila.includes(filtro) ? '' : 'none';
    });
});

function listarVotaciones(ind, inicio, nroreg) {
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
    $('#tablaVotaciones tbody').empty();//Que quiere
    $("#paginacion").empty();
    let htmlTags = "";
    
    let i = 0;
    data.rta2.forEach(function (item) {
      i = i + 1;
      htmlTags += '<tr>';
      htmlTags += '     <td>' + item.titulo + '</td>';//Campos de la consulta
      htmlTags += '     <td>' + item.descripcion2 + '</td>';
      htmlTags += '     <td>' + (item.estado === "1" ? 'Activo' : 'Inactivo') + '</td>';
      htmlTags += '     <td>' + (item.estado === "1" ? "Sin Ganador" : item.descripcion) + '</td>';
      htmlTags += '     <td>' + item.fecha_cierre_votacion + '</td>';
      htmlTags += '     <td style="white-space: nowrap; text-align: center; padding: 5px;">';
      htmlTags += '         <a href="grafico.php?dato=' + item.id + '"class="me-3" id="detail" onclick="detailvotacion(' + item.id + ');" name="detail" data-validar="SI" '
      htmlTags += '         data-tipo="TABLA_MODAL" data-apodo="Detalles Votacion" style="cursor: pointer;" title="Detalles de votación"><img src="//ppi.miclickderecho.com/plantilla/assets/img/icons/eye.svg" alt="img"></a>';
/*
      htmlTags += '         <a class="me-3" id="editProducto" onclick="leerdatos(' + item.id + ');" name="editProducto"';   
      //htmlTags += '         data-validar="SI" data-tipo="TABLA_MODAL" data-apodo="Editar Producto" style="cursor: pointer;"title="Editar votación"><img src="//ppi.miclickderecho.com/plantilla/assets/img/icons/edit.svg" alt="img"></a>'; 
      if (item.estado === "1") {
        htmlTags += '         <a class="me-3" id="Estado1" onclick="Estado1(' + item.id + ');" name="Estado1"';
        htmlTags += '         data-validar="SI" data-tipo="TABLA_MODAL" data-apodo="Eliminar Producto" style="cursor: pointer;" title="Desactivar Votación"><img src="//ppi.miclickderecho.com/plantilla/assets/img/icons/closes.svg" alt="img"></a>';

      } else {

        htmlTags += '         <a class="me-3" id="Estado2" onclick="Estado2(' + item.id + ');" name="Estado2"';

        htmlTags += '         data-validar="SI" data-tipo="TABLA_MODAL" data-apodo="Eliminar Producto" style="cursor: pointer;" title="Votación Desactivada" ><img src="//ppi.miclickderecho.com/plantilla/assets/img/icons/expense1.svg" alt="img"></a>';

      }
*/
      htmlTags += '     </td>';

      // htmlTags += '     <td><img src="esl1.png" alt="ESL"  width="100px"></td>';
      
      htmlTags += ' </tr>';

    });

    $('#tablaVotaciones tbody').append(htmlTags);
    //Paginador
    let paginador = "";
    paginador += '<ul class="pagination justify-content-end">';
    paginador += '<li style="margin-left: 0px;margin-right: 0px;"><span class="label label pagina">' + data.rta + ' Registros</span></li>';
    if (inicio > 1) {
      paginador += '<li class="notDisable" style="margin-left: 0px;margin-right: 0px;"><a class="pagina" href="javascript:void(0)" onclick="listarVotaciones(\'1\', 1, ' + nroreg + ', \'\', \'\', \'\', \'\', \'\', \'\')">&laquo;</a></li>';
      paginador += '<li class="notDisable" style="margin-left: 0px;margin-right: 0px;"><a class="pagina" href="javascript:void(0)" onclick="listarVotaciones(\'1\', ' + (inicio - 1) + ', ' + nroreg + ', \'\', \'\', \'\', \'\', \'\', \'\')">&lsaquo;</a></li>';
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
        paginador += '<li style="margin-left: 0px;margin-right: 0px;"><a class="pagina" href="javascript:void(0)" onclick="listarVotaciones(\'1\', ' + i + ', ' + nroreg + ', \'\', \'\', \'\', \'\', \'\', \'\')">' + i + '</a></li>';
      }
    }
    if (inicio < Math.ceil(data.rta / parseInt(nroreg))) {
      paginador += '<li class="notDisable" style="margin-left: 0px;margin-right: 0px;"><a class="pagina" href="javascript:void(0)" onclick="listarVotaciones(1, ' + (inicio + 1) + ', ' + nroreg + ', \'\', \'\', \'\', \'\', \'\', \'\')">&rsaquo;</a></li>';
      paginador += '<li class="notDisable" style="margin-left: 0px;margin-right: 0px;"><a class="pagina" href="javascript:void(0)" onclick="listarVotaciones(1, ' + Math.ceil(data.rta / nroreg) + ', ' + nroreg + ', \'\', \'\', \'\', \'\', \'\', \'\')">&raquo;</a></li>';
    } else {
      paginador += '<li class="disabled" style="margin-left: 0px;margin-right: 0px;"><a class="pagina" href="javascript:void(0)">&rsaquo;</a></li>';
      paginador += '<li class="disabled" style="margin-left: 0px;margin-right: 0px;"><a class="pagina" href="javascript:void(0)">&raquo;</a></li>';
    }
    paginador += '<li style="margin-left: 0px;margin-right: 0px;"><span class="label label pagina">' + Math.ceil(data.rta / parseInt(nroreg)) + ' Páginas</span></li>';
    paginador += '</ul>';
    $('#paginacion').append(paginador);
    //Fin del paginador
    //let tablaVotaciones = document.querySelector('#tablaVotaciones');
    //tablaVotaciones.append();

  }).catch(function (error) {

    console.error("Error al obtener productos:", error);

  });

};



function limpiarCampos() {

  document.getElementById('titulo').value = '';

  document.getElementById('descripcion').value = '';

}



// function leerdatos(id) {

//   limpiarCampos();



//   let ladata = new FormData();

//   ladata.append('id', id);

//   ladata.append('ind', 0);



//   fetch('backend/votacionesBackend.php', {

//     method: 'POST',

//     body: ladata,

//   })

//     .then(response => {

//       if (response.ok) {

//         return response.json();

//       } else {

//         throw new Error('Error al obtener los datos de la votacion');

//       }

//     })

//     .then(data => {

//       $('#largeModal').modal('show');

//       const votacion = data.rta[0]; // Accede al primer elemento

//       document.getElementById('titulo').value = votacion.titulo;

//       document.getElementById('descripcion').value = votacion.descripcion ;

//       document.getElementById('idVotacion').value = votacion.id; // Esta línea asume que has añadido un input oculto para el ID

//       document.getElementById("actualizarVotacion").style.display = "block";

//     })

//     .catch(error => {

//       console.error('Error:', error);

//       Swal.fire({

//         icon: 'error',

//         title: 'Error',

//         text: 'Hubo un problema al obtener los datos del usuario. Por favor, inténtelo de nuevo más tarde.',

//       });

//     });

// }

function leerdatos(id) {

  limpiarCampos();



  let ladata = new FormData();

  ladata.append('id', id);

  ladata.append('ind', 61);



  fetch('backend/votacionesBackend.php', {

    method: 'POST',

    body: ladata,

  })

    .then(response => {

      if (response.ok) {

        return response.json();

      } else {

        throw new Error('Error al obtener los datos de la votacion');

      }

    })

    .then(data => {

      $('#largeModal').modal('show');

      const votacion = data.rta[0];

      document.getElementById('titulo').value = votacion.titulo;

      document.getElementById('descripcion').value = votacion.descripcion;

      document.getElementById('idVotacion').value = votacion.id;

      document.getElementById("actualizarVotacion").style.display = "block";

      crearTablaOpciones(data);

    })

    .catch(error => {

      console.error('Error:', error);

      Swal.fire({

        icon: 'error',

        title: 'Error al obtener datos',

        text: 'Hubo un problema al obtener los datos de la votación. Por favor, inténtelo de nuevo más tarde.',

      });

    });

}



function crearTablaOpciones(data) {

  $('#tablaOpciones tbody').empty();

  $("#paginacion").empty();

  let htmlTags = "";

  if (data.rta2) {

    data.rta2.forEach(item => {

      htmlTags += `<tr>

        <td contenteditable="true" name="Opcion${item.id}" id="${item.id}">${item.descripcion}</td>

      </tr>`;

    });

  }

  $('#tablaOpciones tbody').append(htmlTags);

}



function agregarOpcion() {

  // Obtener el texto de la nueva opción

  var nuevaOpcion = prompt("Ingrese la descripción de la nueva opción:");



  // Crear una fila de tabla con el contenido de la nueva opción

  var newRow = document.createElement('tr');

  newRow.innerHTML = `

      <td contenteditable="true" name="Opcion-${Date.now()}" id="-1">${nuevaOpcion}</td>

  `;
  // Agregar la fila a la tabla
  var tableBody = document.querySelector('#tablaOpciones tbody');
  tableBody.appendChild(newRow);

}



function Update_Votacion() {
  // Obtener los valores de los campos de entrada
  var titulo = document.getElementById('titulo').value;
  var descripcion = document.getElementById('descripcion').value;
  var id = document.getElementById('idVotacion').value;

  // Obtener los valores y los IDs de todas las opciones
  var opciones = [];
  var opcionElements = document.querySelectorAll('[name^="Opcion"]');
  opcionElements.forEach(function (element) {
    var idOpcion = element.getAttribute('id'); // Obtener el ID de la opción
    var valorOpcion = element.textContent; // Obtener el contenido de la celda
    opciones.push(idOpcion + '|' + valorOpcion); // Concatenar ID y valor separados por '|'
  });


  // Agregar los valores de los campos y opciones a ladata
  let ladata = new FormData();
  ladata.append('titulo', titulo);
  ladata.append('descripcion', descripcion);
  ladata.append('id', id);
  ladata.append('ind', 4);
  ladata.append("opciones", opciones)

  // Realizar solicitud fetch al backend
  fetch('backend/votacionesBackend.php', {
    method: 'POST',
    body: ladata,
  })
    .then(response => {
      if (response.ok) {
        return response.json();
      } else {
        throw new Error('Error al actualizar la votación');
      }
    })
    .then(data => {
      // Manejar la respuesta del backend
      if (data.rta === 'ok') {
        // Limpiar los campos del formulario
        limpiarCampos();
        // Ejecutar la función listarVotaciones con los parámetros necesarios
        listarVotaciones(1, 1, 5);
        // Cerrar la modal y sus elementos
        $('#largeModal').modal('hide');
        $('.modal-backdrop').remove();
        Swal.fire({
          icon: 'success',
          title: '!Votación actualizado exitosamente!',
          text: 'La votación ha sido actualizada correctamente.',
        });
      } else {
        // Mostrar SweetAlert de error
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Hubo un problema al actualizar la votación. Por favor, inténtelo de nuevo más tarde.',
        });
      }
    })
    .catch(error => {
      console.error('Error:', error);
      // Mostrar SweetAlert de error en caso de fallo en la solicitud
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Hubo un problema al actualizar la votación. Por favor, inténtelo de nuevo más tarde.',
      });
    });
}

function Estado1(id) {
  Swal.fire({
    title: '¿Estás seguro de que quieres parar la votación?',
    text: "¡No podrás revertir esto!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
  }).then((result) => {
    if (result.isConfirmed) {
      let ladata = new FormData();
      ladata.append('id', id);
      ladata.append('ind', 5);

      fetch('backend/votacionesBackend.php', {
        method: 'POST',
        body: ladata,
      })
        .then(response => {
          if (response.ok) {
            return response.json();
          } else {
            throw new Error('Error al cambiar el estado');
          }
        })
        .then(data => {
          if (data.rta === 'ok') {
            listarVotaciones(1, 1, 5);
            Swal.fire(
              '¡Cambiado!',
              'La votación se ha desactivado.',
              'success'
            );
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: 'Hubo un problema al Cambiar el estado. Por favor, inténtelo de nuevo más tarde.',
            });
          }
        })
        .catch(error => {
          console.error('Error:', error);
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Hubo un problema al Cambiar el estado. Por favor, inténtelo de nuevo más tarde.',
          });
        });
    }
  });
}
function Estado2(id) {
  Swal.fire({
    title: '¡Ya has desactivado la votación¡',
    text: "¡No puedes reactivar la votación!",
    icon: 'error',
    confirmButtonColor: '#3085d6',
  })
}