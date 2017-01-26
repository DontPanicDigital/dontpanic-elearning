/**
 * @name FlashMsg
 * @type {Object}
 */
let FlashMsg = {
    close() {
        let closeFlashes = $('.flashMessage--autoclose');
        setTimeout(function(){closeFlashes.fadeOut('fast');}, 3000);
    }
};

window.FlashMsg = FlashMsg;
module.exports = FlashMsg;
