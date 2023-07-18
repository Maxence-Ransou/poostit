function previewImages() {
  var fileInput = document.getElementById("files");
  var previewContainer = document.getElementById("previewContainer");
  previewContainer.innerHTML = "";

  var files = fileInput.files;

  for (var i = 0; i < files.length; i++) {
    var file = files[i];
    var reader = new FileReader();

    reader.onload = (function (file) {
      return function (e) {
        previewContainer.style.display = "flex";
        var image = document.createElement("img");
        image.className = "preview_image";
        image.src = e.target.result;
        previewContainer.appendChild(image);
      };
    })(file);

    reader.readAsDataURL(file);
  }
}
