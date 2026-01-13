<input type="file" name="{{$name}}"   class="form-control image-input"  accept=".pdf,.jpg,.jpeg,.png" />
<div class="preview-wrapper"></div> <!-- THIS will contain the preview -->

{{-- <label class="file-upload-card">
    <div class="upload-content">
        <i class="fa fa-cloud-upload"></i>
        <p>Click to upload</p>
        <small>PDF, JPG, PNG (Max 5MB)</small>
    </div>
</label> --}}
<script>
document.addEventListener('change', function (e) {
    // Only target inputs with class 'image-input'
    if (!e.target.classList.contains('image-input')) return;

    const input = e.target;
    const file = input.files[0];

    // Only process images
    if (!file || !file.type.startsWith('image/')) return;

    // Find the preview wrapper div (immediate sibling)
    const previewDiv = input.nextElementSibling;
    if (!previewDiv || !previewDiv.classList.contains('preview-wrapper')) return;

    // Clear previous preview if any
    previewDiv.innerHTML = '';

    // Create new img element
    const img = document.createElement('img');
    img.src = URL.createObjectURL(file); // faster than FileReader
    img.className = 'img-thumbnail mt-2';
    img.style.maxHeight = '150px';
    img.style.marginRight = '5px';

    // Append the image inside the preview wrapper
    previewDiv.appendChild(img);
});
</script>



