import { eventBus } from "./EventBus.js";


export class Cart {
    cart = [];

    constructor() {
        eventBus.addEventListener("addItemToCart", ({ detail }) => {
            this.updateCart(detail.item);
        });
    }

    getCart() {
        return this.cart;
    }

    updateCartItemQuantity(id, value) {
        const cartItem = this.findCartItemByID(id);
        cartItem.kuantiti = value;
    }

    updateCart(item) {
        const cartItem = this.findCartItemByID(item.id);
        if (cartItem) {
            cartItem.kuantiti += item.kuantiti;
            eventBus.emit("updateItemQuantityInCartDialog", {
                item: cartItem
            });
        } else {
            this.addItemToCart(item);
            eventBus.emit("addItemToCartDialog", {
                newItem: item
            });
        }
    }

    addItemToCart(item) {
        const cartItem = {
            id: item.id,
            kuantiti: item.kuantiti,
        };
        this.cart.push(cartItem);
    }

    deleteItem(id) {
        const cartItem = this.findCartItemByID(id);
        const index = this.cart.indexOf(cartItem);
        this.cart.splice(index, 1);
    }

    findCartItemByID(id) {
        const cartItem = this.cart.find(item => item.id === id);
        console.log(cartItem)
        if (cartItem !== undefined) {
            return cartItem;
        }
        return false;
    }
}

