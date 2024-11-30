<!DOCTYPE html>
<html>

<head>
    <title>Tạo biểu đồ sử dụng PHP và Chart.js</title>
    <style type="text/css">
    BODY {
        width: 550PX;
    }

    #chart-container {
        width: 100%;
        height: auto;
    }
    </style>
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body>
    <div id="chart-container">
        <canvas id="graph"></canvas>
    </div>

    <script>
    $(document).ready(function() {
        showGraph(); // Gọi hàm vẽ biểu đồ khi trang tải
    });

    function showGraph() {
        $.ajax({
            url: "data.php", // Đường dẫn đến file data.php
            method: "GET", // Phương thức GET để lấy dữ liệu
            dataType: "json", // Kiểu dữ liệu trả về là JSON
            success: function(data) {
                var months = data.months; // Mảng tháng/năm
                var totals = data.totals; // Mảng tổng tiền

                // Kiểm tra nếu đã có biểu đồ, hủy biểu đồ cũ đi
                if (window.myChart) {
                    window.myChart.destroy();
                }

                // Lấy đối tượng canvas
                var ctx = document.getElementById('graph').getContext('2d');

                // Tạo một biểu đồ mới với dữ liệu nhận được
                window.myChart = new Chart(ctx, {
                    type: 'line', // Kiểu biểu đồ là đường
                    data: {
                        labels: months, // Nhãn biểu đồ là các tháng/năm
                        datasets: [{
                            label: 'Tổng tiền giao dịch (VND)', // Nhãn cho dòng dữ liệu
                            data: totals, // Dữ liệu tổng tiền
                            borderColor: 'rgba(75, 192, 192, 1)', // Màu đường viền
                            backgroundColor: 'rgba(75, 192, 192, 0.2)', // Màu nền
                            fill: true, // Điền màu dưới đường
                            tension: 0.1 // Độ cong của đường
                        }]
                    },
                    options: {
                        responsive: true, // Biểu đồ responsive theo kích thước màn hình
                        scales: {
                            y: {
                                beginAtZero: true, // Bắt đầu từ 0
                                ticks: {
                                    callback: function(value) {
                                        return value.toLocaleString(); // Định dạng số tiền
                                    }
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: true // Hiển thị legend
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(tooltipItem) {
                                        return tooltipItem.raw.toLocaleString() +
                                            ' VND'; // Hiển thị tooltip với đơn vị
                                    }
                                }
                            }
                        }
                    }
                });
            },
            error: function(xhr, status, error) {
                console.error("Có lỗi xảy ra khi tải dữ liệu: ", error);
            }
        });
    }
    </script>
</body>

</html>