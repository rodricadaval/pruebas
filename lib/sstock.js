function cargando() {
    $.blockUI({
        message: '<h2>Cargando...</h2>',
        css: {
            border: 'none',
            padding: '15px',
            backgroundColor: '#000',
            '-webkit-border-radius': '50px',
            '-moz-border-radius': '50px',
            opacity: .5,
            color: '#fff'
        }
    });
}

function quitar_cargando() {
    $.unblockUI();
}
