"use strict";

// ChartJS chart instantiation and creation
var createdChartArea = document.getElementById("created_chart_area");
var createdBarChart = new Chart(createdChartArea, {
    type: 'bar',
    data: {
        labels: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
        datasets: [
            {
                label: "Users",
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)',
                    'rgba(119, 158, 203, 0.2)',
                    'rgba(130, 105, 83, 0.2)',
                    'rgba(222, 165, 164, 0.2)',
                    'rgba(225, 209, 220, 0.2)',
                    'rgba(225, 105, 97, 0.2)',
                    'rgba(207, 207, 196, 0.2)'

                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)',
                    'rgba(119, 158, 203, 1)',
                    'rgba(130, 105, 83, 1)',
                    'rgba(222, 165, 164, 1)',
                    'rgba(225, 209, 220, 1)',
                    'rgba(225, 105, 97, 1)',
                    'rgba(207, 207, 196, 1)'
                ],
                borderWidth: 1,
                data: [jan, feb, mar, apr, may, jun, jul, aug,sep, oct, nov, dec],

            }
        ]
    }
});
