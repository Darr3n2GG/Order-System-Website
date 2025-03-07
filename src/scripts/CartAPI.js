import { eventBus } from "./eventBus.js";


export class Cart {
    cart = [];

    constructor() {
        eventBus.addEventListener("addItemToCart", event => {
            this.addItemToCart(event.detail.item);
        });
    }

    getCart() {
        return this.cart;
    }

    getCartLength() {
        return this.cart.length;
    }

    updateCartItemQuantity(id, value) {
        const cartItem = this.findCartItemByID(id);
        cartItem.kuantiti = value;
    }

    addItemToCart(item) {
        const cartItem = this.findCartItemByID(item.id);
        if (cartItem === true) {
            cartItem.kuantiti += item.kuantiti;
            eventBus.emit("updateItemQuantityInCartDialog", {
                item: cartItem
            });
        } else {
            const cartItem = {
                id: item.id,
                kuantiti: item.kuantiti,
            };
            this.cart.push(cartItem);
            eventBus.emit("addItemToCartDialog", {
                newItem: item
            });
        }
    }

    findCartItemByID(id) {
        for (let i = 0; i < this.getCartLength(); i++) {
            const item = this.cart[i];
            if (item.id === id) {
                return item;
            }
        }
        return false;
    }
}

