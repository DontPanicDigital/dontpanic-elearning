import TweenMax from 'gsap/src/minified/TweenMax.min';
import TimelineMax from 'gsap/src/uncompressed/TimelineMax';

/**
 * @name Questions
 * @type {Object}
 */
let Questions = {
    countBoxes: 0,
    currentBox: 0,

    cache: {
        box: null,
        continue: null,
        sortable: null
    },

    init() {
        this.prepare_vars();
        this.prepare_listeners();
        this.sortable();
    },

    sortable() {
        this.cache.sortable.sortable({
            axis: "y",
            update: function (event, ui) {
                let data = $(this).sortable('toArray');
                data = JSON.stringify(data);
                console.log(data);
            }
        });
        this.cache.sortable.disableSelection();
    },

    prepare_vars() {
        this.cache.box = $('.box');
        this.cache.sortable = $('.sortable');
        this.cache.box.eq(0).show().css({right: 0, left: 0, opacity: 1});
        this.countBoxes = this.cache.box.length;
        this.cache.continue = $('.button--continue');
    },

    prepare_listeners() {
        let _self = this;
        this.cache.continue.on('click', () => {
            console.log(Nette);
//            _self.changed_boxes();
        });
    },

    changed_boxes() {
        let tl1 = new TimelineMax({repeat:0});
        let tl2 = new TimelineMax({repeat:0});
        let cb = this.cache.box.eq(this.currentBox);
        let nb = this.cache.box.eq(this.currentBox+1);

        tl1.to(cb, 0.5, {opacity: 0, x: -400, display:'none', ease:Linear.easeNone});
        tl2.to(nb, 0.5, {opacity: 1, right: 0, display:'block', ease:Linear.easeNone});

        this.currentBox++;
    }
};

module.exports = Questions;
