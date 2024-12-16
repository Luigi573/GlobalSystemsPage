$(document).ready(function() {
    $('#contactForm').validate({
        rules: {
            inputName: {
                required: true,
                minlength: 15,
                maxlength: 50
            },
            inputEmail: {
                required: true,
                email: true
            },
            inputPhone: {
                required: true,
                minlength: 10,
                maxlength: 10,
                digits: true
            },
            inputService: {
                required: true
            },
            inputRequestBody: {
                required: true
            }
        },
        messages: {
            inputName: {
                required: "Este campo es obligatorio.",
                minlength: "El nombre debe tener al menos 15 caracteres.",
                maxlength: "El nombre no puede exceder 50 caracteres."
            },
            inputEmail: {
                required: "Este campo es obligatorio.",
                email: "Ingrese un correo válido."
            },
            inputPhone: {
                required: "Este campo es obligatorio.",
                minlength: "El teléfono debe tener 10 caracteres.",
                maxlength: "El teléfono debe tener 10 caracteres.",
                digits: "El teléfono solo debe contener números."
            },
            inputService: {
                required: "Debe seleccionar un servicio."
            },
            inputRequestBody: {
                required: "Por favor ingrese su solicitud."
            }
        },

        validClass: "is-valid",  // When field is valid, add the 'is-valid' class
        errorClass: "is-invalid",  // When field is invalid, add the 'is-invalid' class

        
        submitHandler: function (form) {        
            var formData = $(form).serialize();
        
            $.ajax({
                url: "../../backEnd/php/EmailService.php", 
                type: "POST",
                data: formData,
                success: function() {
                    alert("Correo enviado exitosamente");
                    form.reset(); 
                    window.location.href = "index.html";
                },
                error: function(response) {
                    console.log(response);
                    alert("Error al enviar el correo, no se pudo establecer conexión con el servidor");
                }
            });
        }
        
    });
});
