/**
 * Admin Panel Interactive Features
 * Using Vanilla JavaScript for dynamic quotation items and auto-calculation
 */

// Quotation Form - Dynamic Items Management
class QuotationForm {
    constructor() {
        this.itemsContainer = document.getElementById('quotation-items');
        this.addItemBtn = document.getElementById('add-item-btn');
        this.taxInput = document.getElementById('tax-percentage');
        this.itemCounter = 1;

        if (this.itemsContainer) {
            this.init();
        }
    }

    init() {
        // Debug: log element existence
        console.log('QuotationForm init:', {
            container: !!this.itemsContainer,
            addBtn: !!this.addItemBtn,
            taxInput: !!this.taxInput
        });

        // Add first item row
        this.addItemRow();

        // Event listeners with null checks
        if (this.addItemBtn) {
            this.addItemBtn.addEventListener('click', () => this.addItemRow());
        } else {
            console.error('Add Item button not found!');
        }

        if (this.taxInput) {
            this.taxInput.addEventListener('input', () => this.calculateTotal());
        }
    }

    addItemRow() {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>
                <select name="items[${this.itemCounter}][type]" class="form-control" required>
                    <option value="">- Select -</option>
                    <option value="Labor">Labor</option>
                    <option value="Material">Material</option>
                    <option value="Equipment">Equipment</option>
                    <option value="Services">Services</option>
                    <option value="Other">Other</option>
                </select>
            </td>
            <td><input type="text" name="items[${this.itemCounter}][name]" class="form-control" placeholder="Item name" required></td>
            <td><input type="number" step="1" min="1" name="items[${this.itemCounter}][qty]" class="form-control item-qty" placeholder="1" required></td>
            <td><input type="number" step="0.01" min="0" name="items[${this.itemCounter}][unit_price]" class="form-control item-price" placeholder="0" required></td>
            <td class="item-subtotal">Rp 0</td>
            <td><button type="button" class="btn-remove-item" onclick="removeItem(this)">×</button></td>
        `;

        this.itemsContainer.appendChild(row);

        // Add event listeners for calculation
        const qtyInput = row.querySelector('.item-qty');
        const priceInput = row.querySelector('.item-price');

        qtyInput.addEventListener('input', () => this.calculateItemSubtotal(row));
        priceInput.addEventListener('input', () => this.calculateItemSubtotal(row));

        this.itemCounter++;
    }

    calculateItemSubtotal(row) {
        const qty = parseFloat(row.querySelector('.item-qty').value) || 0;
        const price = parseFloat(row.querySelector('.item-price').value) || 0;
        const subtotal = qty * price;

        row.querySelector('.item-subtotal').textContent = this.formatCurrency(subtotal);
        this.calculateTotal();
    }

    calculateTotal() {
        let subtotal = 0;

        // Sum all item subtotals
        document.querySelectorAll('.item-qty').forEach((qtyInput, index) => {
            const row = qtyInput.closest('tr');
            const qty = parseFloat(qtyInput.value) || 0;
            const price = parseFloat(row.querySelector('.item-price').value) || 0;
            subtotal += qty * price;
        });

        const taxPercentage = parseFloat(this.taxInput?.value) || 11;
        const taxAmount = (subtotal * taxPercentage) / 100;
        const total = subtotal + taxAmount;

        // Update display
        document.getElementById('subtotal-display').textContent = this.formatCurrency(subtotal);
        document.getElementById('tax-display').textContent = this.formatCurrency(taxAmount);
        document.getElementById('total-display').textContent = this.formatCurrency(total);
    }

    formatCurrency(amount) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(amount);
    }
}

// Global function to remove item row
function removeItem(button) {
    const row = button.closest('tr');
    if (document.querySelectorAll('#quotation-items tr').length > 1) {
        row.remove();
        // Recalculate after removing
        if (window.quotationForm) {
            window.quotationForm.calculateTotal();
        }
    } else {
        alert('Minimal harus ada 1 item!');
    }
}

// Image Preview for Payment Proof
function previewPaymentProof(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function (e) {
            const preview = document.getElementById('payment-proof-preview');
            if (preview) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function () {
    // Initialize quotation form
    window.quotationForm = new QuotationForm();

    // Confirm dialogs for critical actions
    document.querySelectorAll('[data-confirm]').forEach(element => {
        element.addEventListener('click', function (e) {
            if (!confirm(this.dataset.confirm)) {
                e.preventDefault();
            }
        });
    });
});
