class PopupManager {
    constructor() {
        this.initPopup();
        this.initOverlay();
        this.initClose();
        this.initTicketSelector('.tickets__item');
    }

    initPopup() {
        $('.js-btn').on('click', (e) => {
            e.preventDefault();
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

document.addEventListener('DOMContentLoaded', () => {
    const popupManager = new PopupManager();
});
