$(document).ready(function () {
    $('#contactForm').validate({
        rules: {
            inputEmail: {
                required: true,
                email: true
            }
        },
        messages: {
            inputEmail: {
                required: "Este campo es obligatorio.",
                email: "Ingrese un correo válido."
            }
        },
        errorClass: 'is-invalid',
        validClass: 'is-valid'
    });
});