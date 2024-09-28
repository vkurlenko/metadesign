$(document).ready(function () {
    const form = $('#form-calculator');
    const inputPhone = $('#phone');
    const modal = $('#myModal');
    const modalBody = modal.find('.modal-body p');

    applyInputMask (inputPhone);

    // Действие по кнопке submit.
    form.on('submit', function (event) {
        event.preventDefault();

        if (isFormValid()) {
            sendForm();
        }
    })

    /**
     * Валидация формы
     *
     * @returns {boolean}
     */
    function isFormValid () {
        let isValid = true;

        // Валидация полей на "пусто".
        $.each($('select, input'), function (index, element) {
            let inputField = $(element);
            let value = inputField.val();

            if (
                ! value && inputField.is(':required') && inputField.attr('type') !== 'checkbox'
                || inputField.attr('type') === 'checkbox' && ! inputField.is(':checked')
            ) {
                inputField.addClass('is-invalid');

                isValid = false;
            } else {
                inputField.removeClass('is-invalid');
            }
        })

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

    /**
     * К полю ввода номера телефона применим маску
     *
     * @param element
     */
    function applyInputMask (element) {
        element.inputmask("(999) 999-99-99");
    }

    /**
     * Отправка формы
     */
    function sendForm () {
        let formData = new FormData(form[0]);

        $.ajax({
            url: '/api/calculate',
            method: 'post',
            processData: false,
            contentType: false,
            dataType: 'json',
            data: formData,
            success: function(data){
                modalBody.html(data.message);
                modal.modal('show');

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

                modalBody.html(message);
                modal.modal('show');
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