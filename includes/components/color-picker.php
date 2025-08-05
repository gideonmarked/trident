<?php
/**
 * Color Picker Component
 * 
 * @package WordPress
 * @subpackage Trident
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Render color picker popup
 */
function trident_render_color_picker() {
    ?>
    <!-- Color Picker Popup -->
    <div id="colorPickerPopup" class="color-picker-popup">
        <div class="color-picker-content">
            <div class="color-picker-header">
                <h3>Add Color</h3>
                <button onclick="closeColorPicker()" class="close-btn">&times;</button>
            </div>
            <div class="color-picker-body">
                <div class="color-picker-section">
                    <h4>Predefined Colors</h4>
                    <div class="predefined-colors">
                        <div class="predefined-color" data-color="#fbbf24" data-name="Yellow" onclick="selectPredefinedColor('#fbbf24', 'Yellow')">
                            <div class="color-swatch" style="background: #fbbf24;"></div>
                            <span>Yellow</span>
                        </div>
                        <div class="predefined-color" data-color="#059669" data-name="Dark Green" onclick="selectPredefinedColor('#059669', 'Dark Green')">
                            <div class="color-swatch" style="background: #059669;"></div>
                            <span>Dark Green</span>
                        </div>
                        <div class="predefined-color" data-color="#1f2937" data-name="Black" onclick="selectPredefinedColor('#1f2937', 'Black')">
                            <div class="color-swatch" style="background: #1f2937;"></div>
                            <span>Black</span>
                        </div>
                        <div class="predefined-color" data-color="#10b981" data-name="Mint" onclick="selectPredefinedColor('#10b981', 'Mint')">
                            <div class="color-swatch" style="background: #10b981;"></div>
                            <span>Mint</span>
                        </div>
                        <div class="predefined-color" data-color="#f59e0b" data-name="Gold" onclick="selectPredefinedColor('#f59e0b', 'Gold')">
                            <div class="color-swatch" style="background: #f59e0b;"></div>
                            <span>Gold</span>
                        </div>
                        <div class="predefined-color" data-color="#3b82f6" data-name="Blue" onclick="selectPredefinedColor('#3b82f6', 'Blue')">
                            <div class="color-swatch" style="background: #3b82f6;"></div>
                            <span>Blue</span>
                        </div>
                        <div class="predefined-color" data-color="#ef4444" data-name="Red" onclick="selectPredefinedColor('#ef4444', 'Red')">
                            <div class="color-swatch" style="background: #ef4444;"></div>
                            <span>Red</span>
                        </div>
                        <div class="predefined-color" data-color="#8b5cf6" data-name="Purple" onclick="selectPredefinedColor('#8b5cf6', 'Purple')">
                            <div class="color-swatch" style="background: #8b5cf6;"></div>
                            <span>Purple</span>
                        </div>
                    </div>
                </div>
                
                <div class="color-picker-section">
                    <h4>Custom Color</h4>
                    <div class="custom-color-input">
                        <input type="color" id="customColorPicker" value="#fbbf24">
                        <input type="text" id="customColorHex" placeholder="#fbbf24" maxlength="7">
                        <input type="text" id="customColorName" placeholder="Color name (e.g., Custom Blue)" maxlength="20">
                    </div>
                </div>
                
                <div class="color-picker-actions">
                    <button onclick="addSelectedColor()" class="add-color-action-btn">Add Color</button>
                    <button onclick="closeColorPicker()" class="cancel-btn">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <style>
    /* Color Picker Popup Styles */
    .color-picker-popup {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 2000;
    }

    .color-picker-popup.open {
        display: flex;
    }

    .color-picker-content {
        background: white;
        border-radius: 12px;
        max-width: 500px;
        width: 90%;
        max-height: 80vh;
        overflow-y: auto;
        box-shadow: 0 20px 25px rgba(0, 0, 0, 0.1);
    }

    .color-picker-header {
        padding: 1.5rem;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .color-picker-header h3 {
        margin: 0;
        font-family: 'Montserrat', sans-serif;
        font-size: 1.25rem;
        font-weight: 600;
        color: #1f2937;
    }

    .color-picker-body {
        padding: 1.5rem;
    }

    .color-picker-section {
        margin-bottom: 2rem;
    }

    .color-picker-section h4 {
        margin: 0 0 1rem 0;
        font-family: 'Montserrat', sans-serif;
        font-size: 1rem;
        font-weight: 600;
        color: #374151;
    }

    .predefined-colors {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 1rem;
    }

    .predefined-color {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .predefined-color:hover {
        border-color: #fbbf24;
        transform: translateY(-2px);
    }

    .predefined-color.selected {
        border-color: #fbbf24;
        background: #fef3c7;
    }

    .predefined-color .color-swatch {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        border: 2px solid #e5e7eb;
    }

    .predefined-color span {
        font-family: 'Montserrat', sans-serif;
        font-size: 0.875rem;
        font-weight: 500;
        color: #374151;
        text-align: center;
    }

    .custom-color-input {
        display: flex;
        flex-direction: row;
        gap: 1rem;
        align-items: center;
    }

    .custom-color-input input[type="color"] {
        width: 20%;
        height: 50px;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        cursor: pointer;
    }

    .custom-color-input input[type="text"] {
        width: 40%;
        padding: 0.75rem;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-family: 'Montserrat', sans-serif;
        font-size: 1rem;
    }

    .custom-color-input input[type="text"]:focus {
        outline: none;
        border-color: #fbbf24;
        box-shadow: 0 0 0 3px rgba(251, 191, 36, 0.1);
    }

    .color-picker-actions {
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
        padding-top: 1rem;
        border-top: 1px solid #e5e7eb;
    }

    .add-color-action-btn {
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

    .add-color-action-btn:hover {
        background: #f59e0b;
    }
    </style>

    <script>
    // Color Picker Variables - Global Scope
    var currentColorType = '';
    var selectedColor = '';
    var selectedColorName = '';
    
    // Color Picker Functions - Global Scope
    function openColorPicker(type) {
        currentColorType = type;
        selectedColor = '';
        selectedColorName = '';
        
        // Reset form
        document.getElementById('customColorPicker').value = '#fbbf24';
        document.getElementById('customColorHex').value = '#fbbf24';
        document.getElementById('customColorName').value = '';
        
        // Remove selected class from predefined colors
        document.querySelectorAll('.predefined-color').forEach(el => {
            el.classList.remove('selected');
        });
        
        // Show popup
        document.getElementById('colorPickerPopup').classList.add('open');
        document.body.style.overflow = 'hidden';
    }
    
    function closeColorPicker() {
        document.getElementById('colorPickerPopup').classList.remove('open');
        document.body.style.overflow = 'auto';
    }
    
    function selectPredefinedColor(color, name) {
        selectedColor = color;
        selectedColorName = name;
        
        // Update form fields
        document.getElementById('customColorPicker').value = color;
        document.getElementById('customColorHex').value = color;
        document.getElementById('customColorName').value = name;
        
        // Update selected state
        document.querySelectorAll('.predefined-color').forEach(el => {
            el.classList.remove('selected');
        });
        
        // Add selected class to clicked element
        event.currentTarget.classList.add('selected');
    }
    
    function addSelectedColor() {
        const color = document.getElementById('customColorPicker').value;
        const name = document.getElementById('customColorName').value || 'Custom Color';
        
        if (!color) {
            alert('Please select a color');
            return;
        }
        
        // Add color to the appropriate section
        addColorToSection(currentColorType, color, name);
        
        // Close popup
        closeColorPicker();
    }
    
    function addColorToSection(type, color, name) {
        const container = document.getElementById(type + 'ColorOptions');
        const addButton = document.getElementById(type + 'AddBtn');
        
        // Create new color option
        const colorOption = document.createElement('div');
        colorOption.className = 'color-option';
        colorOption.innerHTML = `
            <input type="radio" name="${type}_color" value="${color}" class="color-radio" id="${type}_${color.replace('#', '')}">
            <label for="${type}_${color.replace('#', '')}" class="color-label">
                <div class="color-swatch" style="background: ${color};"></div>
                <span>${name}</span>
            </label>
        `;
        
        // Insert before add button
        container.insertBefore(colorOption, addButton);
        
        // Add event listener for color selection
        const radio = colorOption.querySelector('input[type="radio"]');
        radio.addEventListener('change', function() {
            const displayElement = document.getElementById(type + 'ColorDisplay');
            displayElement.textContent = name;
        });
        
        // Auto-select the first color added
        if (container.querySelectorAll('.color-option:not(.add-color-option)').length === 1) {
            radio.checked = true;
            document.getElementById(type + 'ColorDisplay').textContent = name;
        }
    }
    
    // Initialize color picker functionality
    document.addEventListener('DOMContentLoaded', function() {
        // Custom color picker sync
        const customColorPicker = document.getElementById('customColorPicker');
        const customColorHex = document.getElementById('customColorHex');
        const colorPickerPopup = document.getElementById('colorPickerPopup');
        
        if (customColorPicker) {
            customColorPicker.addEventListener('input', function() {
                if (customColorHex) {
                    customColorHex.value = this.value;
                }
                document.querySelectorAll('.predefined-color').forEach(el => el.classList.remove('selected'));
            });
        }
        
        if (customColorHex) {
            customColorHex.addEventListener('input', function() {
                if (customColorPicker) {
                    customColorPicker.value = this.value;
                }
                document.querySelectorAll('.predefined-color').forEach(el => el.classList.remove('selected'));
            });
        }
        
        // Close color picker when clicking outside
        if (colorPickerPopup) {
            colorPickerPopup.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeColorPicker();
                }
            });
        }
    });
    </script>
    <?php
} 