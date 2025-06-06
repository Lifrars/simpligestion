$(document).ready(function() {

  const input = document.getElementById('inp-Anexo');

    // Agregamos un listener para el evento 'input'
    input.addEventListener('input', function() {
      // Validamos el valor del campo con una expresión regular
      if (!/^[0-9]*$/.test(this.value)) {
        // Si el valor no es válido, lo vaciamos
        this.value = '';
      }
    
    });

    // Añadimos un listener para el evento 'focus' (cuando el campo de entrada obtiene el foco)
    input.addEventListener('focus', function() {
      // Si el valor es 0, limpiamos el campo para permitir la entrada del usuario
      if (this.value === '0') {
        this.value = '';
      }
    });

    // Añadimos un listener para el evento 'blur' (cuando el campo de entrada pierde el foco)
    input.addEventListener('blur', function() {
      // Si el campo está vacío, restablecemos su valor a 0
      if (this.value === '') {
        this.value = '0';
      }
    });

    // ----------------------------------------------------------
    // DRAG AND DROP
    const droppableArea = document.querySelector('.drag-drop');
    const list = document.querySelector('.file-list');

    // Manejar evento de dragover
    droppableArea.addEventListener('dragover', function(e) {
      e.preventDefault();
      droppableArea.classList.add('drag-over');
    });

    // Manejar evento de dragleave
    droppableArea.addEventListener('dragleave', function(e) {
      droppableArea.classList.remove('drag-over');
    });

    // Manejar evento de drop
    droppableArea.addEventListener('drop', function(e) {
      e.preventDefault();
      droppableArea.classList.remove('drag-over');

      const files = e.dataTransfer.files;
      console.log(e.dataTransfer.files)
      handleFiles(files);
    });

    // Función para manejar los archivos
    function handleFiles(files) {
      for (const file of files) {
        const listItem = document.createElement('li');
        listItem.textContent = file.name;
        listItem.addEventListener('click', function() {
          openFile(file);
          
        });
        list.appendChild(listItem);
      }
    }

    // Función para abrir el archivo
    function openFile(file) {
      const reader = new FileReader();
      reader.onload = function(e) {
        // Aquí puedes hacer lo que quieras con el contenido del archivo
        alert(e.target.result);
      };
      reader.readAsText(file);
    }

});

function toggleRadio(id) {
    var radio = document.getElementById(id);
    if (radio.checked) {
      radio.checked = false;
    } else {
      radio.checked = true;
    }
  }

