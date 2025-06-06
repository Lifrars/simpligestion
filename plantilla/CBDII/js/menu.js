// Obtenemos la URL actual de la página
var url = window.location.href;

// Obtenemos el nombre del archivo de la URL
var filename = url.substring(url.lastIndexOf('/') + 1);

// Verificamos qué archivo es el actual y agregamos la clase 'active' al elemento del menú correspondiente
if (filename === 'index.php') {
    document.querySelector('a[href="index.php"]').parentNode.classList.add('active');
} else if (filename === 'radicados.php') {
    document.querySelector('a[href="radicados.php"]').parentNode.classList.add('active');
} else if (filename === 'oficios.php') {
    document.querySelector('a[href="oficios.php"]').parentNode.classList.add('active');
} else if (filename === 'archivo.php') {
    document.querySelector('a[href="archivo.php"]').parentNode.classList.add('active');
} else if (filename === 'usuarios.php') {
    document.querySelector('a[href="usuarios.php"]').parentNode.classList.add('active');
    document.querySelector('a[href="#components"]').parentNode.classList.add('active');
}  else if (filename === 'dependencias.php') {
    document.querySelector('a[href="dependencias.php"]').parentNode.classList.add('active');
    document.querySelector('a[href="#components"]').parentNode.classList.add('active');
}  else if (filename === 'perfiles.php') {
    document.querySelector('a[href="perfiles.php"]').parentNode.classList.add('active');
    document.querySelector('a[href="#components"]').parentNode.classList.add('active');
}
// Agrega más bloques "else if" si hay más archivos de vista
