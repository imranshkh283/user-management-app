<!DOCTYPE html>
<html>

<head>
    <title>File Upload with Progress</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>

    <form id="uploadForm">
        <input type="file" id="fileInput" name="image">
        <button type="button" id="uploadButton">Upload</button>
        <div id="progress">0%</div>
    </form>
</body>

</html>

<script>
    const form = document.getElementById('uploadForm');
    const fileInput = document.getElementById('fileInput');
    const uploadButton = document.getElementById('uploadButton');
    const progressDiv = document.getElementById('progress');

    uploadButton.addEventListener('click', () => {
        const file = fileInput.files[0];

        if (!file) {
            alert('Please select a file.');
            return;
        }
        console.log({
            file
        });

        uploadFileInChunks(file, "{{ route('upload') }}"); // Use your Laravel route
    });

    async function uploadFileInChunks(file, url, chunkSize = 1024 * 1024 * 20) { // 20 MB chunk size
        const totalChunks = Math.ceil(file.size / chunkSize);
        let start = 0;

        for (let i = 0; i < totalChunks; i++) {
            const end = Math.min(start + chunkSize, file.size);
            const chunk = file.slice(start, end);

            const formData = new FormData();
            formData.append('chunk', chunk);
            formData.append('chunkNumber', i + 1);
            formData.append('totalChunks', totalChunks);
            formData.append('filename', file.name);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    body: formData,
                });

                if (!response.ok) {
                    const errorData = await response.json(); // Try to get error details
                    throw new Error(`Upload failed: ${response.status} - ${errorData.message || response.statusText}`);
                }

                const data = await response.json();
                console.log(`Chunk ${i + 1} of ${totalChunks} uploaded:`, data);
                const progress = Math.round(((i + 1) / totalChunks) * 100);
                progressDiv.textContent = `${progress}%`; // Update progress display

            } catch (error) {
                console.error('Error uploading chunk:', error);
                throw error; // Re-throw the error to be handled by the caller
            }

            start = end;
        }

        console.log('File upload complete!');
        progressDiv.textContent = '100%'; // Update progress to 100%
        alert("File upload complete!");
    }
</script>