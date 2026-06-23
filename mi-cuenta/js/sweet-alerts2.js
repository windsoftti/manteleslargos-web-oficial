var Toast = Swal.mixin({
    toast: true,
    showConfirmButton: false
});

var Confirm = Swal.mixin({
    toast: false,
    position: 'center',
    showConfirmButton: true,
    cancelButtonColor: '#BBBBBB'
});

const showSweetAlert = ({
    icon,
    title,
    timer = 3000,
    position = 'top-end'
}) => {
    Toast.fire({
        icon: icon,
        title: ' ' + title,
        timer,
        position: position
    });
}

const showBigAlert = ({
    icon,
    title,
    subtitle,
    buttonTitle = 'Aceptar',
    showCancelButton = false,
    confirmButtonColor = '#156DD8'
}) => {
    return new Promise((resolve, reject) => {
        Confirm.fire({
            icon: icon,
            title: title,
            html: subtitle,
            confirmButtonText: buttonTitle,
            cancelButtonText: 'Cancelar',
            allowOutsideClick: "true",
            showCancelButton: showCancelButton,
            confirmButtonColor: confirmButtonColor,
        }).then(status => {
            if (status.isConfirmed) resolve(true);
            else return resolve(false);
        });
    });
}

const showSweetConfirm = ({
    title,
    subtitle,
    icon = 'warning',
    showCancelButton = true,
    confirmButtonColor = '#d94e5f',
    buttonTitle = 'Si, continuar',
    cancelButtonText = 'No, cancelar'
}) => {
    return new Promise((resolve, reject) => {
        Confirm.fire({
            icon: icon,
            title: title,
            html: subtitle,
            confirmButtonText: buttonTitle,
            cancelButtonText: cancelButtonText,
            allowOutsideClick: "true",
            showCancelButton: showCancelButton,
            confirmButtonColor: confirmButtonColor
        }).then(status => {
            if (status.isConfirmed) resolve(true);
            else return resolve(false);
        });
    });
}