<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Upload</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

    <h2>Upload Image</h2>
    <form id="uploadForm" enctype="multipart/form-data">
        @csrf
        <input type="text" name="image_name" id="image_name" placeholder="Enter image name" required>
        <input type="file" name="image" id="image" required>
        <button type="submit">Upload</button>
    </form>

    <h2>Your Uploaded Images</h2>
    <div id="imageGallery"></div>

    <script>
        $(document).ready(function() {
            fetchImages();

            $("#uploadForm").submit(function(e) {
                e.preventDefault();
                var formData = new FormData(this);

                $.ajax({
                    url: "{{ url('/upload-image') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" },
                    success: function(response) {
                        alert(response.message);
                        fetchImages();
                    },
                    error: function(error) {
                        alert("Upload failed!");
                    }
                });
            });

            function fetchImages() {
                $.ajax({
                    url: "{{ url('/user-images') }}",
                    type: "GET",
                    success: function(response) {
                        $("#imageGallery").html("");
                        response.images.forEach(function(image) {
                            $("#imageGallery").append(`
                                <div>
                                    <img src="${image.image_url}" width="150" />
                                    <button onclick="deleteImage('${image.image_url}')">Delete</button>
                                </div>
                            `);
                        });
                    }
                });
            }

            window.deleteImage = function(imageUrl) {
                let imageName = imageUrl.split('/').pop(); 

                $.ajax({
                    url: `/delete-image/${imageName}`,
                    type: "DELETE",
                    headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" },
                    success: function(response) {
                        alert(response.message);
                        fetchImages();
                    },
                    error: function(error) {
                        alert("Delete failed!");
                    }
                });
            };
        });
    </script>

</body>
</html>

