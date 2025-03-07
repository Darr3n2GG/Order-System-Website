import { eventBus } from "./eventBus.js";


export class Cart {
    cart = [];

    constructor() {
        eventBus.addEventListener("addItemToCart", ({ detail }) => {
            this.addItemToCart(detail.item);
        });
    }

    getCart() {
        return this.cart;
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
        const items = Array.from(this.cart);
        return items.find(item => item.id === id) || false;
    }
}

