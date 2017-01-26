import FlashMsg from './components/flashmsg';

window.onload = () => {
    $('input[type=radio].question-type').on('change', function () {
        if (!this.checked) return
        $('.collapse').not($('div.' + $(this).attr('data-show'))).slideUp();
        $('.collapse.' + $(this).attr('data-show')).slideDown();
    });
}