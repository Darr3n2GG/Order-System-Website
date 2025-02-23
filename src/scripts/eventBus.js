// Global Event Manager

class EventBus extends EventTarget {
    emit(eventName, detail) {
        this.dispatchEvent(new CustomEvent(eventName, { detail }));
    }
}

// Exporting a shared instance of the EventBus Class as a Singleton.
export const eventBus = new EventBus();