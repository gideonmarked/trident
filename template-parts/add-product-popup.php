<?php
/**
 * Add Product Popup Template
 * 
 * @package WordPress
 * @subpackage Trident
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
?>

<!-- Add Product Popup -->
<div id="addProductPopup" class="add-product-popup">
    <div class="popup-header">
        <h2 class="popup-title">Add Product</h2>
        <button onclick="closeAddProductPopup()" class="close-btn">&times;</button>
    </div>
    <div class="popup-content">
        
        <form id="addProductForm">
            <!-- Body Colors Section -->
            <div class="color-section">
                <div class="color-section-header">
                    <h3 class="color-section-title">Body Color - 22oz</h3>
                    <span class="selected-color" id="bodyColorDisplay">No colors selected</span>
                </div>
                <div class="color-options" id="bodyColorOptions">
                    <!-- Add Button for Body Colors -->
                    <div class="color-option add-color-option" id="bodyAddBtn" onclick="openColorPicker('body')">
                        <div class="add-color-btn">
                            <span>+</span>
                        </div>
                        <span>Add Color</span>
                    </div>
                </div>
            </div>
            
            <!-- Cap Colors Section -->
            <div class="color-section">
                <div class="color-section-header">
                    <h3 class="color-section-title">Cap Color - 22oz</h3>
                    <span class="selected-color" id="capColorDisplay">No colors selected</span>
                </div>
                <div class="color-options" id="capColorOptions">
                    <!-- Add Button for Cap Colors -->
                    <div class="color-option add-color-option" id="capAddBtn" onclick="openColorPicker('cap')">
                        <div class="add-color-btn">
                            <span>+</span>
                        </div>
                        <span>Add Color</span>
                    </div>
                </div>
            </div>
            
            <!-- Boot Colors Section -->
            <div class="color-section">
                <div class="color-section-header">
                    <h3 class="color-section-title">Boot Color - 22oz</h3>
                    <span class="selected-color" id="bootColorDisplay">No colors selected</span>
                </div>
                <div class="color-options" id="bootColorOptions">
                    <!-- Add Button for Boot Colors -->
                    <div class="color-option add-color-option" id="bootAddBtn" onclick="openColorPicker('boot')">
                        <div class="add-color-btn">
                            <span>+</span>
                        </div>
                        <span>Add Color</span>
                    </div>
                </div>
            </div>
            
            <!-- Product Details Section -->
            <div class="product-details-section">
                <h3 class="section-title">Product Details</h3>
                
                <!-- Size Selection -->
                <div class="size-selection">
                    <label class="size-label">Size:</label>
                    <div class="size-buttons">
                        <button type="button" class="size-btn active" data-size="24oz">24oz</button>
                        <button type="button" class="size-btn" data-size="32oz">32oz</button>
                    </div>
                </div>
                
                <!-- Product Name -->
                <div class="form-group">
                    <label for="product_name" class="form-label">Product Name</label>
                    <input type="text" id="product_name" name="product_name" class="form-input" required>
                </div>
                
                <!-- Product Details -->
                <div class="form-group">
                    <label for="product_details" class="form-label">Product Details</label>
                    <textarea id="product_details" name="product_details" class="form-textarea" rows="4"></textarea>
                </div>
                
                <!-- Product Image -->
                <div class="form-group">
                    <label for="product_image" class="form-label">Product Image</label>
                    <input type="file" id="product_image" name="product_image" class="form-input" accept="image/*">
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="popup-actions">
                <button type="button" onclick="closeAddProductPopup()" class="cancel-btn">Cancel</button>
                <button type="submit" class="save-btn">Add Product</button>
            </div>
        </form>
    </div>
</div>

<style>
/* Add Product Popup Styles */
.add-product-popup {
    position: fixed;
    top: 0;
    right: -500px;
    width: 500px;
    height: 100vh;
    background: white;
    box-shadow: -5px 0 15px rgba(0, 0, 0, 0.1);
    transition: right 0.3s ease;
    z-index: 1000;
    overflow-y: auto;
}

.add-product-popup.open {
    right: 0;
}

.popup-header {
    padding: 2rem 2rem 1rem;
    border-bottom: 1px solid #e5e7eb;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.popup-title {
    font-family: 'Montserrat', sans-serif;
    font-size: 1.5rem;
    font-weight: 600;
    color: #1f2937;
    margin: 0;
}

.close-btn {
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    color: #6b7280;
    padding: 0.5rem;
    border-radius: 4px;
    transition: all 0.3s ease;
}

.close-btn:hover {
    background: #f3f4f6;
    color: #374151;
}

.popup-content {
    padding: 2rem;
    max-height: 80vh;
    overflow-y: auto;
}

.color-section {
    margin-bottom: 2rem;
    padding: 1.5rem;
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.color-section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
    flex-wrap: wrap;
    gap: 1rem;
}

.color-section-title {
    font-family: 'Montserrat', sans-serif;
    font-size: 1.125rem;
    font-weight: 600;
    color: #374151;
    margin: 0;
    flex-shrink: 0;
}

.selected-color {
    font-family: 'Montserrat', sans-serif;
    font-weight: 500;
    color: #6b7280;
    background: #fef3c7;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.875rem;
    flex-shrink: 0;
}

.color-options {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
    align-items: center;
    justify-content: flex-start;
    margin-top: 1rem;
}

.color-option {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    min-width: 60px;
}

.color-radio {
    display: none;
}

.color-label {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
    transition: all 0.3s ease;
    text-align: center;
    min-width: 60px;
}

/* Add Color Button Styles */
.add-color-option {
    cursor: pointer;
    transition: all 0.3s ease;
}

.add-color-option:hover {
    transform: scale(1.05);
}

.add-color-btn {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: white;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #374151;
    font-size: 1.5rem;
    font-weight: bold;
    transition: all 0.3s ease;
    border: 2px solid #d1d5db;
}

.add-color-btn:hover {
    background: #f9fafb;
    border-color: #9ca3af;
    transform: scale(1.1);
}

.add-color-option span:last-child {
    font-family: 'Montserrat', sans-serif;
    font-size: 0.875rem;
    font-weight: 500;
    color: #6b7280;
}

/* Product Details Section */
.product-details-section {
    margin-bottom: 2rem;
}

.section-title {
    font-family: 'Montserrat', sans-serif;
    font-size: 1.25rem;
    font-weight: 600;
    color: #374151;
    margin-bottom: 1.5rem;
}

.size-selection {
    margin-bottom: 1.5rem;
}

.size-label {
    display: block;
    font-family: 'Montserrat', sans-serif;
    font-weight: 500;
    color: #374151;
    margin-bottom: 0.75rem;
}

.size-buttons {
    display: flex;
    gap: 0.5rem;
}

.size-btn {
    padding: 0.5rem 1rem;
    border: 2px solid #fbbf24;
    background: white;
    color: #374151;
    border-radius: 20px;
    cursor: pointer;
    font-family: 'Montserrat', sans-serif;
    font-weight: 500;
    transition: all 0.3s ease;
}

.size-btn.active {
    background: #fbbf24;
    color: white;
}

.size-btn:hover {
    background: #fbbf24;
    color: white;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    display: block;
    font-family: 'Montserrat', sans-serif;
    font-weight: 500;
    color: #374151;
    margin-bottom: 0.5rem;
}

.form-input,
.form-textarea {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    font-family: 'Montserrat', sans-serif;
    font-size: 1rem;
    transition: border-color 0.3s ease;
}

.form-textarea {
    resize: vertical;
    min-height: 100px;
}

.form-input:focus,
.form-textarea:focus {
    outline: none;
    border-color: #fbbf24;
    box-shadow: 0 0 0 3px rgba(251, 191, 36, 0.1);
}

.popup-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    padding: 1.5rem 2rem;
    border-top: 1px solid #e5e7eb;
}

.cancel-btn {
    background: #f3f4f6;
    color: #374151;
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    cursor: pointer;
    font-family: 'Montserrat', sans-serif;
    font-weight: 500;
    transition: all 0.3s ease;
}

.cancel-btn:hover {
    background: #e5e7eb;
}

.save-btn {
    background: #fbbf24;
    color: white;
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    cursor: pointer;
    font-family: 'Montserrat', sans-serif;
    font-weight: 500;
    transition: all 0.3s ease;
}

.save-btn:hover {
    background: #f59e0b;
}

/* Color swatch styles */
.color-swatch {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    border: 2px solid #e5e7eb;
    transition: all 0.3s ease;
}

.color-radio:checked + .color-label .color-swatch {
    border: 3px solid #fbbf24;
    transform: scale(1.1);
}

.color-label:hover {
    transform: translateY(-2px);
}
</style>

<script>
// Add Product Popup Functions
function openAddProductPopup() {
    const popup = document.getElementById('addProductPopup');
    if (popup) {
        popup.classList.add('open');
        document.body.style.overflow = 'hidden';
        // Show dark overlay when popup opens
        document.body.classList.add('popup-open');
    }
}

function closeAddProductPopup() {
    const popup = document.getElementById('addProductPopup');
    if (popup) {
        popup.classList.remove('open');
        document.body.style.overflow = 'auto';
        // Hide dark overlay when popup closes
        document.body.classList.remove('popup-open');
        // Reset form
        document.getElementById('addProductForm').reset();
        // Reset size buttons
        document.querySelectorAll('.size-btn').forEach(btn => {
            btn.classList.remove('active');
        });
        document.querySelector('.size-btn[data-size="24oz"]').classList.add('active');
        // Reset color displays
        document.getElementById('bodyColorDisplay').textContent = 'No colors selected';
        document.getElementById('capColorDisplay').textContent = 'No colors selected';
        document.getElementById('bootColorDisplay').textContent = 'No colors selected';
    }
}

function setupAddProductPopup() {
    // Color selection handlers
    document.querySelectorAll('input[name="body_color"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const label = this.nextElementSibling.querySelector('span').textContent;
            document.getElementById('bodyColorDisplay').textContent = label;
        });
    });
    
    document.querySelectorAll('input[name="cap_color"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const label = this.nextElementSibling.querySelector('span').textContent;
            document.getElementById('capColorDisplay').textContent = label;
        });
    });
    
    document.querySelectorAll('input[name="boot_color"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const label = this.nextElementSibling.querySelector('span').textContent;
            document.getElementById('bootColorDisplay').textContent = label;
        });
    });
    
    // Size button handlers
    document.querySelectorAll('.size-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.size-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
        });
    });
    
    // Form submission handler
    const addProductForm = document.getElementById('addProductForm');
    if (addProductForm) {
        addProductForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Validate form data
        const productName = document.getElementById('product_name').value.trim();
        const productDetails = document.getElementById('product_details').value.trim();
        
        // Check if product name is provided
        if (!productName) {
            alert('Please enter a product name');
            document.getElementById('product_name').focus();
            return;
        }
        
        // Check if product details are provided
        if (!productDetails) {
            alert('Please enter product details');
            document.getElementById('product_details').focus();
            return;
        }
        
        // Check if at least one color is selected for each category
        const bodyColors = document.querySelectorAll('#bodyColorOptions .color-option:not(.add-color-option) input[type="radio"]:checked');
        const capColors = document.querySelectorAll('#capColorOptions .color-option:not(.add-color-option) input[type="radio"]:checked');
        const bootColors = document.querySelectorAll('#bootColorOptions .color-option:not(.add-color-option) input[type="radio"]:checked');
        
        if (bodyColors.length === 0) {
            alert('Please select at least one body color');
            return;
        }
        
        if (capColors.length === 0) {
            alert('Please select at least one cap color');
            return;
        }
        
        if (bootColors.length === 0) {
            alert('Please select at least one boot color');
            return;
        }
        
        // Get form data
        const formData = new FormData(this);
        const selectedSize = document.querySelector('.size-btn.active').dataset.size;
        formData.append('size', selectedSize);
        formData.append('action', 'trident_add_product');
        formData.append('nonce', '<?php echo wp_create_nonce('trident_product_nonce'); ?>');
        
        // Collect color data
        const bodyColorData = [];
        const capColorData = [];
        const bootColorData = [];
        
        // Get all body colors (not just selected ones)
        document.querySelectorAll('#bodyColorOptions .color-option:not(.add-color-option) input[type="radio"]').forEach(radio => {
            bodyColorData.push(radio.value);
        });
        
        // Get all cap colors
        document.querySelectorAll('#capColorOptions .color-option:not(.add-color-option) input[type="radio"]').forEach(radio => {
            capColorData.push(radio.value);
        });
        
        // Get all boot colors
        document.querySelectorAll('#bootColorOptions .color-option:not(.add-color-option) input[type="radio"]').forEach(radio => {
            bootColorData.push(radio.value);
        });
        
        formData.append('body_colors', JSON.stringify(bodyColorData));
        formData.append('cap_colors', JSON.stringify(capColorData));
        formData.append('boot_colors', JSON.stringify(bootColorData));
        
        // Show loading overlay
        document.body.classList.add('loading');
        
        // Show loading state
        const saveBtn = document.querySelector('#addProductForm .save-btn');
        const originalText = saveBtn.textContent;
        saveBtn.textContent = 'Adding...';
        saveBtn.disabled = true;
        
        // Submit form
        fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            // Hide loading overlay
            document.body.classList.remove('loading');
            
            // Reset button
            saveBtn.textContent = originalText;
            saveBtn.disabled = false;
            
            if (data.success) {
                // Show success message
                const successMessage = document.createElement('div');
                successMessage.style.cssText = 'position: fixed; top: 20px; right: 20px; background: #10b981; color: white; padding: 1rem 1.5rem; border-radius: 8px; z-index: 1000; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);';
                successMessage.textContent = 'Product added successfully!';
                document.body.appendChild(successMessage);
                
                // Remove success message after 3 seconds
                setTimeout(() => {
                    if (successMessage.parentNode) {
                        successMessage.parentNode.removeChild(successMessage);
                    }
                }, 3000);
                
                closeAddProductPopup();
                location.reload(); // Refresh page to show new product
            } else {
                // Show error message
                const errorMessage = document.createElement('div');
                errorMessage.style.cssText = 'position: fixed; top: 20px; right: 20px; background: #ef4444; color: white; padding: 1rem 1.5rem; border-radius: 8px; z-index: 1000; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);';
                errorMessage.textContent = 'Error adding product: ' + data.data;
                document.body.appendChild(errorMessage);
                
                // Remove error message after 5 seconds
                setTimeout(() => {
                    if (errorMessage.parentNode) {
                        errorMessage.parentNode.removeChild(errorMessage);
                    }
                }, 5000);
            }
        })
        .catch(error => {
            // Hide loading overlay
            document.body.classList.remove('loading');
            
            // Reset button
            saveBtn.textContent = originalText;
            saveBtn.disabled = false;
            
            console.error('Error:', error);
            
            // Show error message
            const errorMessage = document.createElement('div');
            errorMessage.style.cssText = 'position: fixed; top: 20px; right: 20px; background: #ef4444; color: white; padding: 1rem 1.5rem; border-radius: 8px; z-index: 1000; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);';
            errorMessage.textContent = 'Error adding product. Please try again.';
            document.body.appendChild(errorMessage);
            
            // Remove error message after 5 seconds
            setTimeout(() => {
                if (errorMessage.parentNode) {
                    errorMessage.parentNode.removeChild(errorMessage);
                }
            }, 5000);
        });
    });
    }
    
    // Close popup when clicking outside
    const addProductPopup = document.getElementById('addProductPopup');
    if (addProductPopup) {
        addProductPopup.addEventListener('click', function(e) {
            if (e.target === this) {
                closeAddProductPopup();
            }
        });
    }
    
    // Close popup with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeAddProductPopup();
        }
    });
}

// Initialize popup when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    setupAddProductPopup();
});
</script> 