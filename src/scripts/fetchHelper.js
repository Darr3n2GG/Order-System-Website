// Helper functions when doing fetch requests

export default class FetchHelper {
    static onFulfilled = (response) => {
        if (!response.ok) {
            return response.json()
                .then(err => {
                    throw new Error(err.message || `HTTP error! Status: ${response.status}`);
                });
        }
        return response.json();
    }
      
    static onRejected = (error) => {
        console.error(error);
    }
}