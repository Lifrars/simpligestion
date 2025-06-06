const form = document.querySelector("form");
var cont = 3;
function agregarOpcion() {
  // Encuentra el div contenedor de las opciones
 
  var contenedorOpciones = document.getElementById("contenedor-opciones");

  // Crea un nuevo elemento de entrada de texto
  var nuevaOpcion = document.createElement("input");
  nuevaOpcion.type = "text";
  nuevaOpcion.name = "opcion_"+cont; // Asigna un nombre único a la nueva opción
  nuevaOpcion.required = true;
  nuevaOpcion.classList.add("input", "is-normal");
  nuevaOpcion.placeholder = "Escribe aqui...";
  // Crea un nuevo elemento de etiqueta para la nueva opción
  var nuevaEtiqueta = document.createElement("label");
  nuevaEtiqueta.innerHTML = "Opcion " + (cont) + ":"; // Incrementa el número de opción dinámicamente

  // Añade la nueva opción y etiqueta al contenedor
  contenedorOpciones.appendChild(nuevaEtiqueta);
  contenedorOpciones.appendChild(nuevaOpcion);
  contenedorOpciones.appendChild(document.createElement("br")); // Agrega un salto de línea para el formato
  cont = cont + 1
}

function enviar_vota() {

  
  var opciones = [];
  
  // Obtener todos los elementos de entrada del formulario
  var elementosInput = document.querySelectorAll('input[type="text"]');
  const titulo = document.getElementById("titulo").value;
  const descripcion = document.getElementById("descripcion").value;
  // Iterar sobre los elementos de entrada
  elementosInput.forEach(function(input) {
    // Verificar si el input es una opción
    if (input.name.startsWith("opcion_")) {
      // Guardar el valor del input en el array de opciones
      opciones.push(input.value);
      
    }
  });
  
 
  
  const data = new FormData();
  data.append("ind",3)
  data.append("titulo", titulo);
  data.append("descripcion", descripcion);
  data.append("opciones",opciones)


  // Enviar datos al servidor usando AJAX
  fetch("backend/votacionesBackend.php", {
    method: "POST",
    body: data,
  })
  .then(response => {
    if (response.ok) {
      // Si la respuesta es exitosa, retornamos el contenido de la respuesta como JSON
      return response.json();
    } else {
      // Si hay un error en la respuesta, lanzamos un error
      throw new Error('Hubo un problema al guardar la votacion.');
    }
  })
  .then(data => {
    // Aquí puedes trabajar con los datos de la respuesta
    if (data ) {
      alert(data)
      // Si la respuesta contiene 'rta' con valor 'ok', mostramos la alerta de éxito
      Swal.fire({
        icon: 'success',
        title: '¡Usuario guardado exitosamente!',
        text: 'La votacion ha sido guardado correctamente.',
      });
      window.history.back();
    } else {
      // Si la respuesta no es la esperada, mostramos una alerta de error
      throw new Error('La respuesta del servidor no fue la esperada.');
    }
  })
  .catch(error => {
    // Capturamos cualquier error que pueda ocurrir durante el proceso
    console.error('Error:', error);
    // Mostramos una alerta de error genérica en caso de fallo
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: error.message, // Mostramos el mensaje de error capturado
    });
  });

}

