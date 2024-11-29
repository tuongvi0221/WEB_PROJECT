document.getElementById("anhchinh").addEventListener("change", function (event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            // Hiển thị preview ảnh
            const imgPreview = document.createElement("img");
            imgPreview.src = e.target.result;
            imgPreview.alt = "Hình ảnh xem trước";
            imgPreview.style.maxWidth = "100%";
            imgPreview.style.marginTop = "10px";

            // Thay thế ảnh cũ nếu có
            const parent = event.target.parentElement;
            const existingPreview = parent.querySelector("img");
            if (existingPreview) {
                parent.removeChild(existingPreview);
            }
            parent.appendChild(imgPreview);
        };
        reader.readAsDataURL(file);
    }
});

$(document).ready(function() {
    // Khi người dùng chọn một danh mục sản phẩm
    $('#category-select').change(function() {
        var categoryId = $(this).val(); // Lấy giá trị danh mục đã chọn

        // Gửi yêu cầu đến server nếu có chọn danh mục
        if (categoryId) {
            $.get('fetch_products_by_category.php', { category_id: categoryId }, function(response) {
                $('#product-list').html(response); // Hiển thị kết quả vào tbody của bảng
            });
        } else {
            $('#product-list').html(''); // Nếu không chọn danh mục, xóa danh sách sản phẩm
        }
    });
});
