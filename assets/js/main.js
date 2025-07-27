/**
 * Trident Theme Main JavaScript
 */

(function($) {
    'use strict';

    // DOM ready
    $(document).ready(function() {
        initMobileMenu();
        initSmoothScrolling();
        initScrollEffects();
    });

    /**
     * Initialize mobile menu
     */
    function initMobileMenu() {
        $('.menu-toggle').on('click', function() {
            const $nav = $('#site-navigation');
            const $button = $(this);
            const isExpanded = $button.attr('aria-expanded') === 'true';
            
            $button.attr('aria-expanded', !isExpanded);
            $nav.toggleClass('nav-open');
            $('body').toggleClass('menu-open');
        });

        // Close menu when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('#site-navigation, .menu-toggle').length) {
                $('#site-navigation').removeClass('nav-open');
                $('.menu-toggle').attr('aria-expanded', 'false');
                $('body').removeClass('menu-open');
            }
        });
    }

    /**
     * Initialize smooth scrolling for anchor links
     */
    function initSmoothScrolling() {
        $('a[href^="#"]').on('click', function(e) {
            const target = $(this.getAttribute('href'));
            if (target.length) {
                e.preventDefault();
                $('html, body').animate({
                    scrollTop: target.offset().top - 100
                }, 800);
            }
        });
    }

    /**
     * Initialize scroll effects
     */
    function initScrollEffects() {
        let ticking = false;

        function updateHeader() {
            const scrollTop = $(window).scrollTop();
            const $header = $('#masthead');
            
            if (scrollTop > 100) {
                $header.addClass('scrolled');
            } else {
                $header.removeClass('scrolled');
            }
            
            ticking = false;
        }

        $(window).on('scroll', function() {
            if (!ticking) {
                requestAnimationFrame(updateHeader);
                ticking = true;
            }
        });
    }

    /**
     * Utility function for AJAX requests
     */
    window.tridentAjax = function(action, data, callback) {
        const requestData = {
            action: action,
            nonce: trident_ajax.nonce,
            ...data
        };

        $.ajax({
            url: trident_ajax.ajax_url,
            type: 'POST',
            data: requestData,
            success: function(response) {
                if (callback && typeof callback === 'function') {
                    callback(response);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
            }
        });
    };

    /**
     * Utility function for showing notifications
     */
    window.tridentNotify = function(message, type = 'info') {
        const $notification = $(`
            <div class="trident-notification trident-notification-${type}">
                <span class="trident-notification-message">${message}</span>
                <button class="trident-notification-close">&times;</button>
            </div>
        `);

        $('body').append($notification);

        // Auto-hide after 5 seconds
        setTimeout(function() {
            $notification.fadeOut(function() {
                $(this).remove();
            });
        }, 5000);

        // Manual close
        $notification.find('.trident-notification-close').on('click', function() {
            $notification.fadeOut(function() {
                $(this).remove();
            });
        });
    };

    /**
     * Utility function for loading states
     */
    window.tridentLoading = {
        show: function($element) {
            $element.addClass('loading').prop('disabled', true);
        },
        hide: function($element) {
            $element.removeClass('loading').prop('disabled', false);
        }
    };

})(jQuery); 