/** Foundation Finder JS goes here */
/** Custom js goes here */
var shadeGuide = (function ($) {
    return {
        init: function (options) {
            var that = this;
            var settings = $.extend({
                wHeight: $(window).height(),
                skipStepTrigger: '.skip',
                nextStepTrigger: '.next-step',
                prevStepTrigger: '.prev-step',
                startOver: '.start-over'
            }, options );

            that.setMinHeight(that, settings.wHeight);

            $('body').on('click', '.option-select, .checkbox-select', function() {
                that.checkInput(that, settings, this);
            });

            $(settings.prevStepTrigger).on('click', function() {
                that.prevStep(that, settings, this);
            });

            $(settings.skipStepTrigger).on('click', function() {
                that.nextStep(that, settings, this);
            });
            $(settings.nextStepTrigger).on('click', function() {
                that.nextStep(that, settings, this);
            });
            $(settings.startOver).on('click', function() {
                location.reload();
            });
        },
        setMinHeight: function(that, minHeight) {
            $('.shadeguide-step').not('.image_carousel').css('min-height', parseInt(minHeight * 0.77));
        },
        prevStep: function(that, settings, el) {
            $('#lightbox-loader, .shadeguide-overlay').show();

            var elObj = $(el),
                currentStep = elObj.closest('.shadeguide-step'),
                prevStep = currentStep.prev('.shadeguide-step');
                // if(currentStep != 'start')

                // added to fix shadefinder skipping filters
                var removeStoredStep = function(){
                    var step = prevStep[0].dataset.attr
                    var className = 'hiddenOption ' + step
                    var previousElement = document.getElementsByClassName(className)[0]
                    previousElement.parentNode.removeChild(previousElement)
                }
                if(prevStep[0].dataset.attr){
                    removeStoredStep()
                }
                // END added to fix shadefinder skipping filters

            if (prevStep.length) {
                /** un-select current options */
                currentStep.find('input:checked').each(function() {
                    $(this).prop('checked', false);
                });
                currentStep.find('.option-select, .checkbox-select').each(function() {
                    $(this).removeClass('selected');
                });
                /** show and scroll to previous step */
                setTimeout(function() {
                    currentStep.css('visibility', 'hidden');
                    currentStep.css('display', 'none');
                }, 750);

                prevStep.show('fast', function() {
                    prevStep.css('visibility', 'visible');
                    prevStep.css('display', 'flex')
                    that.scrollTo(that, el, prevStep, currentStep, 1000, -30);
                });
            }
        },
        nextStep: function(that, settings, el) {
            var elObj = $(el),
                currentStep = elObj.closest('.shadeguide-step'),
                nextStep = currentStep.next('.shadeguide-step');
                
                if(nextStep[0].id != 'results'){
                    jQuery('.shadeguide-steps .step-background-image:not(.visible)').addClass('visible');
                }else{
                    jQuery('.shadeguide-steps').css('background-color', '#d1ab86');
                    jQuery('.shadeguide-steps .step-background-image.visible').removeClass('visible');
                    jQuery('.shadeguide-steps .background-image-block').css('display', 'none');
                }
                
                // added to fix shadefinder skipping filters
                var storeStep = function(){
                    var step = currentStep[0].dataset.attr
                    var assigned_class = elObj[0].classList[2]

                    if(!assigned_class || assigned_class == undefined){
                        assigned_class = 'nopref'
                    }
                    
                    var loggedSelection = document.createElement('div')
                    loggedSelection.className = 'hiddenOption ' + step + ' ' + assigned_class
                    loggedSelection.style.display = 'none';
                    document.body.appendChild(loggedSelection)
                }
                if(currentStep[0].dataset.attr){
                    storeStep()
                }
                // END added to fix shadefinder skipping filters

            if (currentStep.attr('id') == 'start' || elObj.hasClass('skip')) {
                console.log('has start')
                $('#lightbox-loader, .shadeguide-overlay').show();
                /** un-select current options */
                currentStep.find('input:checked').each(function() {
                    $(this).prop('checked', false);
                });
                currentStep.find('.option-select, .checkbox-select').each(function() {
                    $(this).removeClass('selected');
                });

                var delayTime = 500;
                if (currentStep.attr('id') != 'start') {
                    that.ajaxCollection(that, settings, el, currentStep, nextStep);
                } else {
                    delayTime = 0;
                }

                setTimeout(function() {
                    if (nextStep.length) {
                        /** show and scroll to next step */
                        nextStep.show('fast', function() {
                            nextStep.css('visibility', 'visible');
                            nextStep.css('display', 'flex')
                            that.scrollTo(that, el, nextStep, currentStep, 1000, -30);
                        });
                    }
                }, delayTime);
            } else {
                console.log('doesn\'t have start')
                var count = 0;
                var currentParams = {};
                currentStep.find('input').each(function() {
                    if ($(this).is(':checked')) {
                        currentParams[count] = $(this).val();
                        count++;
                    }
                });

                if (Object.keys(currentParams).length) {
                    $('#lightbox-loader, .shadeguide-overlay').show();

                    that.ajaxCollection(that, settings, el, currentStep, nextStep);

                    setTimeout(function() {
                        if (nextStep.length) {
                            nextStep.show('fast', function() {
                                /** show next step */
                                nextStep.css('visibility', 'visible');/** scroll to next step */
                                nextStep.css('display', 'flex');
                                that.scrollTo(that, el, nextStep, currentStep, 1000, -30);
                            });

                        }
                    }, 500);
                } else {
                    /** add error and exit */
                    currentStep.find('.message').addClass('error').text('Please select at list one option!');
                }
            }
        },
        scrollTo: function(that, el, target, current, speed, offset) {
            var count = 0;
            if (target.length) {
                if (target.find('.owl-carousel').length) {
                    $(window).trigger('resize');
                    target.css('position', 'relative')
                }

                if (!that.checkSafariBrowser() && !that.checkFirefoxBrowser()) {
                    $('html, body').stop().animate({
                        scrollTop: target.offset().top + offset
                    }, speed, function() {
                        if (count == 0 && current.length) {
                            setTimeout(function() {
                                if (!$(el).hasClass('prev-step')) {
                                    current.css('visibility', 'hidden');
                                    current.css('display', 'none');
                                }
                                current.hide();
                                current.find('.message.error').removeClass('error').text('');
                                $('#lightbox-loader, .shadeguide-overlay').hide();
                            }, 1000);
                        }
                        count++;
                    });
                } else {
                    /** for safari and firefox use fade in/out effect */
                    current.fadeOut(500, function() {
                        current.css('position', 'absolute');
                        target.fadeIn(1000, function() {
                            target.css('position', 'relative');
                            $('html, body').stop().animate({
                                scrollTop: target.offset().top + offset
                            }, speed, function() {
                                current.find('.message.error').removeClass('error').text('');
                                $('#lightbox-loader, .shadeguide-overlay').hide();
                            });
                        });
                    });
                }
            }
        },
        checkInput: function(that, settings, el) {
            var input = $(el).closest('.fieldset').find('input');
            if (!input.is('[disabled=disabled]')) {
                if ($(el).hasClass('option-select')) {
                    input.trigger('click');
                }
                if (input.attr('type') == 'radio') {
                    $(el).closest('.options-wrapper').find('.label, .option-image').removeClass('selected');
                    $(el).closest('.field-wrapper').find('.label, .option-image').addClass('selected');
                } else {
                    if ($(el).hasClass('checkbox-select')) {
                        $(el).toggleClass('selected');
                    }
                    if (input.is(':checked')) {
                        $(el).closest('.field-wrapper').find('.option-image').addClass('selected');
                    } else {
                        $(el).closest('.field-wrapper').find('.option-image').removeClass('selected');
                    }
                }

                if (input.attr('type') == 'radio') {
                    that.nextStep(that, settings, el);
                }
            } else {

            }
        },
        ajaxCollection: function(that, settings, el, currentStep, nextStep) {
            var collectionUrl = settings.collectionUrl,
                params = {};

            $('.options-wrapper').each(function() {
                var attr = $(this).data('attr'),
                    count = 0;
                params[attr] = {};
                $(this).find('input:checked').each(function() {
                    params[attr][count] = $(this).val();
                    count++;
                });
                
            });

            // added to fix shadefinder skipping filters
            var newParams = document.getElementsByClassName('hiddenOption');
            $(newParams).each(function(){
                var stepName = this.classList[1]
                if(stepName != 'form' && stepName != 'skin_type'){
                    var l = 'option-'.length
                    var optionNumber = this.classList[2].substr(l)
                    params[stepName][0] = optionNumber
                }
            });
            // END added to fix shadefinder skipping filters

            jQuery.ajax({
                url : collectionUrl,
                dataType : 'json',
                type : 'POST',
                data: {
                    isAjax: 1,
                    filter: params
                },
                beforeSend : function() {
                    $('#lightbox-loader, .shadeguide-overlay').show();
                },
                success : function(data){
                    if(data.status == 1){
                        $('#' + data.container).html(data.results);
                        that.removeEl('.first-toolbar, .second-toolbar');
                        /** disable unavailable options of nextStep */
                        that.disableUnavailableOptions(that, settings, nextStep, data.productIds);
                    }
                },
                complete: function() {
                    setTimeout(function() {
                        $('#lightbox-loader, .shadeguide-overlay').hide();
                    }, 500);
                }
            });
        },
        disableUnavailableOptions: function(that, settings, nextStep, productIds) {
            console.log(productIds);
            jQuery.ajax({
                url : settings.disableOptionsUrl,
                dataType : 'json',
                type : 'POST',
                data: {
                    isAjax: 1,
                    attr: nextStep.data('attr'),
                    productIds: productIds
                },
                beforeSend : function() {

                },
                success : function(data){
                    $('#lightbox-loader, .shadeguide-overlay').hide();
                    if(data.status == 1){
                        var availableOptions = data.availableOptions;
                        console.log('availableOptions', availableOptions)
                        for (var i = 0; i < availableOptions.length; i++) {
                            var input = $('.option-' + availableOptions[i]);
                            if (input.length) {
                                input.prop('disabled', false);
                                var parent = input.closest('.field-wrapper');
                                parent.find('.label').removeClass('disabled');
                                parent.find('.option-select').removeClass('disabled');
                            }
                        }
                    }
                }
            });
        },
        removeEl: function(el) {
            var elem = el.split(',');
            for (var i = 0; i < elem.length; i++) {
                if ($($.trim(elem[i])).length) {
                    var obj = $($.trim(elem[i]));
                    obj.remove();
                }
            }
        },
        checkSafariBrowser: function () {
            return navigator.userAgent.indexOf('Safari') != -1 && navigator.userAgent.indexOf('Chrome') == -1 &&  navigator.userAgent.indexOf('Android') == -1
        },
        checkFirefoxBrowser: function() {
            return typeof InstallTrigger !== 'undefined';
        },
        debug: function(data) {
            console.log(data);
        }
    }
})(jQuery);