export default class fetchHelper {
    static onFulfilled = (response) => {
        if (response.status !== 200 && !response.ok) {
            throw new Error(`[${response.status}] Unable to fetch resource`);
        }
        return response.json();
    }
      
    static onRejected = (err) => {
        console.error(err);
    }
}