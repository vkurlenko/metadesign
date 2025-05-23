$(document).ready(function () {
    const form = $('#form-call'),
        inputPhone = $('#phone')
    ;
    applyInputMask (inputPhone);

    form.on('submit', function (event) {
        event.preventDefault();
        if (isFormValid()) {
            sendForm();
        }
    })

    function isFormValid () {
        let isValid = true;

        // Валидация номера телефона.
        let phoneNumber = inputPhone.val().replace(/[^0-9]+/g, '');

        if (phoneNumber.length < 10) {
            inputPhone.addClass('is-invalid');

            isValid = false;
        } else {
            inputPhone.removeClass('is-invalid');
        }

        return isValid;
    }
    function applyInputMask (element) {
        element.inputmask("(999) 999-99-99");
    }
    function sendForm () {

        let formData = new FormData(form[0]);

        $.ajax({
            url: '/api/feedback',
            method: 'post',
            processData: false,
            contentType: false,
            dataType: 'json',
            data: formData,
            success: function(data) {
                formReset();
            },
            error: function (jqXHR, exception) {
                let message = '';

                if (jqXHR.status === 0) {
                    message = ('Not connect. Verify Network.');
                } else if (jqXHR.status === 404) {
                    message = ('Requested page not found (404).');
                } else if (jqXHR.status === 500) {
                    message = ('Internal Server Error (500).');
                } else if (exception === 'parsererror') {
                    message = ('Requested JSON parse failed.');
                } else if (exception === 'timeout') {
                    message = ('Time out error.');
                } else if (exception === 'abort') {
                    message = ('Ajax request aborted.');
                } else {
                    message = ('Uncaught Error. ' + jqXHR.responseText);
                }
            }
        });
    }

    /**
     * Сброс формы
     */
    function formReset() {
        form[0].reset();
    }
})