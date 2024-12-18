
function them_sp(){
	var q = 'them_sp',
	tensp = $('#tensp').val(),
	gia = $('#gia').val(),
	chatlieu = $('#chatlieu').val(),
	mau = $('#mau').val(),
	danhcho = $('#danhcho').val(),
	khuyenmai = $('#khuyenmai').val(),
	madm = $('#madm').val(),
	anhchinh = $('#anhchinh').val();
	console.log(tensp);
	console.log(madm);
	console.log(gia);
	if(tensp == "" || gia == ""){
		alert("Tên sản phẩm và giá không được để trống!");
		return 0;
	}
	$.ajax({
		url : "for-ajax.php",
		type : "post",
		dataType:"text",
		data : {
			q, tensp, gia,chatlieu, 
			mau, danhcho, khuyenmai, madm, anhchinh
		},
		success : function (result){
			$("#sp_error").html(result);
			window.location.reload();
		}
	});
}
function xoa_sp(masp_xoa){
	var q = 'xoa_sp';
	$.ajax({
		url : "for-ajax.php",
		type : "post",
		dataType:"text",
		data : {
			q , masp_xoa
		},
		success : function (result){
			$('#sp_error').html(result);
			window.location.reload();
		}
	});
}
function them_dm(){
	var q = 'them_dm';
	var tendm = $('#tendm').val();
	var xuatsu = $('#xuatsu').val();
	if(tendm == "" || xuatsu == ""){
		alert('Không được để trống!');
		return 0;
	}
	$.ajax({
		url : "for-ajax.php",
		type : "post",
		dataType:"text",
		data : {
			q, tendm, xuatsu
		},
		success : function (result){
			$('#sp_error').html(result);
			window.location.reload();
		}
	});
}
function xoa_dm(madm_xoa){
	var q = 'xoa_dm';
	$.ajax({
		url : "for-ajax.php",
		type : "post",
		dataType:"text",
		data : {
			q , madm_xoa
		},
		success : function (result){
			$('#sp_error').html(result);
			window.location.reload();
		}
	});
}

//sap xep cac giao dich
function list_chuagh(){
	var q = 'giaodich_chuagh';
	$('#loai_gd').text("chưa hoàn tất");
	$.ajax({
		url : "for-ajax.php",
		type : "post",
		dataType:"text",
		data : {
			q
		},
		success : function (result){
			$('#tbl-giaodich-list').html(result);
		}
	});
}
function list_dagh(){
	var q = 'giaodich_dagh';
	$('#loai_gd').text("đã hoàn tất");
	$.ajax({
		url : "for-ajax.php",
		type : "post",
		dataType:"text",
		data : {
			q
		},
		success : function (result){
			$('#tbl-giaodich-list').html(result);
		}
	});
}
function list_tatcagh() {
    var q = 'giaodich_tatcagh';
    $('#loai_gd').text("Tất cả");
    $.ajax({
        url: "for-ajax.php",
        type: "POST",
        dataType: "text",
        data: {
            q: q // Thêm dấu `:` để truyền tham số q đúng cách
        },
        success: function(result) {
            $('#tbl-giaodich-list').html(result); // Cập nhật bảng
        }
    });
}


// Lưu tab đang active vào localStorage khi người dùng click vào tab
$('#admin-tabs a').on('click', function (e) {
    e.preventDefault();
    const activeTab = $(this).attr('href'); // Lấy id của tab hiện tại
    localStorage.setItem('activeTab', activeTab); // Lưu vào localStorage
    $(this).tab('show'); // Hiển thị tab
});

// Khôi phục tab khi load lại trang
$(document).ready(function () {
    const activeTab = localStorage.getItem('activeTab'); // Lấy tab từ localStorage
    if (activeTab) {
        $('#admin-tabs a[href="' + activeTab + '"]').tab('show'); // Kích hoạt tab
    }
});

$('#add-admin-form').submit(function (e) {
    e.preventDefault();
    const formData = $(this).serialize(); // Dữ liệu form

    $.post('add-admin.php', formData, function (response) {
        alert('Thêm thành viên thành công');
        $('#add-admin-area').hide(); // Ẩn form thêm admin
        $('#admin-list').load('load-admin.php'); // Load lại danh sách thành viên
        $('#admin-tabs a[href="#thanhvien"]').tab('show'); // Kích hoạt tab thành viên
    });
});


// Cập nhật thành viên xong thì giữ nguyên tab
function them_admin() {
    var q = 'them_admin';
    var ten = $('#admin-name').val();
    var tentk = $('#admin-username').val();
    var mk = $('#admin-password').val();
    var address = $('#admin-address').val();
    var phone = $('#admin-phonenumber').val();
    var email = $('#admin-email').val();

    var phonePattern = /^[0-9]{10}$/; // Kiểm tra số điện thoại có đúng định dạng
    if (!phonePattern.test(phone)) {
        alert("Số điện thoại phải gồm 10 chữ số.");
        return;
    }

    $.ajax({
        url: "for-ajax.php",
        type: "post",
        dataType: "text",
        data: {
            q, ten, tentk, mk, diachi: address, sdt: phone, email: email
        },
        success: function (result) {
			// Thông báo thành công
			alert("Thêm thành viên thành công!");
		
			// Làm mới nội dung danh sách thành viên
			$('#tbl-thanhvien-list').html(result);
		
			// Giữ nguyên tab thành viên đang active
			$('a[href="#tab-thanhvien"]').tab('show');
		},
		
        error: function () {
            alert("Có lỗi xảy ra khi thêm thành viên!");
        }
    });
}





function xoa_taikhoan(id_tk_xoa){
	var q = 'xoa_taikhoan';
	$.ajax({
		url : "for-ajax.php",
		type : "post",
		dataType:"text",
		data : {
			q, id_tk_xoa
		},
		success : function (result){
			$('#tbl-thanhvien-list').html(result);
			location.reload();
		}
	});
}
function display_edit_sanpham(masp_sua_sp){
    // Hiển thị phần sửa sản phẩm
    $('#sua_sp-area').show(300);

    // Cập nhật sự kiện click cho nút "Xong"
    $('#edit_sp_btn').attr("onclick", "sua_sp('" + masp_sua_sp + "')");

    // Cuộn trang đến phần sửa sản phẩm
    $('html, body').animate({
        scrollTop: $('#sua_sp-area').offset().top - 20 // Cuộn lên một chút để đảm bảo không bị ẩn
    }, 300); // 300ms cho hiệu ứng cuộn trang
}

// Đảm bảo khi trang đã tải xong thì gọi jQuery
$(document).ready(function() {
    // Bạn có thể kiểm tra bằng cách in ra console để xác minh
    console.log('Document is ready');
});


function sua_sp(masp_sua){
	var q = 'sua_sp';
	var tensp_ed = $('#tensp-edit').val();
	var gia_ed = $('#gia-edit').val();
	var baohanh_ed = $('#baohanh-edit').val();
	var khuyenmai_ed = $('#khuyenmai-edit').val();
	var tinhtrang_ed = $('#tinhtrang-edit').val();
	if(tensp_ed == "" && gia_ed == "" && baohanh_ed == "" && khuyenmai_ed == "" && tinhtrang_ed == ""){
		alert("Bạn phải sửa ít nhất một trường!");
		return 0;
	}
	$.ajax({
		url : "for-ajax.php",
		type : "post",
		dataType:"text",
		data : {
			q, masp_sua, tensp_ed, gia_ed, baohanh_ed, khuyenmai_ed, tinhtrang_ed
		},
		success : function (result){
			$('#big-error').html(result);
			/*location.reload();*/
		}
	});
}