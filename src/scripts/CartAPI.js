import { eventBus } from "./EventBus.js";


export class Cart {
    #cart;

    constructor() {
        this.cart = [];
        eventBus.addEventListener("addItemToCart", ({ detail }) => {
            this.updateCart(detail.item);
        });
    }

    updateCart(item) {
        if (isItemValid(item)) {
            return;
        }

        const cartItem = this.findCartItem(item.id);
        if (cartItem) {
            cartItem.kuantiti += item.kuantiti;
            eventBus.emit("ItemQuantityUpdated", { item: cartItem });
        } else {
            this.addCartItem(item);
            eventBus.emit("AddCartItem", { newItem: item });
        }

        function isItemValid(item) {
            if (!item || typeof item.id === "undefined" || typeof item.kuantiti !== "number" || item.kuantiti < 0) {
                console.error("Invalid item provided to updateCart:", item);
                return false;
            } 
            return true;
        }
    }

    getCart() {
        return this.#cart;
    }

    addCartItem({id, kuantiti}) {
        const cartItem = {
            id: id,
            kuantiti: kuantiti,
        };
        this.#cart.push(cartItem);
    }

    updateCartItem(id, kuantiti) {
        const cartItem = this.#cart.findCartItem(id)
        cartItem.kuantiti = kuantiti
    }

    deleteCartItem(id) {
        const cartItem = this.findCartItem(id);
        const index = this.cart.indexOf(cartItem);
        this.#cart.splice(index, 1);
    }

    findCartItem(id) {
        const cartItem = this.#cart.find(item => item.id === id);
        if (cartItem !== undefined) {
            return cartItem;
        }
        return false;
    }
}