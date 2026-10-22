import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.data('cartDrawer', () => ({
    open: false,
    cartItems: {},
    cartCount: 0,
    cartSubtotal: '$0.00',
    cartShipping: '$0.00',
    cartShippingCents: 0,
    cartTotal: '$0.00',

    init() {
        this.fetchCart();
    },

    async fetchCart() {
        const res = await fetch('/cart');
        const data = await res.json();
        this.cartItems = data.items;
        this.cartCount = data.count;
        this.cartSubtotal = data.subtotal;
        this.cartShipping = data.shipping;
        this.cartShippingCents = data.shipping_cents;
        this.cartTotal = data.total;
    },

    async addToCart(productId, qty = 1) {
        const token = document.querySelector('meta[name="csrf-token"]').content;
        await fetch('/cart/add', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token, 'Accept': 'application/json' },
            body: JSON.stringify({ product_id: productId, qty: qty }),
        });
        await this.fetchCart();
        this.open = true;
    },

    async updateQty(productId, qty) {
        const token = document.querySelector('meta[name="csrf-token"]').content;
        const res = await fetch('/cart/update', {
            method: 'PATCH',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token, 'Accept': 'application/json' },
            body: JSON.stringify({ product_id: productId, qty: qty }),
        });
        const data = await res.json();
        this.cartItems = data.items;
        this.cartCount = data.count;
        this.cartSubtotal = data.subtotal;
        this.cartShipping = data.shipping;
        this.cartShippingCents = data.shipping_cents;
        this.cartTotal = data.total;
    },

    async removeItem(productId) {
        const token = document.querySelector('meta[name="csrf-token"]').content;
        const res = await fetch('/cart/remove', {
            method: 'DELETE',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token, 'Accept': 'application/json' },
            body: JSON.stringify({ product_id: productId }),
        });
        const data = await res.json();
        this.cartItems = data.items;
        this.cartCount = data.count;
        this.cartSubtotal = data.subtotal;
        this.cartShipping = data.shipping;
        this.cartShippingCents = data.shipping_cents;
        this.cartTotal = data.total;
    },
}));

Alpine.start();
