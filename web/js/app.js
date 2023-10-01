class PopupManager {
    constructor() {
        this.initPopup();
        this.initOverlay();
        this.initClose();
        this.initTicketSelector('.tickets__item');
        this.emailPopupManager = new EmailPopupManager();
    }

    initPopup() {
        $('.js-btn').on('click', (e) => {
            e.preventDefault();
            const formElement = document.getElementById("ticketsForm");
            const agreeCheckbox = $('#agree1')[0];
            const valid = formElement.reportValidity();

            if (!valid) {
                agreeCheckbox.focus();
                return;
            }

            const id = $(e.currentTarget).attr('data-link');
            $(`#${id}`).fadeIn(500);
            $('.js-overlay').fadeIn(500).css('opacity', '0.7');
        });
    }

    initOverlay() {
        $('.js-overlay').on('click', (e) => {
            if (!$(e.target).closest('.popup').length) {
                $('.js-overlay').fadeOut(500);
                $('.js-popup').fadeOut(500);
            }
        });
    }

    initClose() {
        $('.js-close').on('click', () => {
            $('.js-overlay').fadeOut(500);
            $('.js-popup').fadeOut(500);
        });
    }

    initTicketSelector(selector) {
        $(selector).on('click', function() {
            $(selector).removeClass('active');
            $(this).addClass('active');
        });
    }
}

class EmailPopupManager {
    constructor() {
        this.initPopup();
        this.initClose();
    }

    initPopup() {
        $('#emailPopupBtn').on('click', (e) => {
            e.preventDefault();

            const emailFormElement = document.querySelector("#buy .myform");
            const emailInput = document.getElementById("email");

            // Применяем reportValidity для валидации email
            const isValid = emailFormElement.reportValidity();

            if (!isValid) {
                emailInput.focus();
                return;
            }

            this.showSuccessPopup();
        });
    }

    initClose() {
        $('#emailCloseBtn').on('click', () => {
            $('#buy').fadeOut(500);
            $('.js-overlay').fadeOut(500);
        });
    }

    closePopupAndOverlay() {
        $('#buy').fadeOut(500);
        $('.js-overlay').fadeOut(500);
    }

    showSuccessPopup() {
        this.closePopupAndOverlay();
        $('#success').fadeIn(1500);
        $('.js-overlay').fadeIn(1500).css('opacity', '0.7');
    }

    showMistakePopup() {
        this.closePopupAndOverlay();
        $('#mistake').fadeIn(1500);
        $('.js-overlay').fadeIn(1500).css('opacity', '0.7');
    }
}


document.addEventListener('DOMContentLoaded', () => {
    const popupManager = new PopupManager();
});