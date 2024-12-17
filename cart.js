$(document).on("click", ".delete", function (e) {
    e.preventDefault();
    const masp = $(this).data("masp");
    const $productRow = $(this).closest('.d-flex'); // Lấy phần tử sản phẩm tương ứng

   
        $.post("backend-index.php", {
            query: "xoasanpham",
            masp: masp
        }, function (response) {
            if ($.isNumeric(response)) {
                $("#cart_count").text(response.trim());
               

                // Xóa sản phẩm khỏi giao diện
                $productRow.remove();

                // Kiểm tra giỏ hàng có trống không
                if ($("#content .d-flex").length === 0) {
                    $("#content").html("<div class='alert alert-warning text-center'>Giỏ hàng của bạn đang trống!</div>");
                }
            } else {
                alert(response);
            }
        }).fail(function () {
            alert("Có lỗi xảy ra khi xóa sản phẩm!");
        });
    
});



$(document).ready(function () {
    // Gắn sự kiện click vào nút thêm vào giỏ hàng
    $('.cart-container').on('click', function (e) {
        e.preventDefault(); // Ngăn hành động mặc định của thẻ a

        // Lấy mã sản phẩm từ thuộc tính data-masp
        let masp = $(this).data('masp');

        // Gửi yêu cầu POST để thêm sản phẩm vào giỏ hàng
        $.post('backend-index.php', { action: 'addtocart_action', masp: masp }, function (response) {
            // Cập nhật số lượng giỏ hàng dựa trên phản hồi
            $('#cart_count').html(response);

        });
    });
});


$(document).ready(function () {
    // Lấy số lượng sản phẩm trong giỏ hàng
    $.get('backend-index.php', { action: 'get_cart_count' }, function (response) {
        $('#cart_count').html(response);
    });
});