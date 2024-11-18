$('#contactForm').validate({
    rules: {
        inputName:{
            required: true,
            minlength: 15,
            maxlength: 50
        },
        inputEmail:{
            required: true,
            email: true
        },
        inputPhone:{
            required: true,
            minlength: 10,
            maxlength: 10
        },
        inputService:{
            required: true
        },
        inputRequestBody:{
            required: true
        }
    },
    messages: {
        inputName: {
            required: "Este campo es obligatorio.",
            minlength: "El nombre debe tener al menos 15 caracteres.",
            maxlength: "El nombre no puede exceder 50 caracteres"
        },
        inputEmail: {
            required: "Este campo es obligatorio.",
            email: "Ingrese un correo válido."
        },
        inputPhone: {
            required: "Este campo es obligatorio.",
            minlength: "El teléfono debe tener 10 caracteres.",
            maxlength: "El teléfono debe tener 10 caracteres."
        },
        inputService: {
            required: "Debe seleccionar un servicio."
        },
        inputRequestBody: {
            required: "Por favor ingrese su solicitud."
        }
    },
    
    submitHandler: function(form){
        form.submit();
    }
});