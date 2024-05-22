

const chart = (element, data, type) => {

  const node = document.querySelector(element);

    const gradesData = {
      labels: Object.keys(data),
      datasets: [{
        label: node.getAttribute('data-label'),
        data: Object.values(data), 
        backgroundColor: [
          'rgba(37,  99, 235, .5)',
          'rgba(147, 51, 234, .5)',
          'rgba(22,  163, 74, .5)',
          'rgba(234,  88, 12, .5)',
          'rgba(220,  38, 38, .5)',


          'rgba(255, 99, 132, 0.5)',
          // 'rgba(54, 162, 235, 0.5)',
          // 'rgba(255, 206, 86, 0.5)',
          // 'rgba(75, 192, 192, 0.5)', 
          // 'rgba(153, 102, 255, 0.5)',
          // 'rgba(255, 159, 64, 0.5)' 
        ],
        borderWidth: 1
      }]
    };

    const chartOptions = {
      responsive: true, // Set to true for a responsive chart
      maintainAspectRatio: false // Set to true to maintain aspect ratio
    };

    
    if (type === 'bar') {
      chartOptions.scales = {
        y: {
          beginAtZero: true // Start y-axis at zero
        }
      }
    }

    // Get the canvas element
    const ctx = node.getContext('2d');

    return new Chart(ctx, {
      type: type,
      data: gradesData,
      options: chartOptions
    });
}

// <!DOCTYPE html>
// <html lang="en">
// <head>
//   <meta charset="UTF-8">
//   <meta name="viewport" content="width=device-width, initial-scale=1.0">
//   <title>Grade Distribution Bar Chart</title>
//   <!-- Include Chart.js from CDN -->
//   <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
// </head>
// <body>
//   <canvas id="gradeChart" width="400" height="400"></canvas>

//   <script>
//     // Function to create a reusable chart
//     function createChart(canvasId, data, chartType) {
//       // Configuration options
//       const chartOptions = {
//         responsive: false, // Set to true for a responsive chart
//         maintainAspectRatio: false, // Set to true to maintain aspect ratio
//         scales: {
//           y: {
//             beginAtZero: true // Start y-axis at zero
//           }
//         }
//       };

//       // Get the canvas element
//       const ctx = document.getElementById(canvasId).getContext('2d');

//       // Create the chart
//       return new Chart(ctx, {
//         type: chartType,
//         data: {
//           labels: Object.keys(data),
//           datasets: [{
//             label: 'Grade Distribution',
//             data: Object.values(data), // Use values from the data object
//             backgroundColor: [
//               'rgba(255, 99, 132, 0.5)', // Red for A
//               'rgba(54, 162, 235, 0.5)', // Blue for B
//               'rgba(255, 206, 86, 0.5)', // Yellow for C
//               'rgba(75, 192, 192, 0.5)', // Green for D
//               'rgba(153, 102, 255, 0.5)', // Purple for E
//               'rgba(255, 159, 64, 0.5)' // Orange for F
//             ],
//             borderWidth: 1
//           }]
//         },
//         options: chartOptions
//       });
//     }

//     // Call the function to create the chart
//     createChart('gradeChart', {A: 4, B: 3, C: 10, D:20, E:5, F:2}, 'bar');
//   </script>
// </body>
// </html>
