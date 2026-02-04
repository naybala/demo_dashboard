@props(['width' => '', 'height' => '', 'src' => '', 'rounded' => null ])
<div class="gap-4">
   <div class="flex justify-center">
     <img class="{{ $width }} {{ $height }} {{ $rounded }} cursor-pointer" src="{{ $src == "" || null ? asset('images/default_profile_pic.jpg') : $src }}"
        alt="" id="avartar-photo" onclick="viewImage()">
   </div>
    <div class="div" id="popup">
        <img src="" alt="" id="selected-image" />
    </div>
</div>
<script>
    const viewImage = () => {
        const popup = document.querySelector("#popup");
        const gallery = document.querySelector("#avartar-photo");
        const selectedImage = document.querySelector("#selected-image");
        selectedImage.src = gallery.src;
        popup.style.transform = `translateY(0%)`;
        popup.addEventListener("click", () => {
            popup.style.transform = `translateY(-100%)`;
            popup.src = "";
        });
    }
</script>
