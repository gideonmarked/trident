/**
 * TRIDENT Mini Cart JavaScript
 * 
 * Handles mini cart functionality and interactions
 */

(function($) {
    'use strict';

    // Mini Cart Class
    class TridentMiniCart {
        constructor() {
            this.isOpen = false;
            this.isLoading = false;
            this.init();
        }

        init() {
            this.bindEvents();
            this.loadCartCount();
            this.loadCartContents();
        }

        bindEvents() {
            // Cart toggle buttons
            $(document).on('click', '#cart-toggle, #header-cart-toggle', (e) => {
                e.preventDefault();
                this.toggleCart();
            });

            // Close cart
            $(document).on('click', '.mini-cart-close, #mini-cart-overlay', (e) => {
                if (e.target === e.currentTarget) {
                    this.closeCart();
                }
            });

            // Escape key to close cart
            $(document).on('keydown', (e) => {
                if (e.key === 'Escape' && this.isOpen) {
                    this.closeCart();
                }
            });

            // Remove item from cart
            $(document).on('click', '.cart-item-remove', (e) => {
                e.preventDefault();
                const cartKey = $(e.target).closest('.mini-cart-item').data('cart-key');
                this.removeFromCart(cartKey);
            });

            // Update quantity
            $(document).on('click', '.quantity-btn', (e) => {
                e.preventDefault();
                const $item = $(e.target).closest('.mini-cart-item');
                const cartKey = $item.data('cart-key');
                const action = $(e.target).data('action');
                const $input = $item.find('.quantity-input');
                let quantity = parseInt($input.val());

                if (action === 'increase') {
                    quantity++;
                } else if (action === 'decrease') {
                    quantity = Math.max(1, quantity - 1);
                }

                $input.val(quantity);
                this.updateCartQuantity(cartKey, quantity);
            });

            // Quantity input change
            $(document).on('change', '.quantity-input', (e) => {
                const $item = $(e.target).closest('.mini-cart-item');
                const cartKey = $item.data('cart-key');
                const quantity = parseInt($(e.target).val());
                
                if (quantity > 0) {
                    this.updateCartQuantity(cartKey, quantity);
                }
            });

            // Add to cart buttons (global)
            $(document).on('click', '.add-to-cart-btn', (e) => {
                e.preventDefault();
                const $btn = $(e.target);
                const productId = $btn.data('product-id');
                const quantity = parseInt($btn.data('quantity') || 1);
                const options = $btn.data('options') || {};
                
                this.addToCart(productId, quantity, options);
            });
        }

        toggleCart() {
            if (this.isOpen) {
                this.closeCart();
            } else {
                this.openCart();
            }
        }

        openCart() {
            if (this.isOpen) return;
            
            this.isOpen = true;
            $('#mini-cart-overlay').addClass('active');
            $('body').addClass('cart-open');
            
            // Load cart contents if not already loaded
            this.loadCartContents();
        }

        closeCart() {
            if (!this.isOpen) return;
            
            this.isOpen = false;
            $('#mini-cart-overlay').removeClass('active');
            $('body').removeClass('cart-open');
        }

        loadCartCount() {
            $.ajax({
                url: trident_cart_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'trident_get_cart_count',
                    nonce: trident_cart_ajax.nonce
                },
                success: (response) => {
                    if (response.success) {
                        this.updateCartCount(response.data.cart_count);
                        this.updateCartTotal(response.data.cart_total);
                    }
                }
            });
        }

        loadCartContents() {
            if (this.isLoading) return;
            
            this.isLoading = true;
            const $itemsContainer = $('#mini-cart-items');
            const $emptyContainer = $('#mini-cart-empty');
            const $footerContainer = $('#mini-cart-footer');
            
            $.ajax({
                url: trident_cart_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'trident_get_cart_contents',
                    nonce: trident_cart_ajax.nonce
                },
                success: (response) => {
                    if (response.success && response.data.items) {
                        $itemsContainer.html(response.data.html);
                        $emptyContainer.hide();
                        $footerContainer.show();
                    } else {
                        $itemsContainer.empty();
                        $emptyContainer.show();
                        $footerContainer.hide();
                    }
                },
                error: () => {
                    $itemsContainer.empty();
                    $emptyContainer.show();
                    $footerContainer.hide();
                },
                complete: () => {
                    this.isLoading = false;
                }
            });
        }

        addToCart(productId, quantity = 1, options = {}) {
            const $btn = $(`.add-to-cart-btn[data-product-id="${productId}"]`);
            const originalText = $btn.text();
            
            // Show loading state
            $btn.prop('disabled', true).text('Adding...');
            
            $.ajax({
                url: trident_cart_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'trident_add_to_cart',
                    product_id: productId,
                    quantity: quantity,
                    options: options,
                    nonce: trident_cart_ajax.nonce
                },
                success: (response) => {
                    if (response.success) {
                        this.updateCartCount(response.data.cart_count);
                        this.updateCartTotal(response.data.cart_total);
                        this.showNotification(response.data.message, 'success');
                        
                        // Reload cart contents if cart is open
                        if (this.isOpen) {
                            this.loadCartContents();
                        }
                    } else {
                        this.showNotification(trident_cart_ajax.strings.error, 'error');
                    }
                },
                error: () => {
                    this.showNotification(trident_cart_ajax.strings.error, 'error');
                },
                complete: () => {
                    // Restore button state
                    $btn.prop('disabled', false).text(originalText);
                }
            });
        }

        removeFromCart(cartKey) {
            const $item = $(`.mini-cart-item[data-cart-key="${cartKey}"]`);
            
            $.ajax({
                url: trident_cart_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'trident_remove_from_cart',
                    cart_key: cartKey,
                    nonce: trident_cart_ajax.nonce
                },
                success: (response) => {
                    if (response.success) {
                        $item.fadeOut(300, () => {
                            $item.remove();
                            this.updateCartCount(response.data.cart_count);
                            this.updateCartTotal(response.data.cart_total);
                            
                            // Check if cart is empty
                            if ($('.mini-cart-item').length === 0) {
                                $('#mini-cart-items').empty();
                                $('#mini-cart-empty').show();
                                $('#mini-cart-footer').hide();
                            }
                        });
                        
                        this.showNotification(response.data.message, 'success');
                    } else {
                        this.showNotification(trident_cart_ajax.strings.error, 'error');
                    }
                },
                error: () => {
                    this.showNotification(trident_cart_ajax.strings.error, 'error');
                }
            });
        }

        updateCartQuantity(cartKey, quantity) {
            const $item = $(`.mini-cart-item[data-cart-key="${cartKey}"]`);
            
            $.ajax({
                url: trident_cart_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'trident_update_cart_quantity',
                    cart_key: cartKey,
                    quantity: quantity,
                    nonce: trident_cart_ajax.nonce
                },
                success: (response) => {
                    if (response.success) {
                        this.updateCartCount(response.data.cart_count);
                        this.updateCartTotal(response.data.cart_total);
                        
                        // Update item total
                        if (response.data.item_total !== undefined) {
                            $item.find('.cart-item-total').text('$' + response.data.item_total);
                        }
                        
                        // Remove item if quantity is 0
                        if (quantity <= 0) {
                            $item.fadeOut(300, () => {
                                $item.remove();
                                
                                // Check if cart is empty
                                if ($('.mini-cart-item').length === 0) {
                                    $('#mini-cart-items').empty();
                                    $('#mini-cart-empty').show();
                                    $('#mini-cart-footer').hide();
                                }
                            });
                        }
                        
                        this.showNotification(response.data.message, 'success');
                    } else {
                        this.showNotification(trident_cart_ajax.strings.error, 'error');
                    }
                },
                error: () => {
                    this.showNotification(trident_cart_ajax.strings.error, 'error');
                }
            });
        }

        updateCartCount(count) {
            $('#cart-count, #header-cart-count').text(count);
            
            // Add animation class
            const $countElements = $('#cart-count, #header-cart-count');
            $countElements.addClass('cart-count-updated');
            setTimeout(() => {
                $countElements.removeClass('cart-count-updated');
            }, 300);
        }

        updateCartTotal(total) {
            $('#mini-cart-total').text('$' + parseFloat(total).toFixed(2));
        }

        showNotification(message, type = 'info') {
            // Remove existing notifications
            $('.cart-notification').remove();
            
            const $notification = $(`
                <div class="cart-notification cart-notification-${type}">
                    <span>${message}</span>
                    <button class="notification-close">&times;</button>
                </div>
            `);
            
            $('body').append($notification);
            
            // Show notification
            setTimeout(() => {
                $notification.addClass('show');
            }, 100);
            
            // Auto hide after 3 seconds
            setTimeout(() => {
                $notification.removeClass('show');
                setTimeout(() => {
                    $notification.remove();
                }, 300);
            }, 3000);
            
            // Manual close
            $notification.on('click', '.notification-close', () => {
                $notification.removeClass('show');
                setTimeout(() => {
                    $notification.remove();
                }, 300);
            });
        }
    }

    // Initialize mini cart when document is ready
    $(document).ready(() => {
        window.tridentMiniCart = new TridentMiniCart();
    });

    // Global function to add to cart (can be called from other scripts)
    window.tridentAddToCart = function(productId, quantity = 1, options = {}) {
        if (window.tridentMiniCart) {
            window.tridentMiniCart.addToCart(productId, quantity, options);
        }
    };

    // Global function to open cart
    window.tridentOpenCart = function() {
        if (window.tridentMiniCart) {
            window.tridentMiniCart.openCart();
        }
    };

    // Global function to close cart
    window.tridentCloseCart = function() {
        if (window.tridentMiniCart) {
            window.tridentMiniCart.closeCart();
        }
    };

})(jQuery); 