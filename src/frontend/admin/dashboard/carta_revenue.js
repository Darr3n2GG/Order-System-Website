import FetchHelper from "../../../scripts/FetchHelper.js";

const ApiUrl = "/Order-System-Website/src/backend/api/RevenueAPI.php"

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

const cartaRevenueCanvas = document.getElementById("carta_revenue").getContext("2d")

const plugin = {
    id: 'customCanvasBackgroundColor',
    beforeDraw: (chart, args, options) => {
        const { ctx } = chart;
        ctx.save();
        ctx.globalCompositeOperation = 'destination-over';
        ctx.fillStyle = options.color || '#99ffff';
        ctx.fillRect(0, 0, chart.width, chart.height);
        ctx.restore();
    }
};

const CartaRevenue = new Chart(cartaRevenueCanvas, {
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
            min: 0,
            ticks: {
                stepSize: 10
            },
            legend: {
                display: false,
            }
        },

        plugins: {
            customCanvasBackgroundColor: {
                color: 'lightgray',
            }
        }
    },
    plugins: [plugin]
});