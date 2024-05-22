import ApexCharts from 'apexcharts';
alert(typeof ApexCharts)
        // Sample data for the chart
        const seriesData = [30, 40, 35, 50, 49, 60, 70, 91, 125];

        // Configuration options for the chart
        const chartOptions = {
            chart: {
                type: 'line', // Specify the type of chart (e.g., line, bar, pie)
                height: 350 // Set the height of the chart
            },
            series: [{
                name: 'Sample Series', // Specify the name of the series
                data: seriesData // Provide the data for the series
            }],
            xaxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep'] // Set the categories for the X-axis
            }
        };

        // Initialize the chart with the specified options and data
        const chart = new ApexCharts(document.querySelector('#chart'), chartOptions);

        // Render the chart
        chart.render();