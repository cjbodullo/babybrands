(function () {
    var fileInput = document.getElementById("winner_photo");
    var fileNameLabel = document.getElementById("upload-filename");
    var uploadBox = document.getElementById("upload-box");
    var uploadError = document.getElementById("upload-error");
    var uploadPreview = document.getElementById("upload-preview");
    var phoneInput = document.getElementById("phone");
    var form = document.getElementById("winner-photo-release-form");
    var MAX_FILE_SIZE = 10 * 1024 * 1024;
    var ALLOWED_TYPES = ["image/png", "image/jpeg", "image/jpg"];

    if (!form) {
        return;
    }

    function showUploadError(message) {
        if (!uploadError) {
            return;
        }
        uploadError.textContent = message || "";
    }

    function clearPreview() {
        if (uploadPreview) {
            uploadPreview.style.display = "none";
            uploadPreview.removeAttribute("src");
        }
    }

    function showPreview(file) {
        if (!uploadPreview) {
            return;
        }

        var reader = new FileReader();
        reader.onload = function (event) {
            uploadPreview.src = event.target.result;
            uploadPreview.style.display = "block";
        };
        reader.readAsDataURL(file);
    }

    function isValidFile(file) {
        if (!file) {
            return "Please select an image file.";
        }
        if (ALLOWED_TYPES.indexOf(file.type) === -1) {
            return "Only PNG, JPG, or JPEG files are allowed.";
        }
        if (file.size > MAX_FILE_SIZE) {
            return "File is too large. Maximum size is 10MB.";
        }
        return "";
    }

    function setSelectedFile(file) {
        var validationMessage = isValidFile(file);

        if (validationMessage) {
            fileInput.value = "";
            fileInput.setCustomValidity(validationMessage);
            fileNameLabel.textContent = "No file selected";
            clearPreview();
            showUploadError(validationMessage);
            return false;
        }

        fileInput.setCustomValidity("");
        fileNameLabel.textContent = file.name;
        showUploadError("");
        showPreview(file);
        return true;
    }

    if (fileInput && fileNameLabel && uploadBox) {
        fileInput.addEventListener("change", function () {
            if (!fileInput.files || !fileInput.files.length) {
                fileNameLabel.textContent = "No file selected";
                clearPreview();
                showUploadError("");
                return;
            }
            setSelectedFile(fileInput.files[0]);
        });

        uploadBox.addEventListener("dragover", function (event) {
            event.preventDefault();
            uploadBox.classList.add("is-dragging");
        });

        uploadBox.addEventListener("dragleave", function () {
            uploadBox.classList.remove("is-dragging");
        });

        uploadBox.addEventListener("drop", function (event) {
            event.preventDefault();
            uploadBox.classList.remove("is-dragging");

            if (!event.dataTransfer || !event.dataTransfer.files || !event.dataTransfer.files.length) {
                return;
            }

            var droppedFile = event.dataTransfer.files[0];
            if (!setSelectedFile(droppedFile)) {
                return;
            }

            var dt = new DataTransfer();
            dt.items.add(droppedFile);
            fileInput.files = dt.files;
        });
    }

    if (phoneInput) {
        phoneInput.addEventListener("input", function () {
            var digits = phoneInput.value.replace(/\D/g, "").slice(0, 10);

            if (digits.length <= 3) {
                phoneInput.value = digits;
            } else if (digits.length <= 6) {
                phoneInput.value = "(" + digits.slice(0, 3) + ") " + digits.slice(3);
            } else {
                phoneInput.value = "(" + digits.slice(0, 3) + ") " + digits.slice(3, 6) + "-" + digits.slice(6);
            }
        });
    }

    form.addEventListener("submit", function () {
        if (fileInput && fileInput.files && fileInput.files.length) {
            var message = isValidFile(fileInput.files[0]);
            fileInput.setCustomValidity(message);
            showUploadError(message);
        }
    });
})();
