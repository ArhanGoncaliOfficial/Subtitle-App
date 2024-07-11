const dropZone = document.getElementById('drop_zone');
const fileInput = document.getElementById('file_input');
const fileUpload = document.getElementById('file_upload');
const uploadForm = document.getElementById('upload_form');
const uploadedFileLink = document.getElementById('uploaded_file_link');
const uploadedFilesList = document.getElementById('uploaded_files_list');
const uploadedFilesSection = document.getElementById('uploaded_files_section');
const fixFilesButton = document.getElementById('fix_files_button');
const popup = document.getElementById('popup');
const popupDownloadButton = document.getElementById('popup_download_button');

let filesList = [];

dropZone.addEventListener('dragover', (e) => {
    e.preventDefault();
    dropZone.classList.add('dragging');
});

dropZone.addEventListener('dragleave', (e) => {
    dropZone.classList.remove('dragging');
});

dropZone.addEventListener('drop', (e) => {
    e.preventDefault();
    dropZone.classList.remove('dragging');
    const files = e.dataTransfer.files;
    if (files.length) {
        addFilesToList(files);
    }
});

fileInput.addEventListener('change', (e) => {
    const files = e.target.files;
    if (files.length) {
        addFilesToList(files);
    }
});

function addFilesToList(files) {
    Array.from(files).forEach(file => {
        if (file.name.endsWith('.srt')) {
            filesList.push(file);
        } else {
            alert(`${file.name} is not a valid SRT file.`);
        }
    });
    updateFilesInput();
    listUploadedFiles();
}

function updateFilesInput() {
    const dataTransfer = new DataTransfer();
    filesList.forEach(file => dataTransfer.items.add(file));
    fileUpload.files = dataTransfer.files;
}

function listUploadedFiles() {
    uploadedFilesList.innerHTML = '';
    if (filesList.length > 0) {
        uploadedFilesSection.classList.remove('hidden');
        filesList.forEach((file, index) => {
            const li = document.createElement('li');
            li.textContent = file.name;
            const removeButton = document.createElement('button');
            removeButton.textContent = 'Remove';
            removeButton.onclick = () => removeFile(index);
            li.appendChild(removeButton);
            uploadedFilesList.appendChild(li);
        });
    } else {
        uploadedFilesSection.classList.add('hidden');
    }
}

function removeFile(index) {
    filesList.splice(index, 1);
    updateFilesInput();
    listUploadedFiles();
}

function submitForm() {
    const formData = new FormData(uploadForm);
    fetch(uploadForm.action, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            uploadedFileLink.innerHTML = `<a id="download_link" href="${data.download_url}" download="subtitle_fix.zip" hidden>Download Processed Files</a>`;
            fixFilesButton.textContent = 'Download Processed Files';
            fixFilesButton.onclick = () => {
                document.getElementById('download_link').click();
                setTimeout(() => {
                    fetch(uploadForm.action + '?cleanup=true')
                    .then(() => {
                        window.location.reload();
                    });
                }, 1000);
            };
            fixFilesButton.style.display = 'none';
            popup.classList.remove('hidden');
        } else {
            alert(data.message);
        }
    })
    .catch(error => console.error('Error:', error));
}

popupDownloadButton.addEventListener('click', () => {
    document.getElementById('download_link').click();
    setTimeout(() => {
        fetch(uploadForm.action + '?cleanup=true')
        .then(() => {
            window.location.reload();
        });
    }, 1000);
});
