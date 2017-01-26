import TweenMax from 'gsap/src/minified/TweenMax.min';
import IntlMessageFormat from 'intl-messageformat';
import lang from '../intl/messages/cs';

/**
 * @name Questions
 * @type {Object}
 */
let Questions = {
    countQuestions: 0,
    currentQuestion: 0,
    prevQuestion: null,
    isAnimating: false,

    cache: {
        question: null,
        continue: null,
        sortable: null,
        overlay: null,
        overlayText: null,
        overlayClose: null,
        questionCurrent: null,
        questionAll: null
    },

    init() {
        this.prepare_vars();
        this.set_vars();
        this.prepare_listeners();
        this.sortable();
        this.set_count_question();
    },

    sortable() {
        this.cache.sortable.sortable({
            axis: "y",
            update: function (event, ui) {
                $(this).find('.question__item').each(function() {
                    let index = $(this).index() + 1;
                    $(this).find('input').val(index);
                });
            }
        });
        this.cache.sortable.disableSelection();
    },

    prepare_vars() {
        this.cache.question = $('.question');
        this.cache.sortable = $('.question__sortable');
        this.countQuestions = this.cache.question.length;
        this.cache.continue = $('.button--continue');
        this.cache.overlay = $('.overlay');
        this.cache.overlayText = $('.overlay__text');
        this.cache.overlayClose = this.cache.overlay.find('.button--close');
        this.cache.questionCurrent = $('.question__count--current');
        this.cache.questionAll = $('.question__count--all');
    },

    set_count_question() {
        this.cache.questionAll.html(this.countQuestions);
        this.cache.questionCurrent.html(this.currentQuestion + 1);
        TweenLite.to($('.question__count'), .5, {css:{scale:1, opacity:1 }});
    },

    set_vars() {
        TweenLite.set(this.cache.question, {
            opactiy: '1',
            left: "-100%"
        });
        this.go_to_question(0);
    },

    prepare_listeners() {
        let _self = this;
        this.cache.continue.on('click', () => {
            _self.validate();
        });

        $('input.radio, input.checkbox').click(function() {
            let answer = $(this).data('correct');
            if($(this).prop("checked") && answer == 0) {
                _self.show_overlay($(this).data('overlay'));
                $(this).prop('checked', false);
            }
            if($(this).prop("checked") && answer == 1) {
                $(this).parent().addClass('question__item--correct');
            }
            else {
                $('input.radio, input.checkbox').each(function() {
                    if(!$(this).prop("checked")) {
                        $(this).parent().removeClass('question__item--correct');
                    }
                });
            }
        });

        this.cache.overlayClose.on('click', function(e) {
            e.preventDefault();
            _self.close_overlay();
        });

        $(document).click(function(e) {
            if (!$(e.target).closest('.overlay__content, input.radio, input.checkbox').length){
                _self.close_overlay();
            }
        });
    },

    show_overlay(content) {
        this.cache.overlayText.html(content);
        this.cache.overlay.fadeIn();
    },

    close_overlay() {
        this.cache.overlay.fadeOut();
    },

    go_to_next() {
        let questionToGo = this.currentQuestion + 1;

        if (questionToGo >= this.countQuestions) {
            $('.form').submit();
        }
        else {
            this.go_to_question(questionToGo);
        }
    },

    go_to_question(questionID) {
        if (!this.isAnimating) {
            this.isAnimating = true;
            this.prevQuestion = this.currentQuestion;
            this.currentQuestion = questionID;
            let $prevQuestion = this.cache.question.eq(this.prevQuestion);
            let $currentQuestion = this.cache.question.eq(this.currentQuestion);

            TweenLite.to($prevQuestion, 1, {
                left: "-100%",
                opacity: "0"
            });
            TweenLite.fromTo($currentQuestion, 1, {
                left: "100%",
                opacity: '1',
                display: 'table'
            }, {
                left: "0",
                opacity: "1"
            });

            TweenLite.delayedCall(1, function() {
                Questions.isAnimating = false;
            });
        }
    },

    validate() {
        let form = $("#frm-displayForm-form");
        form.validate({
            errorElement: "div",
            errorClass: "form__error",
            errorPlacement: function(error, elem) {
                if (elem.is(':radio') || elem.is(':checkbox')) {
                    error.insertAfter(elem.closest('.question__container'));
                }
                else {
                    error.appendTo(elem.closest('.question__item'));
                }
            }
        });


        $.validator.addMethod('radio', function(value, elem) {
            return $(elem).parent().parent().find('.radio:checked').length > 0;
        }, new IntlMessageFormat(lang.choose_right_answer, 'cs').format());

        $.validator.addMethod("checkbox", function(value, elem) {
            let correct = 0;
            let checkboxes = $(elem).parent().parent().find('.checkbox');
            let checkedCount =  checkboxes.filter(':checked').length;

            checkboxes.each(function() {
                if($(this).data('correct') == 1) {
                    correct++;
                }
            });

            return checkedCount == correct ? true : false;
        }, new IntlMessageFormat(lang.check_all_answers, 'cs').format());

        $.validator.addMethod('sort', function(value, elem) {
            let sortItem = $(elem).parent();
            let correct = false;

            sortItem.each(function() {
                let index = $(this).index() + 1;
                let attr = $(this).data('sort');
                correct = index == attr ? true : false;
            });

            return correct;
        }, new IntlMessageFormat(lang.order_right_answers, 'cs').format());

        if (form.valid() === true) {
            this.go_to_next();
            this.set_count_question();
        }
    }
};

module.exports = Questions;
