

const dropZone = document.getElementById("dropZone");
const fileInput = document.getElementById("fileInput");

if (dropZone) {
    // Prevent default drag behaviors
    ["dragenter", "dragover", "dragleave", "drop"].forEach((eventName) => {
        dropZone.addEventListener(eventName, preventDefaults, false);
    });

    // Highlight drop zone when file is dragged over it
    ["dragenter", "dragover"].forEach((eventName) => {
        dropZone.addEventListener(eventName, highlight, false);
    });

    // Unhighlight drop zone when file is dragged out of it
    ["dragleave", "drop"].forEach((eventName) => {
        dropZone.addEventListener(eventName, unhighlight, false);
    });

    // Handle dropped files
    dropZone.addEventListener("drop", handleDrop, false);

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    function highlight() {
        dropZone.classList.add("highlight");
    }

    function unhighlight() {
        dropZone.classList.remove("highlight");
    }

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;

        if (files.length > 1) {
            alert("Please only upload one image");
            return;
        }

        handleFiles(files);
    }

    function handleFiles(files) {
        const file = files[0];
        uploadFile(file);
    }

    function uploadFile(file) {
        const reader = new FileReader();

        reader.onload = function (e) {
            const dataURL = e.target.result;
            appendFileInput(dataURL);
        };

        reader.readAsDataURL(file);
    }

    function appendFileInput(dataURL) {
        // Remove any existing image
        const existingImage = dropZone.querySelector("img");
        if (existingImage) {
            existingImage.src = dataURL;
        } else {
            const image = document.createElement("img");
            image.src = dataURL;
            image.classList.add("img");
            dropZone.appendChild(image);
        }

        const hiddenInput = document.createElement("input");
        hiddenInput.type = "hidden";
        hiddenInput.name = "image"; // This will send the image data to the server under 'image' key
        hiddenInput.value = dataURL;
        dropZone.appendChild(hiddenInput);
    }
}
