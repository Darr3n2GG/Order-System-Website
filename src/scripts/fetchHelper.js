// Helper functions when doing fetch requests

export default class FetchHelper {
    static onFulfilled = (response) => {
        if (response.status === 404) {
            throw new Error(`File not found ${response.status}.`)
        } else if (!response.ok) {
            return response.json()
                .then(err => {
                    throw new Error(err.message || `HTTP error! Status: ${response.status}`);
                });
        } else {
            return response.json();
        }
    }

    static onRejected = (error) => {
        console.error(error);
    }
}