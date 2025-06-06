let cont = 3;

function agregarOpcion() {
    var contenedorOpciones = document.getElementById("contenedor-opciones");
    
    var fieldDiv = document.createElement("div");
    fieldDiv.classList.add("field", "option-field");
    fieldDiv.setAttribute("id", "opcion_div_" + cont);
    
    var label = document.createElement("label");
    label.classList.add("label");
    label.innerText = "Opción " + cont + ": *";
    
    var controlDiv = document.createElement("div");
    controlDiv.classList.add("control", "has-icons-right", "is-flex");
    
    var input = document.createElement("input");
    input.type = "text";
    input.name = "opcion_" + cont;
    input.required = true;
    input.classList.add("input", "option-input");
    
    var deleteButton = document.createElement("span");
    deleteButton.classList.add("delete-option");
    deleteButton.innerHTML = "❌";
    deleteButton.style.marginLeft = "10px"; 
    deleteButton.onclick = function () {
        fieldDiv.remove();
        cont--;
    };
    
    controlDiv.appendChild(input);
    controlDiv.appendChild(deleteButton);
    
    fieldDiv.appendChild(label);
    fieldDiv.appendChild(controlDiv);
    
    contenedorOpciones.appendChild(fieldDiv);
    
    cont++;
}

document.getElementById("votacion-form").addEventListener("submit", function(event) {
    event.preventDefault();
    
    const titulo = document.getElementById("titulo").value.trim();
    const descripcion = document.getElementById("descripcion").value.trim();
    const fechaCierre = document.getElementById("fecha_cierre").value.trim(); // Capturar la fecha
    let opciones = [];

    document.querySelectorAll(".option-input").forEach(input => {
        opciones.push(input.value.trim());
    });

    if (!titulo || !descripcion || !fechaCierre || opciones.some(op => op === "")) {
        alert("Todos los campos son obligatorios.");
        return;
    }

    const data = new FormData();
    data.append("ind", 3);
    data.append("titulo", titulo);
    data.append("descripcion", descripcion);
    data.append("fecha_cierre", fechaCierre);
    data.append("opciones", opciones);

    fetch("backend/votacionesBackend.php", {
        method: "POST",
        body: data,
    })
    .then(response => response.json())
    .then(data => {
        console.log("Respuesta del servidor:", data);  // 🔍 Ver la respuesta en la consola
        if (data.rta === "ok") {
            alert("¡Votación creada exitosamente!");
            window.history.back();
        } else {
            alert("Hubo un error al crear la votación: " + (data.msg || "Error desconocido"));
        }
    })
    .catch(error => {
        console.error("Error en la solicitud:", error);
        alert("Error de conexión con el servidor.");
    });
    
});

