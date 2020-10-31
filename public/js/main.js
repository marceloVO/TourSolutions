//funcion para mandar una alerta al momento de eliminar una foto de la galeria
function confirmarEliminacion(id){
    Swal.fire({
    title: '¿Estas seguro de que quieres eliminar?',
    text: "No puedes deshacer esta accion",
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
//funcion para mandar una sweet alert para verificar si el administrador desea banear al usuario o no
function confirmarBan(id){
    Swal.fire({
    title: '¿Estas seguro de que quieres Banear a este usuario?',
    text: "Para que el usuario ocupe nuevamente el sistema tienes que desbanearlo",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    onfirmButtonText: 'Si, Eliminar!',
    cancelButtonText:'Cancelar'
    }).then((result) => {
    if (result.value) {
        //Aqui se redirige al eliminar
        window.location.href = "/banUser/"+id+"/" ;
    }
    })
}