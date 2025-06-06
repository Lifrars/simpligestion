$(document).ready(function () {
  let nuevoinicio = 1; //Variable de sesion?
  let nroreg = 5; //Variable de sesion?
  creargrafico(2, nuevoinicio, nroreg, dato);
  console.log("miracle", dato)
});

function creargrafico(ind, inicio, nroreg, dato) {
  let nuevoinicio = (inicio - 1) * parseInt(nroreg);//ni idea para que es

  let ladata = new FormData();
  ladata.append('ind', ind);//indicador
  ladata.append('nuevoinicio', nuevoinicio);
  ladata.append('nroreg', nroreg);
  ladata.append("id_vota", parseInt(dato))

  fetch('backend/votarBackend.php', { //Pa donde vamos
    method: 'POST',
    body: ladata,

  }).then(function (response) {

    return response.json();
  }).then(function (data) {
    console.log(data)
    if (!data.rta2 || data.rta2.length === 0) {
      Swal.fire({
          icon: 'warning',
          title: 'Error',
          text: 'No hay suficientes datos para graficar.',
          showConfirmButton: true, // Mostrar el botón de confirmación
          allowOutsideClick: false // No permitir clics fuera del modal
      }).then((result) => {
          if (result.isConfirmed) {
              window.history.back(); // Redirigir solo si el usuario confirma el mensaje
          }
      });
      return; // Detener la ejecución del código aquí para evitar que continúe después de mostrar el mensaje
  }

    var series = [];
    var labels = [];

    for (var i = 0; i < data.rta2.length; i++) {
      series.push(parseInt(data.rta2[i].Total));
      labels.push(data.rta2[i].descripcion);
    }
    if (data.rta2.length < 2) {
      let missingDataCount = 2 - data.rta2.length;
      for (let i = 0; i < missingDataCount; i++) {
          data.rta2.push({ Total: 0, descripcion: `Opción ${i + 1}` });
      }
  }
  

    var options1 = {
      chart: {
        type: 'donut',
        width: '100%', // Ancho del gráfico
        height: 400, // Alto del gráfico
        animations: {
          enabled: true, // Habilitar animaciones
          easing: 'easeout', // Tipo de interpolación
          speed: 800 // Duración de la animación en milisegundos
        },
        dropShadow: {
          enabled: true, // Habilitar sombra
          blur: 5, // Desenfoque de la sombra
          left: 1, // Desplazamiento horizontal de la sombra
          top: 1, // Desplazamiento vertical de la sombra
          opacity: 0.2 // Opacidad de la sombra
        }
      },
      series: series,
      labels: labels
    };

    
    var barto = new ApexCharts(document.querySelector("#bar"), options1);

    barto.render();

    var options = {
      chart: {
        type: 'bar',
        height: 400 // Ajusta la altura del gráfico según sea necesario
      },
      plotOptions: {
        bar: {
          horizontal: false, // Cambia a true para barras horizontales
          columnWidth: '50%', // Ancho de las columnas de la barra
          endingShape: 'rounded' // Forma de los extremos de las barras ('rounded', 'flat')
        },
      },
      dataLabels: {
        enabled: false // Desactiva las etiquetas de datos dentro de las barras
      },
      colors: ['#008FFB'], // Color de las barras
      series: [{
        name: 'Serie 1', // Nombre de la serie (opcional)
        data: series // Datos de la serie
      }],
      xaxis: {
        categories: labels // Etiquetas del eje X
      },
      yaxis: {
        title: {
          text: 'Cantidad' // Título del eje y
        }
      }
    };
    
    var chart = new ApexCharts(document.querySelector("#chart"), options);
    chart.render();


  }).catch(function (error) {
    console.error("Error al obtener productos:", error);
  });
}
