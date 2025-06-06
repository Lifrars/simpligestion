$(document).ready(function () {
listarCard(1)
});
  

            // Agrega buscador
            document.getElementById('buscador').addEventListener('input', function () {
                const filtro = this.value.toLowerCase();
                const cards = document.querySelectorAll('.ag-courses_item');

                cards.forEach(card => {
                    const texto = card.textContent.toLowerCase();
                    card.style.display = texto.includes(filtro) ? '' : 'none';
                });
            });
        ;
function listarCard(ind ) {

    let ladata = new FormData();
    ladata.append('ind', ind);//indicador

    fetch('backend/calendarBackend.php', { //Pa donde vamos
        method: 'POST',
        body: ladata,

    }).then(function (response) {

        return response.json();
    }).then(function (data) {
        console.log(data.rta)
        const container = document.getElementById('card-container');

        data.rta.forEach(item => {
            const card = document.createElement('div');
            card.className = 'ag-courses_item';

            card.innerHTML = `
        <a href="calendario.php?id=${item.area_id}" class="ag-courses-item_link">
            <div class="ag-courses-item_bg"></div>
            <div class="ag-courses-item_title">${item.nombre}</div>
            <div class="ag-courses-item_date-box">
                Horario:
                <span class="ag-courses-item_date">${item.horario}</span>
            </div>
            <div class="ag-courses-item_date-box">
                Descripcon:
                <span class="ag-courses-item_date">${item.descripcion}</span>
            </div>
            <div class="ag-courses-item_date-box">
                Ubicacion:
                <span class="ag-courses-item_date">${item.ubicacion}</span>
            </div>
        </a>
    `;

            container.appendChild(card);
        })
    });

}
