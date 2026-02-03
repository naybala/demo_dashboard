import Dropzone from "dropzone";
import "./bootstrap";
import "flowbite";
import jQuery from "jquery";
// import 'preline/dist/preline.js';
import 'preline'
import swal from "sweetalert2";
window.$ = jQuery;
// HSFileUpload.autoInit();
import Alpine from 'alpinejs'

Alpine.start()

// If you want Alpine's instance to be available globally
window.Alpine = Alpine


window.Swal = swal;
window.Dropzone = Dropzone;
