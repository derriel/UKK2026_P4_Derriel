export const initChartTopBooks = () => {
    const chartElement = document.querySelector('#topBooksChart');
    if (!chartElement) return;

    const labels = JSON.parse(chartElement.dataset.labels || '[]');
    const values = JSON.parse(chartElement.dataset.values || '[]');

    const options = {
        series: [{
            name: 'Jumlah Peminjaman',
            data: values,
        }],
        // Mengubah bar menjadi area agar seperti gunung
        chart: {
            type: 'area',
            height: 350,
            fontFamily: 'Plus Jakarta Sans, sans-serif',
            toolbar: {
                show: false,
            },
            sparkline: {
                enabled: false,
            },
            zoom: {
                enabled: false,
            },
            dropShadow: {
                enabled: true,
                top: 10,
                left: 0,
                blur: 3,
                color: '#3b82f6',
                opacity: 0.1
            }
        },
        // Membuat garis melengkung (smooth) bukan patah-patah
        stroke: {
            curve: 'smooth',
            width: 3,
            colors: ['#3b82f6']
        },
        // Efek warna di bawah garis (gunung)
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.45,
                opacityTo: 0.05,
                stops: [20, 100, 100],
                colorStops: [
                    {
                        offset: 0,
                        color: "#3b82f6",
                        opacity: 0.4
                    },
                    {
                        offset: 100,
                        color: "#3b82f6",
                        opacity: 0
                    }
                ]
            }
        },
        markers: {
            size: 5,
            colors: ['#3b82f6'],
            strokeColors: '#fff',
            strokeWidth: 2,
            hover: {
                size: 7,
            }
        },
        dataLabels: {
            enabled: false,
        },
        grid: {
            show: true,
            borderColor: '#f1f5f9',
            strokeDashArray: 4,
            padding: {
                left: 20,
                right: 20
            }
        },
        xaxis: {
            categories: labels,
            axisBorder: {
                show: false,
            },
            axisTicks: {
                show: false,
            },
            labels: {
                style: {
                    colors: '#94a3b8',
                    fontSize: '12px',
                    fontWeight: 500
                },
            },
        },
        yaxis: {
            labels: {
                style: {
                    colors: '#94a3b8',
                    fontSize: '12px',
                },
            },
        },
        tooltip: {
            x: {
                show: true
            },
            y: {
                formatter: function (val) {
                    return val + ' Peminjaman';
                },
            },
            theme: 'light',
        },
        colors: ['#3b82f6'],
    };

    const chart = new ApexCharts(chartElement, options);
    chart.render();

    return chart;
};

export default initChartTopBooks;