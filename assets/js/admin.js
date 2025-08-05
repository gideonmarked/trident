/**
 * Trident Theme Admin JavaScript
 */

(function($) {
    'use strict';

    // DOM ready
    $(document).ready(function() {
        initAdminFeatures();
    });

    /**
     * Initialize admin features
     */
    function initAdminFeatures() {
        initColorPickers();
        initImageUploaders();
        initFormValidation();
        initTabs();
        initTooltips();
    }

    /**
     * Initialize color pickers
     */
    function initColorPickers() {
        $('.trident-color-picker input[type="color"]').on('change', function() {
            const $input = $(this);
            const $preview = $input.siblings('.color-preview');
            
            if ($preview.length) {
                $preview.css('background-color', $input.val());
            }
        });
    }

    /**
     * Initialize image uploaders
     */
    function initImageUploaders() {
        $('.trident-image-upload').on('click', function(e) {
            e.preventDefault();
            
            const $button = $(this);
            const $input = $button.siblings('input[type="hidden"]');
            const $preview = $button.siblings('.image-preview');
            
            const frame = wp.media({
                title: 'Select Image',
                button: {
                    text: 'Use this image'
                },
                multiple: false
            });

            frame.on('select', function() {
                const attachment = frame.state().get('selection').first().toJSON();
                $input.val(attachment.id);
                
                if ($preview.length) {
                    $preview.html(`<img src="${attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url}" alt="${attachment.alt}" />`);
                }
            });

            frame.open();
        });
    }

    /**
     * Initialize form validation
     */
    function initFormValidation() {
        $('.trident-form').on('submit', function(e) {
            const $form = $(this);
            const $requiredFields = $form.find('[required]');
            let isValid = true;

            $requiredFields.each(function() {
                const $field = $(this);
                const value = $field.val().trim();
                
                if (!value) {
                    $field.addClass('error');
                    isValid = false;
                } else {
                    $field.removeClass('error');
                }
            });

            if (!isValid) {
                e.preventDefault();
                showNotification('Please fill in all required fields.', 'error');
            }
        });

        // Remove error class on input
        $('input, textarea, select').on('input change', function() {
            $(this).removeClass('error');
        });
    }

    /**
     * Initialize tabs
     */
    function initTabs() {
        $('.trident-tabs').each(function() {
            const $tabs = $(this);
            const $tabButtons = $tabs.find('.tab-button');
            const $tabContents = $tabs.find('.tab-content');

            $tabButtons.on('click', function(e) {
                e.preventDefault();
                
                const $button = $(this);
                const target = $button.attr('data-tab');
                
                // Update active states
                $tabButtons.removeClass('active');
                $button.addClass('active');
                
                $tabContents.removeClass('active');
                $tabs.find(`[data-tab="${target}"]`).addClass('active');
            });
        });
    }

    /**
     * Initialize tooltips
     */
    function initTooltips() {
        $('[data-tooltip]').on('mouseenter', function() {
            const $element = $(this);
            const tooltipText = $element.attr('data-tooltip');
            
            const $tooltip = $(`
                <div class="trident-tooltip">
                    ${tooltipText}
                </div>
            `);
            
            $('body').append($tooltip);
            
            const elementRect = $element[0].getBoundingClientRect();
            const tooltipRect = $tooltip[0].getBoundingClientRect();
            
            $tooltip.css({
                position: 'fixed',
                top: elementRect.top - tooltipRect.height - 10,
                left: elementRect.left + (elementRect.width / 2) - (tooltipRect.width / 2),
                zIndex: 9999
            });
        }).on('mouseleave', function() {
            $('.trident-tooltip').remove();
        });
    }

    /**
     * Show notification
     */
    function showNotification(message, type = 'info') {
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
    }

    /**
     * Utility function for AJAX requests
     */
    window.tridentAdminAjax = function(action, data, callback) {
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
                console.error('Admin AJAX Error:', error);
                showNotification('An error occurred. Please try again.', 'error');
            }
        });
    };

    /**
     * Utility function for loading states
     */
    window.tridentAdminLoading = {
        show: function($element) {
            $element.addClass('trident-loading').prop('disabled', true);
        },
        hide: function($element) {
            $element.removeClass('trident-loading').prop('disabled', false);
        }
    };

    /**
     * Utility function for confirmation dialogs
     */
    window.tridentConfirm = function(message, callback) {
        if (confirm(message)) {
            if (callback && typeof callback === 'function') {
                callback();
            }
        }
    };

    /**
     * Utility function for copying to clipboard
     */
    window.tridentCopyToClipboard = function(text) {
        if (navigator.clipboard) {
            navigator.clipboard.writeText(text).then(function() {
                showNotification('Copied to clipboard!', 'success');
            }).catch(function() {
                fallbackCopyToClipboard(text);
            });
        } else {
            fallbackCopyToClipboard(text);
        }
    };

    function fallbackCopyToClipboard(text) {
        const textArea = document.createElement('textarea');
        textArea.value = text;
        textArea.style.position = 'fixed';
        textArea.style.left = '-999999px';
        textArea.style.top = '-999999px';
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();
        
        try {
            document.execCommand('copy');
            showNotification('Copied to clipboard!', 'success');
        } catch (err) {
            showNotification('Failed to copy to clipboard.', 'error');
        }
        
        document.body.removeChild(textArea);
    }

})(jQuery); 