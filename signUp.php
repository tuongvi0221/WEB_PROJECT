<script type="text/javascript">
window.onkeyup = function(e) {
    if (e.keyCode == 13) {
        call_to_dangky();
    }
}
</script>

<div class="container-fluid form" style="padding: 20px">
    <div class="row">
        <div class="col-lg-3"></div>
        <div class="col-lg-6">
            <form action="" method="POST" role="form">
                <legend>Đăng Ký</legend>

                <div class="form-group">
                    <label for="">Tên: </label>
                    <input type="text" class="form-control" id="name">
                </div>
                <div class="form-group">
                    <label for="">Tên tài khoản: </label>
                    <input type="text" class="form-control" id="username">
                </div>
                <div class="form-group">
                    <label for="">Mật khẩu: </label>
                    <input type="password" class="form-control" id="password">
                </div>
                <div class="form-group">
                    <label for="">Nhập lại mật khẩu: </label>
                    <input type="password" class="form-control" id="cpassword">
                </div>
                <div class="form-group">
                    <label for="">Địa chỉ: </label>
                    <input type="text" class="form-control" id="address">
                </div>
                <div class="form-group">
                    <label for="">Số điện thoại: </label>
                    <input type="text" class="form-control" id="tel">
                </div>
                <div class="form-group">
                    <label for="">Email: </label>
                    <input type="email" class="form-control" id="email">
                </div>

                <div class="form-group">
                    <label for="birthdate">Ngày sinh:</label>
                    <input type="date" class="form-control" id="birthdate">
                    <small id="birthdate-error" style="color: red; display: none;">Ngày sinh không hợp lệ. Bạn phải trên
                        14 tuổi và không được lớn hơn ngày hôm nay.</small>
                </div>

                <script>
                document.getElementById('birthdate').addEventListener('change', function() {
                    const birthdateInput = new Date(this.value);
                    const today = new Date();

                    // Tính tuổi dựa trên ngày sinh
                    const age = today.getFullYear() - birthdateInput.getFullYear();
                    const isBirthdayPassed =
                        today.getMonth() > birthdateInput.getMonth() ||
                        (today.getMonth() === birthdateInput.getMonth() && today.getDate() >= birthdateInput
                            .getDate());

                    const actualAge = isBirthdayPassed ? age : age - 1;

                    // Kiểm tra điều kiện
                    if (birthdateInput > today) {
                        showError('Ngày sinh không được lớn hơn ngày hiện tại.');
                    } else if (actualAge < 14) {
                        showError('Bạn phải trên 14 tuổi mới có thể đăng ký.');
                    } else {
                        hideError();
                    }
                });

                function showError(message) {
                    const errorElement = document.getElementById('birthdate-error');
                    errorElement.innerText = message;
                    errorElement.style.display = 'block';
                }

                function hideError() {
                    const errorElement = document.getElementById('birthdate-error');
                    errorElement.style.display = 'none';
                }
                </script>

                <div onclick="call_to_dangky()" class="btn btn-primary">Submit</div><br><br>
                <a onclick="ajax_dangnhap()" style="float: right;">Đăng nhập</a><br>
            </form>
        </div>
        <div class="col-lg-12"></div>
    </div>
</div>