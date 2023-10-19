class PopupManager {
    constructor() {
        this.initPopup();
        this.initPageRedirect();
        this.initOverlay();
        this.initClose();
        this.initTicketSelector('.tickets__item');
        this.emailPopupManager = new EmailPopupManager();
    }
    initPageRedirect() {
        $('#btn-pay').on('click', (e) => {
            e.preventDefault();
            const formElement = document.getElementById("ticketsForm");
            const agreeCheckbox = $('#agree1')[0];
            const valid = formElement.reportValidity();

            if (!valid) {
                agreeCheckbox.focus();
                return;
            }
            const selectedTicketId = $('.tickets__item.active .tickets__count').data('ticket-id');
            window.location.href = `/buy${selectedTicketId}`;
        });
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

    showMistakePopup() {
        this.closePopupAndOverlay();
        $('#mistake').fadeIn(1500);
        $('.js-overlay').fadeIn(1500).css('opacity', '0.7');
    }

    showSuccessPopup() {
        this.closePopupAndOverlay();
        $('#success').fadeIn(1500);
        $('.js-overlay').fadeIn(1500).css('opacity', '0.7');

        const email = $('#email').val();
        const selectedTicketCount = $('.tickets__item.active .tickets__count').data('ticket');

        $.ajax({
            type: "POST",
            url: "/create-payment",
            data: { email: email, status: "начата оплата", ticketCount: selectedTicketCount },
            success: function (response) {
                // Обработка успешного ответа от сервера
                console.log(response);
            },
            error: function (error) {
                // Обработка ошибки при запросе
                console.error(error);
            }
        });
    }
}
class UserAgreementModal {
    constructor() {
        // Привязываем обработчик открытия модального окна к ссылке
        const openUserAgreementLink = document.getElementById('openUserAgreement');
        openUserAgreementLink.addEventListener('click', () => this.openModal());

        const userAgreementModal = document.getElementById('userAgreementModal');
        userAgreementModal.addEventListener('click', () => this.closeModal());

        // Привязываем обработчик закрытия модального окна к кнопке внутри модального окна
        const closeModalButton = document.getElementById('closeUserAgreementModal');
        closeModalButton.addEventListener('click', () => this.closeModal());
    }

    openModal() {
        const userAgreementModal = document.getElementById('userAgreementModal');
        userAgreementModal.style.display = 'flex';
    }

    closeModal() {
        const userAgreementModal = document.getElementById('userAgreementModal');
        userAgreementModal.style.display = 'none';
    }
}
    class InfoProductModal {
    constructor() {
        this.openInfoProductLink = document.getElementById('btn-info');
        this.infoProductModalElement = document.getElementById('infoProductModal');
        this.closeInfoProductButton = document.getElementById('closeInfoProductModal');

        this.checkElementExistence(this.openInfoProductLink, 'openInfoProductLink');
        this.checkElementExistence(this.infoProductModalElement, 'infoProductModalElement');
        this.checkElementExistence(this.closeInfoProductButton, 'closeInfoProductButton');

        if (this.openInfoProductLink) {
            this.openInfoProductLink.addEventListener('click', () => this.openModal());
        }
        if (this.infoProductModalElement) {
            this.infoProductModalElement.addEventListener('click', () => this.closeModal());
        }
        if (this.closeInfoProductButton) {
            this.closeInfoProductButton.addEventListener('click', () => this.closeModal());
        }
    }

    checkElementExistence(element, elementName) {
        if (!element) {
            console.warn(`Element ${elementName} not found on the page.`);
        }
    }

    openModal() {
        if (this.infoProductModalElement) {
            this.infoProductModalElement.style.display = 'flex';
        }
    }

    closeModal() {
        if (this.infoProductModalElement) {
            this.infoProductModalElement.style.display = 'none';
        }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const popupManager = new PopupManager();
    const userAgreementModal = new UserAgreementModal();
    const infoProductModal = new InfoProductModal();
});
