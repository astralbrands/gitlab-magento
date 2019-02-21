(function($) {
    $(document).ready(function() {
        /**
         * IE 8 fix for header
         * should be moved outside a js for ie
         */
        jQuery("#wpmm-nav li.parent").hover(function() {
            var openedElement = jQuery(this).find(".wpmm-nav-content");
            if (jQuery("html").hasClass("lt-ie9")) {
                jQuery("#page-header").height(jQuery("#page-header").height() + openedElement.height() + "px");
            }
        }, function() {
            if (jQuery("html").hasClass("lt-ie9")) {
                jQuery("#page-header").height("auto");
            }
        });
        /**
         * IE 8 fix for header
         */
        var $headerTop = $("#page-header-section-01");
        var $headerBottom = $("#page-header-section-02");
        var $headerEnd = $("#endHeader");

        onScroll($headerTop, $headerBottom);

        $(window).on("scroll", {
            "headerTop": $headerTop,
            "headerBottom": $headerBottom,
        }, function(e) {
            _throttledDebounce(e.data.headerTop, e.data.headerBottom);
        });
    });


    var onScroll = function($headerTop, $headerBottom) {
        var curOffset = $(window).scrollTop();
        var stickyHeader = 0;
        var offsetMargin = 0;

        if (typeof $headerTop !== "undefined" && typeof $headerBottom !== "undefined") {
            if ($headerBottom.length) {
                offsetMargin = $headerBottom.outerHeight();
            }
            if ($headerTop.length) {
                stickyHeader = $headerTop.offset().top + $headerTop.outerHeight();
            }
            if (curOffset < stickyHeader) {
                $headerBottom.removeClass("sticky-header");
                $headerTop.css('margin-bottom', 0);
            } else {
                $headerBottom.addClass("sticky-header");
                $headerTop.css('margin-bottom', offsetMargin);
            }

        }
    };

    function _debounce(func, wait, options) {
        let lastArgs,
            lastThis,
            maxWait,
            result,
            timerId,
            lastCallTime
        let lastInvokeTime = 0
        let leading = false
        let maxing = false
        let trailing = true
        // Bypass `requestAnimationFrame` by explicitly setting `wait=0`.
        const useRAF = (!wait && wait !== 0 && typeof root.requestAnimationFrame === 'function')
        if (typeof func != 'function') {
            throw new TypeError('Expected a function')
        }
        wait = +wait || 0
        if (isObject(options)) {
            leading = !!options.leading
            maxing = 'maxWait' in options
            maxWait = maxing ? Math.max(+options.maxWait || 0, wait) : maxWait
            trailing = 'trailing' in options ? !!options.trailing : trailing
        }

        function invokeFunc(time) {
            const args = lastArgs
            const thisArg = lastThis
            lastArgs = lastThis = undefined
            lastInvokeTime = time
            result = func.apply(thisArg, args)
            return result
        }

        function startTimer(pendingFunc, wait) {
            if (useRAF) {
                return root.requestAnimationFrame(pendingFunc)
            }
            return setTimeout(pendingFunc, wait)
        }

        function cancelTimer(id) {
            if (useRAF) {
                return root.cancelAnimationFrame(id)
            }
            clearTimeout(id)
        }

        function leadingEdge(time) {
            // Reset any `maxWait` timer.
            lastInvokeTime = time
            // Start the timer for the trailing edge.
            timerId = startTimer(timerExpired, wait)
            // Invoke the leading edge.
            return leading ? invokeFunc(time) : result
        }

        function remainingWait(time) {
            const timeSinceLastCall = time - lastCallTime
            const timeSinceLastInvoke = time - lastInvokeTime
            const timeWaiting = wait - timeSinceLastCall
            return maxing ? Math.min(timeWaiting, maxWait - timeSinceLastInvoke) : timeWaiting
        }

        function shouldInvoke(time) {
            const timeSinceLastCall = time - lastCallTime
            const timeSinceLastInvoke = time - lastInvokeTime
            // Either this is the first call, activity has stopped and we're at the
            // trailing edge, the system time has gone backwards and we're treating
            // it as the trailing edge, or we've hit the `maxWait` limit.
            return (lastCallTime === undefined || (timeSinceLastCall >= wait) || (timeSinceLastCall < 0) || (maxing && timeSinceLastInvoke >= maxWait))
        }

        function timerExpired() {
            const time = Date.now()
            if (shouldInvoke(time)) {
                return trailingEdge(time)
            }
            // Restart the timer.
            timerId = startTimer(timerExpired, remainingWait(time))
        }

        function trailingEdge(time) {
            timerId = undefined
            // Only invoke if we have `lastArgs` which means `func` has been
            // debounced at least once.
            if (trailing && lastArgs) {
                return invokeFunc(time)
            }
            lastArgs = lastThis = undefined
            return result
        }

        function cancel() {
            if (timerId !== undefined) {
                cancelTimer(timerId)
            }
            lastInvokeTime = 0
            lastArgs = lastCallTime = lastThis = timerId = undefined
        }

        function flush() {
            return timerId === undefined ? result : trailingEdge(Date.now())
        }

        function pending() {
            return timerId !== undefined
        }

        function debounced(...args) {
            const time = Date.now()
            const isInvoking = shouldInvoke(time)
            lastArgs = args
            lastThis = this
            lastCallTime = time
            if (isInvoking) {
                if (timerId === undefined) {
                    return leadingEdge(lastCallTime)
                }
                if (maxing) {
                    // Handle invocations in a tight loop.
                    timerId = startTimer(timerExpired, wait)
                    return invokeFunc(lastCallTime)
                }
            }
            if (timerId === undefined) {
                timerId = startTimer(timerExpired, wait)
            }
            return result
        }
        debounced.cancel = cancel
        debounced.flush = flush
        debounced.pending = pending
        return debounced
    }

    function _throttle(func, wait, options) {
        let leading = true
        let trailing = true
        if (typeof func !== 'function') {
            throw new TypeError('Expected a function')
        }
        if (isObject(options)) {
            leading = 'leading' in options ? !!options.leading : leading
            trailing = 'trailing' in options ? !!options.trailing : trailing
        }
        return _debounce(func, wait, {
            leading,
            trailing,
            'maxWait': wait,
        })
    }

    function isObject(value) {
      const type = typeof value
      return value != null && (type == 'object' || type == 'function')
    }

    var _throttledDebounce = _throttle(onScroll, 100);

})(jQuery);