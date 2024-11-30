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

    /* Chỉnh cho div chứa các nút */
    .button {
        text-align: center;
        margin-top: 30px;
        /* Khoảng cách từ trên xuống */
    }

    /* Chỉnh cho các nút */
    .button button {
        padding: 15px 30px;
        /* Khoảng cách trong nút */
        font-size: 18px;
        /* Kích thước chữ */
        color: white;
        /* Màu chữ */
        border: none;
        /* Xóa viền */
        border-radius: 30px;
        /* Bo tròn các góc */
        cursor: pointer;
        /* Thay đổi con trỏ khi hover */
        margin: 10px;
        /* Khoảng cách giữa các nút */
        transition: transform 0.3s ease, background-color 0.3s ease;
        /* Hiệu ứng chuyển màu nền và phóng to khi hover */
    }

    /* Nút thống kê doanh thu */
    .button-doanhthu {
        background-color: #4CAF50;
        /* Màu nền nút */
    }

    /* Nút thống kê sản phẩm bán chạy */
    .button-sanpham {
        background-color: #008CBA;
        /* Màu nền nút */
    }

    /* Hiệu ứng khi hover vào nút */
    .button button:hover {
        background-color: #45a049;
        /* Thay đổi màu nền khi hover */
        transform: scale(1.1);
        /* Phóng to nút */
    }

    /* Hiệu ứng khi hover vào nút thống kê sản phẩm */
    .button .button-sanpham:hover {
        background-color: #007bb5;
        /* Màu nền khi hover */
    }
    </style>
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>

    <div class="button">
        <button class="button-doanhthu">Thống kê doanh thu</button>
        <button class="button-sanpham">Thống kê sản phẩm bán chạy</button>
    </div>

    <div id="chart-container">
        <canvas id="graph"></canvas> <!-- Biểu đồ đường -->
        <canvas id="topProductsChart"></canvas> <!-- Biểu đồ cột -->
    </div>

    <script>
    $(document).ready(function() {
        showGraph();

    });

    $('.button-sanpham').click(function() {
        showBar();
    });

    $('.button-doanhthu').click(function() {
        showGraph();
    });

    function showGraph() {
        $.ajax({
            url: "doanhthu.php", // Đường dẫn đến file doanh thu PHP
            method: "GET", // Phương thức GET
            dataType: "json", // Kiểu dữ liệu trả về là JSON
            success: function(data) {
                var months = data.months;
                var totals = data.totals;

                if (window.myChart) {
                    window.myChart.destroy(); // Hủy biểu đồ cũ
                }

                var ctx = document.getElementById('graph').getContext('2d');

                window.myChart = new Chart(ctx, {
                    type: 'line', // Biểu đồ đường
                    data: {
                        labels: months,
                        datasets: [{
                            label: 'Tổng tiền giao dịch (VND)',
                            data: totals,
                            borderColor: 'rgba(75, 192, 192, 1)',
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            fill: true,
                            tension: 0.1
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return value.toLocaleString();
                                    }
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: true
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(tooltipItem) {
                                        return tooltipItem.raw.toLocaleString() + ' VND';
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

    function showBar() {
        $.ajax({
            url: "getTopProducts.php", // Đường dẫn đến file lấy dữ liệu sản phẩm
            method: "GET",
            dataType: "json",
            success: function(data) {
                var months = data.map(function(item) {
                    return item.ten_san_pham; // Tên sản phẩm
                });
                var totals = data.map(function(item) {
                    return item.tong_so_luong_ban; // Tổng số lượng bán
                });

                if (window.myChart) {
                    window.myChart.destroy(); // Hủy biểu đồ cũ
                }

                var ctx = document.getElementById('topProductsChart').getContext('2d');

                window.myChart = new Chart(ctx, {
                    type: 'bar', // Biểu đồ cột
                    data: {
                        labels: months,
                        datasets: [{
                            label: 'Tổng số lượng bán',
                            data: totals,
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return value.toLocaleString();
                                    }
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: true
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(tooltipItem) {
                                        return tooltipItem.raw.toLocaleString() +
                                            ' sản phẩm';
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