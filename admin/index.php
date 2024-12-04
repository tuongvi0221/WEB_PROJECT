<!DOCTYPE html>
<html>

<head>
    <title>Tạo biểu đồ sử dụng PHP và Chart.js</title>
    <style type="text/css">
    BODY {
        width: 550PX;
    }


    /* Định dạng phần mỗi "button" (biểu đồ hoặc bảng) */
    .button {
        display: flex;
        flex-direction: column;
        /* Sắp xếp theo cột trong từng phần */
        align-items: center;
        /* Căn giữa nội dung */
        width: 100%;
        /* Tăng chiều rộng của khung */
        padding: 50px;
        /* Tăng khoảng cách bên trong khung */
        margin: 20px;
        /* Khoảng cách giữa các khung */
        border: 2px solid #ccc;
        /* Đường viền lớn hơn */
        border-radius: 10px;
        /* Tăng bo tròn viền */
        background-color: #f9f9f9;
        /* Màu nền nhạt */
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        /* Tăng bóng đổ cho khung */
    }


    /* Định dạng button */
    .button button {
        min-width: 150px;
        /* Chiều rộng tối thiểu của nút */
        padding: 15px;
        font-size: 16px;
        color: white;
        background-color: #007BFF;
        /* Màu xanh dương */
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .button button:hover {
        background-color: #0056b3;
        /* Màu đậm hơn khi hover */
    }

    /* Định dạng canvas (biểu đồ) */
    canvas {
        width: 500px;
        /* Chiều rộng cố định của biểu đồ */
        height: 200px;
        /* Chiều cao cố định của biểu đồ */
        border: 1px solid #ddd;
        border-radius: 8px;
        background-color: white;
        padding: 10px;
    }

    /* Định dạng bảng thống kê */
    #topCustomersTable {
        width: 100%;
        border-collapse: collapse;
        /* Loại bỏ khoảng cách giữa các ô */
        margin-top: 20px;
    }

    #topCustomersTable th,
    #topCustomersTable td {
        border: 1px solid #ccc;
        text-align: center;
        padding: 10px;
        font-size: 14px;
    }

    #topCustomersTable th {
        background-color: #007BFF;
        color: white;
    }

    #topCustomersTable tbody tr:nth-child(odd) {
        background-color: #f9f9f9;
    }

    #topCustomersTable tbody tr:nth-child(even) {
        background-color: #e9ecef;
    }

    /* Định dạng phần chứa bảng */
    #topCustomersTable {
        max-width: 300px;
        /* Giới hạn chiều rộng bảng */
        margin: 0 auto;
    }

    .year-combo {
        margin-left: 10px;
        /* Khoảng cách bên trái */
        padding: 5px 10px;
        /* Đệm bên trong */
        font-size: 16px;
        /* Kích thước chữ */
        border: 1px solid #ccc;
        /* Viền mờ */
        border-radius: 5px;
        /* Bo góc */
    }
    </style>
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
    <script type="javascript" src="admin/script.js"></script>

</head>

<body>

    <div class="button">
        <button class="button-doanhthu">Thống kê doanh thu</button>
        <select id="yearDropdown" class="year-combo">
            <!-- Các năm sẽ được chèn vào đây bằng JavaScript -->
        </select>
        <canvas id="graph"></canvas> <!-- Biểu đồ đường -->
    </div>



    <div class="button">
        <button class="button-sanpham">Thống kê sản phẩm bán chạy</button>
        <canvas id="topProductsChart"></canvas> <!-- Biểu đồ cột -->
    </div>


    <div class="button">
        <button class="button-doanhthutheocacnam">Thống kê doanh thu theo năm</button>
        <canvas id="doanhthutheocacnam"></canvas> <!-- Biểu đồ cột -->
    </div>


    <div class="button">
        <button class="button-thongkekhachhang">Thống kê doanh số của khách hàng</button>
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
    </div>

    <script>
    showYear();

    // Sự kiện khi chọn năm từ combobox
    $('#yearDropdown').change(function() {
        const selectedYear = $(this).val(); // Lấy năm được chọn
        if (selectedYear) {
            showGraph(selectedYear); // Hiển thị biểu đồ theo năm
        }
    });

    // Sự kiện khi click vào nút "Thống kê doanh thu"
    $('.button-doanhthu').click(function() {
        const selectedYear = $('#yearDropdown').val(); // Lấy năm được chọn từ combobox
        if (selectedYear) {
            showGraph(selectedYear); // Hiển thị biểu đồ theo năm
        }
    });


    $('.button-sanpham').click(function() {
        showBar();

    });



    $('.button-doanhthutheocacnam').click(function() {
        showPie();

    });

    $('.button-thongkekhachhang').click(function() {
        $('#topCustomersTable').show();
        showTopCustomers();


    });

    $('#topCustomersTable').hide();

    function showYear() {
        $.ajax({
            url: "layDanhSachNam.php", // Endpoint PHP để lấy danh sách năm
            method: "GET",
            dataType: "json",
            success: function(data) {
                const yearDropdown = $('#yearDropdown');

                // Xóa các tùy chọn cũ (nếu có)
                yearDropdown.empty();

                // Thêm tùy chọn mặc định
                yearDropdown.append('<option value="">Chọn năm</option>');

                // Duyệt qua dữ liệu trả về để thêm các năm vào dropdown
                data.forEach(function(year) {
                    yearDropdown.append(`<option value="${year}">${year}</option>`);
                });
            },
            error: function(xhr, status, error) {
                console.error("Có lỗi xảy ra khi lấy danh sách năm:", error);
            }
        });
    }

    // Hàm hiển thị biểu đồ doanh thu
    function showGraph(selectedYear) {
        $.ajax({
            url: "doanhthu.php", // Đường dẫn tới file PHP xử lý
            method: "GET",
            data: {
                year: selectedYear
            }, // Gửi năm được chọn tới server
            dataType: "json",
            success: function(data) {
                var months = data.months;
                var totals = data.totals;

                // Kiểm tra nếu biểu đồ đã tồn tại thì hủy nó
                if (window.myChart) {
                    window.myChart.destroy();
                }

                var ctx = document.getElementById('graph').getContext('2d');

                window.myChart = new Chart(ctx, {
                    type: 'line', // Biểu đồ đường
                    data: {
                        labels: months,
                        datasets: [{
                            label: `Tổng tiền giao dịch năm ${selectedYear} (VND)`,
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

                // Kiểm tra nếu biểu đồ đã tồn tại thì hủy nó
                if (window.myChart) {
                    window.myChart.destroy();
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

                // Kiểm tra nếu biểu đồ đã tồn tại thì hủy nó
                if (window.myChart) {
                    window.myChart.destroy();
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