﻿function load_more(current){
	var fname = 'load_more';
	var x = current+1;
	$('#loadmorebtn').attr('onclick','load_more('+x+')');
	$.ajax({
		url : "ajax_calling.php",
		type : "get",
		dataType:"text",
		data : {
			fname, current
		},
		success : function (result){
			if(result.search('Đã hết') != -1){
				alert("Đã hết mục để hiện!");
				$('#loadmorebtn').remove();
				return 0;
			}
			$('#content').append(result);
		}
	});
}
function ajax_saling(){
	$('#dgg').addClass('ajaxing');
	$('#spm').removeClass('ajaxing');
	$('#mntq').removeClass('ajaxing');
	$.ajax({
		url : "ajax_calling.php",
		type : "get",
		dataType:"text",
		data : {
			fname: 'php_saling',
		},
		success : function (result){
			$('#content').html(result);
		}
	});
}
function ajax_new(){
	$('#spm').addClass('ajaxing');
	$('#dgg').removeClass('ajaxing');
	$('#mntq').removeClass('ajaxing');
	$.ajax({
		url : "ajax_calling.php",
		type : "get",
		dataType:"text",
		data : {
			fname: 'php_new',
		},
		success : function (result){
			$('#content').html(result);
		}
	});
}
function ajax_buy(){
	$('#mntq').addClass('ajaxing');
	$('#spm').removeClass('ajaxing');
	$('#dgg').removeClass('ajaxing');
	$.ajax({
		url : "ajax_calling.php",
		type : "get",
		dataType:"text",
		data : {
			fname: 'php_buy',
		},
		success : function (result){
			$('#content').html(result);
		}
	});
}
function ajax_search(){
	$('#mntq').removeClass('ajaxing');
	$('#spm').removeClass('ajaxing');
	$('#dgg').removeClass('ajaxing');
	var x1 = $('#src-v').val();
	var x2 = $('#srch-val').val();
	var s = '';
	if(x2 == "" && x1 == ""){
		return 0;
	}
	if(x1 != ""){
		s = x1;
	} else {
		s = x2;
	}
	$.ajax({
		url : "ajax_calling.php",
		type : "get",
		dataType:"text",
		data : {
			fname: 'php_search', s
		},
		success : function (result){
			$('#content').html(result);
		}
	});
}
//Danh muc san pham
function ajax_danhmucsp(tendm){
	$('#spm').removeClass('ajaxing');
	$('#mntq').removeClass('ajaxing');
	$('#dgg').removeClass('ajaxing');
	$.ajax({
		url : "ajax_calling.php",
		type : "get",
		dataType:"text",
		data : {
			fname: 'php_dmsp',
			detail: tendm
		},
		success : function (result){
			$('#content').html(result);
		}
	});
}
function ajax_dangnhap(){
	$('#spm').removeClass('ajaxing');
	$('#mntq').removeClass('ajaxing');
	$('#dgg').removeClass('ajaxing');
	$.ajax({
		url : "ajax_calling.php",
		type : "get",
		dataType:"text",
		data : {
			fname: 'php_dangnhap'
		},
		success : function (result){
			$('#content').html(result);
		}
	});
}
function ajax_dangky(){
	$('#spm').removeClass('ajaxing');
	$('#mntq').removeClass('ajaxing');
	$('#dgg').removeClass('ajaxing');
	$.ajax({
		url : "ajax_calling.php",
		type : "get",
		dataType:"text",
		data : {
			fname: 'php_dangky'
		},
		success : function (result){
			$('#content').html(result);
		}
	});
}
function ajax_giohang(){
	$('#spm').removeClass('ajaxing');
	$('#mntq').removeClass('ajaxing');
	$('#dgg').removeClass('ajaxing');
	$.ajax({
		url : "ajax_calling.php",
		type : "get",
		dataType:"text",
		data : {
			fname: 'php_giohang'
		},
		success : function (result){
			$('#content').html(result);
		}
	});
}
function call_to_dangnhap(){
	var un = $('#username').val();
	var pw = $('#password').val();
	var isR = $('#rmbme').is(":checked");
	var query = 'dang_nhap';
	$.ajax({
		url : "backend-index.php",
		type : "post",
		dataType:"text",
		data : {
			un, pw, query, isR
		},
		success : function (result){
			$('#content').html(result);
		}
	});
}
function call_to_dangxuat(){
	var query = 'dang_xuat';
	$.ajax({
		url : "backend-index.php",
		type : "post",
		dataType:"text",
		data : {
			query
		},
		success : function (result){
			$('#content').html(result);
		}
	});
}
function call_to_thongtin(){
	var query = 'thongtin_user';
	$.ajax({
		url : "backend-index.php",
		type : "post",
		dataType:"text",
		data : {
			query
		},
		success : function (result){
			$('#content').html(result);
		}
	});
}
function call_to_dangky(){
	var query = 'dang_ky';
	var name = $('#name').val();
	var un = $('#username').val();
	var pw = $('#password').val();
	var cpw = $('#cpassword').val();
	var addr = $('#address').val();
	var tel = $('#tel').val();
	var email = $('#email').val();
	$.ajax({
		url : "backend-index.php",
		type : "post",
		dataType:"text",
		data : {
			query, name, un, pw, cpw, addr, tel, email
		},
		success : function (result){
			$('#content').html(result);
		}
	});
}

function addtocart_action(masp) {
    var query = 'addtocart_action';
    if (!$("#cart_count").hasClass('clicked')) {
        $("#cart_count").addClass('cart_count animated tada');
    } else {
        $("#cart_count").removeClass('tada').addClass('flipInX');
    }
    setTimeout(function () {
        $("#cart_count").removeClass('flipInX');
    }, 1000);
    $("#cart_count").addClass('clicked');

    $.ajax({
        url: "backend-index.php",
        type: "post",
        dataType: "text",
        data: {
            query: query,
            masp: masp // Truyền mã sản phẩm vào data AJAX
        },
        success: function (result) {
            $('#cart_count').html(result);
        }
    });
}

function xoasanpham(masp) {
    $.ajax({
        url: "backend-index.php",
        type: "post",
        data: {
            q: "xoasanpham",
            masp: masp
        },
        success: function (result) {
            alert(result); // Thông báo kết quả từ PHP
            if (result.includes("Đã xóa")) { // Chỉ tải lại nếu thành công
                location.reload(); // Tải lại trang để cập nhật giỏ hàng
            }
        },
        error: function () {
            alert("Đã xảy ra lỗi khi xóa sản phẩm.");
        }
    });
}






function hien_sanpham(masp_to_display){
	$('#modal-id').attr('data-remote','sanpham.php?masp='+masp_to_display);
	$('#modal-sanpham').empty();

	var query = 'hien_sanpham';
	$.ajax({
		url : "backend-index.php",
		type : "post",
		dataType:"text",
		data : {
			query, masp_to_display
		},
		success : function (result){
			$('#modal-sanpham').html(result);
		}
	});
}
function tinh_tien() {
    var query = 'tinh_tien';
    var sl = [];
    var array = [];
    var sum = 0;

    // Thu thập số lượng và giá của những sản phẩm được chọn (đánh dấu checkbox)
    $('input[name="selected_products[]"]:checked').each(function() {
        var row = $(this).closest(".prd-in-cart"); // Lấy dòng chứa thông tin sản phẩm

        // Lấy số lượng từ input số lượng
        var quantity = row.find('input[name="sl[]"]').val();
        sl.push(quantity);

        // Lấy giá trị từ data của phần tử có class "cost"
        var price = row.find(".cost").data("val").toString().replace(/ /g, '');
        array.push(price);
    });

    // Tính tổng tiền
    for (var i = 0; i < sl.length; i++) {
        sum += Number(array[i]) * sl[i];
    }

    // Định dạng số tiền
    sum = sum.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");

    // Cập nhật tổng tiền vào phần tử HTML
    $('#tong_tien').html(sum);
}




function check_before_submit(){
	var ten = $('#s_ten').val();
	var quan = $('#s_quan').val();
	var dc = $('#s_dc').val();
	var sdt = $('#s_sdt').val();
	var cost = $('#tong_tien').text();
	if(ten == "" || quan == "" || dc == "" || sdt == ""){
		alert("Vui lòng điền đầy đủ thông tin trước khi đặt hàng!");
		$("form").submit(function(e){
			e.preventDefault();
		});
	} else {
		$("form").submit(function(e){
			$(this).unbind('submit').submit()
		});
		
	}
}

function like_action(masp_to_like){
	if($('#s-s').data("stt") == "nosignin"){
		$('.like-error').toggle("500");
	}else{
		var query = 'like';
		if(!$("#like_count").hasClass('clicked')){
			$("#like_count").addClass('like_count');
			$("#like_count").addClass(' animated tada');
		} else {
			$("#like_count").removeClass(' tada');
			$("#like_count").addClass(' flipInX');
		}
		setTimeout(function(){$("#like_count").removeClass(' flipInX');}, 1000)
		$("#like_count").addClass('clicked');
		$.ajax({
			url : "backend-index.php",
			type : "post",
			dataType:"text",
			data : {
				query, masp_to_like
			},
			success : function (result){
				$('#like_count').html(result);
			}
		});
	}
	
}