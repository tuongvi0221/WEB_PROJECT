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
