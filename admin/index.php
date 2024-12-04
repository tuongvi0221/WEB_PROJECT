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
        background-color: blueviolet;
        /* Màu nền nút */
    }

    .button-doanhthutheocacnam {
        background-color: chocolate;
        /* Màu nền nút */
    }

    .button-thongkekhachhang {
        background-color: blue;
        /* Màu nền nút */
    }


    /* Định dạng button */
    .button {
        display: flex;
        /* Sử dụng flexbox để căn chỉnh các button theo chiều ngang */
        justify-content: center;
        /* Căn giữa các button */
        gap: 20px;
        /* Khoảng cách giữa các button */
        margin-top: 20px;
        /* Khoảng cách phía trên của button */
    }

    .button button {
        padding: 20px 20px;
        font-size: 16px;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .button button:hover {
        background-color: beige;
    }


    #topCustomersTable {
        width: 100%;
        border-collapse: collapse;
        /* Làm cho các đường viền không bị gộp vào nhau */
        margin: 50px 0;
        /* Khoảng cách từ trên và dưới */
        font-family: Arial, sans-serif;
        /* Phông chữ đẹp */
    }

    /* Định dạng phần đầu bảng */
    #topCustomersTable thead {
        background-color: #4CAF50;
        /* Màu nền của phần đầu bảng */
        color: white;
        /* Màu chữ */
        text-align: center;
        /* Canh giữa các cột */
    }

    /* Định dạng phần thân bảng */
    #topCustomersTable tbody tr:nth-child(even) {
        background-color: #f2f2f2;
        /* Màu nền cho các dòng chẵn */
    }

    #topCustomersTable tbody tr:hover {
        background-color: #ddd;
        /* Màu nền khi hover qua dòng */
    }

    /* Định dạng các ô trong bảng */
    #topCustomersTable th,
    #topCustomersTable td {
        padding: 12px 15px;
        /* Khoảng cách từ viền ô đến nội dung */
        text-align: center;
        /* Canh giữa các ô */
        border: 1px solid #ddd;
        /* Đường viền của bảng */
    }

    /* Định dạng đường viền của bảng */
    #topCustomersTable {
        border: 2px solid #4CAF50;
        /* Đường viền ngoài của bảng */
        border-radius: 10px;
        /* Bo góc cho bảng */
    }
    </style>
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>


</head>

<body>

    <div class="button">
        <button class="button-doanhthu">Thống kê doanh thu</button>
        <button class="button-sanpham">Thống kê sản phẩm bán chạy</button>
        <button class="button-doanhthutheocacnam">Thống kê doanh thu theo năm</button>
        <button class="button-thongkekhachhang">Thống kê doanh số của khách hàng</button>
    </div>


    <div id="chart-container">
        <canvas id="graph"></canvas> <!-- Biểu đồ đường -->
        <canvas id="topProductsChart"></canvas> <!-- Biểu đồ cột -->
        <canvas id="doanhthutheocacnam"></canvas> <!-- Biểu đồ cột -->
    </div>

    <div>
        <table id="topCustomersTable">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Tên khách hàng</th>
                    <th>Tổng doanh thu</th>
                </tr>
            </thead>
            <tbody>
                <!-- Dữ liệu khách hàng sẽ được chèn vào đây -->
            </tbody>
        </table>


    </div>
    <script>
    $('.button-sanpham').click(function() {
        showBar();

    });

    $('.button-doanhthu').click(function() {
        showGraph();

    });

    $('.button-doanhthutheocacnam').click(function() {
        showPie();

    });

    $('.button-thongkekhachhang').click(function() {
        showTopCustomers();

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


    function showPie() {
        $.ajax({
            url: "thongkeDoanhSoCacNam.php", // Đường dẫn tới file PHP trả về dữ liệu
            method: "GET",
            dataType: "json",
            success: function(data) {
                // Lấy dữ liệu từ JSON trả về
                const years = data.map(item => item.year);
                const sales = data.map(item => item.total_sales);

                // Kiểm tra nếu biểu đồ đã tồn tại, hủy nó trước khi vẽ biểu đồ mới
                if (window.pieChart) {
                    window.pieChart.destroy();
                }

                if (window.myChart) {
                    window.myChart.destroy(); // Hủy biểu đồ cũ
                }


                // Cấu hình và vẽ biểu đồ
                const ctx = document.getElementById('doanhthutheocacnam').getContext('2d');
                window.pieChart = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: years, // Các năm
                        datasets: [{
                            label: 'Doanh số (VND)',
                            data: sales, // Doanh số
                            backgroundColor: [
                                '#FF6384',
                                '#36A2EB',
                                '#FFCE56',
                                '#4BC0C0',
                                '#9966FF'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top' // Hiển thị legend ở phía trên
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(tooltipItem) {
                                        const currentValue = tooltipItem.raw;
                                        return `${tooltipItem.label}: ${currentValue.toLocaleString()} VND`;
                                    }
                                }
                            }
                        }
                    }
                });
            },
            error: function(xhr, status, error) {
                console.error("Có lỗi xảy ra: ", error);
            }
        });
    }



    function showTopCustomers() {
        $.ajax({
            url: "thongkekhachhang.php", // Đường dẫn tới file PHP
            method: "GET",
            dataType: "json",
            success: function(data) {
                let tableContent = '';
                data.forEach(function(customer, index) {
                    tableContent += `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${customer.user_name}</td>
                        <td>${customer.total_spent.toLocaleString()} VND</td>
                    </tr>
                `;
                });

                $('#topCustomersTable tbody').html(tableContent); // Thêm dữ liệu vào bảng
            },
            error: function(xhr, status, error) {
                console.error("Có lỗi xảy ra: ", error);
            }
        });
    }
    </script>
</body>

</html>