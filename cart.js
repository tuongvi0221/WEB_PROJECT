$(document).on("click", ".delete", function (e) {
    e.preventDefault();
    const masp = $(this).data("masp");
    const $productRow = $(this).closest('.d-flex'); // Lấy phần tử sản phẩm tương ứng

    if (confirm("Bạn có chắc chắn muốn xóa sản phẩm này?")) {
        $.post("backend-index.php", {
            query: "xoasanpham",
            masp: masp
        }, function (response) {
            if ($.isNumeric(response)) {
                $("#cart_count").text(response.trim());
                alert("Sản phẩm đã được xóa khỏi giỏ hàng!");

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
    }
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

            // Hiển thị thông báo thành công
            alert('Sản phẩm đã được thêm vào giỏ hàng!');
        });
    });
});

$(document).on("click", ".like-container", function (e) {
    e.preventDefault();

    const masp = $(this).data("masp");

    // Gửi yêu cầu AJAX để thêm sản phẩm vào danh sách yêu thích
    $.post("backend-index.php", { action: "addtocartlike_action", masp: masp }, function (response) {
        if ($.isNumeric(response)) {
            // Nếu phản hồi là số, cập nhật số lượng sản phẩm yêu thích
            $("#like_count").text(response.trim());

            // Thêm class 'liked' để thay đổi kiểu dáng của icon (để hiển thị trạng thái đã yêu thích)
            $(this).addClass('liked');
            
            alert("Đã thêm sản phẩm vào danh sách yêu thích!");
        } else {
            alert(response); // Hiển thị lỗi từ backend
        }
    }).fail(function () {
        alert("Có lỗi xảy ra khi thêm sản phẩm vào yêu thích!");
    });
});



