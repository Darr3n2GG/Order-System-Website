import FetchHelper from "../../../scripts/FetchHelper.js";

const ApiUrl = "/Order-System-Website/src/backend/RevenueAPI.php"

const response = await fetch(ApiUrl)
    .then(FetchHelper.onFulfilled)
    .then(({ details }) => {
        console.log(details)
        return details
    })
    .catch(FetchHelper.onRejected)

const xValues = []
const yValues = [1, 2, 3, 4, 5, 6, 7]

await response.data.forEach(day => {
    day.forEach(income => {
        xValues.push(income)
    })
});

new Chart("#carta_revenue", {
    type: "line",
    data: {
        labels: xValues,
        datasets: [{
            fill: false,
            lineTension: 0,
            backgroundColor: "rgba(0,0,255,1.0)",
            borderColor: "rgba(0,0,255,0.1)",
            data: yValues
        }]
    },
    options: {
        legend: { display: false },
        scales: {
            yAxes: [{ ticks: { min: 6, max: 16 } }],
        }
    }
});