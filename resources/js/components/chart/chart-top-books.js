export const initChartTopBooks = () => {
    const chartElement = document.querySelector('#topBooksChart');
    if (!chartElement) return;

    // Ganti data manual (seperti Chart.js)
    const labels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul'];
    const values = [65, 59, 80, 81, 56, 55, 40];

    const options = {
        series: [{
            name: 'My First Dataset',
            data: values,
        }],
        chart: {
            type: 'area',
            height: 350,
            toolbar: { show: false }
        },
        stroke: {
            curve: 'smooth',
            width: 3
        },
        xaxis: {
            categories: labels
        },
        colors: ['#4bc0c0'], // warna Chart.js style
        fill: {
            type: 'gradient',
            gradient: {
                opacityFrom: 0.4,
                opacityTo: 0.1
            }
        }
    };
    

    const chart = new ApexCharts(chartElement, options);
    chart.render();
};