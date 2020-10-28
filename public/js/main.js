//funcion para mandar una alerta al momento de eliminar una foto de la galeria
function confirmarEliminacion(id){
    Swal.fire({
    title: '¿Estas seguro que quiere eliminar?',
    text: "No podras deshacer esta acción",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    onfirmButtonText: 'Si, Eliminar!',
    cancelButtonText:'Cancelar'
    }).then((result) => {
    if (result.value) {
        //Aqui se redirige al eliminar
        window.location.href = "/delete-galeria/"+id+"/" ;
    }
    })
}