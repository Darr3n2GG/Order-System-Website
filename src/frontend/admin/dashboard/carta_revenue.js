import FetchHelper from "../../../scripts/FetchHelper.js";

const ApiUrl = "/Order-System-Website/src/backend/RevenueAPI.php"

const response = await fetch(ApiUrl)
    .then(FetchHelper.onFulfilled)
    .then(({ details }) => {
        return details
    })
    .catch(FetchHelper.onRejected)

// The array is arranged like this: [sunday, monday, tuesday, wednesday, thursday, friday, saturday]
const xValues = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"]
const yValues = []

await response.data.forEach(incomeByDay => {
    yValues.push(incomeByDay)
});

const carta_revenue = document.getElementById("carta_revenue")
new Chart(carta_revenue, {
    type: "line",
    data: {
        labels: xValues,
        datasets: [{
            labels: "Income",
            backgroundColor: "rgba(0,0,255,1.0)",
            data: yValues
        }]
    },
    options: {
        responsive: true,
        x: {
            title: {
                display: true,
                text: "Days",
                padding: { top: 20, left: 0, right: 0, bottom: 0 }
            }
        },
        y: {
            title: {
                display: true,
                text: "Revenue ($)",
                font: {
                    family: 'Times',
                    size: 20,
                    style: 'normal',
                    lineHeight: 1.2
                },
                font: {
                    family: 'Comic Sans MS',
                    size: 20,
                    weight: 'bold',
                    lineHeight: 1.2,
                },
                padding: { top: 20, left: 0, right: 0, bottom: 0 }
            },
            beginAtZero: true,
            ticks: {
                stepSize: 10
            }
        }
    }
});