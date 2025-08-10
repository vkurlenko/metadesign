$(document).ready(function () {
    const form = $('#form-calculator'),
        inputPhone = $('#phone'),
        modalCalculator = $('#myModalCalculator'),
        modalResult = $('#modalResult'),
        modalBody = modalResult.find('.modal-body p'),
        responseLoader = $('.response-loader'),
        btnModalCalculator = $('.btnModalCalculator'),
        btnSubmit = $('#btnSubmit'),
        btnRepeat = $('#btnRepeat')
    ;

    btnModalCalculator.on('click', function () {
        modalCalculator.modal('show');
    });

    btnSubmit.on('click', function () {
        form.submit();
    })

    btnRepeat.on('click', function () {
        modalCalculator.modal('show');
    });

    applyInputMask (inputPhone);

    // Действие по кнопке submit.
    form.on('submit', function (event) {
        event.preventDefault();

        if (isFormValid()) {
            modalCalculator.modal('hide');
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
        $.each($('#form-calculator select, #form-calculator input'), function (index, element) {
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

        modalResult.modal('show');

        $.ajax({
            url: '/api/calculate',
            method: 'post',
            processData: false,
            contentType: false,
            dataType: 'json',
            data: formData,
            success: function(data) {

                // Имитируем долгую загрузку расчета.
                setTimeout(() => {
                    responseLoader.hide();

                    modalBody.html(data.message);
                }, 1000);

                formReset();
            },
            error: function (jqXHR, exception) {
                let message = '';
                responseLoader.hide();

                if (jqXHR.status === 0) {
                    message = ('Not connect. Verify Network.');
                } else if (jqXHR.status === 404) {
                    message = ('Requested page not found (404).');
                } else if (jqXHR.status === 500) {
                    message = ('Internal Server Error (500).');
                } else if (jqXHR.status === 422){
                    message = ('Validation failed.');
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