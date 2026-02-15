import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";
import path from "path";
export default defineConfig({
  resolve: {
    alias: {
      "@": path.resolve(__dirname, "resources/js"),
      vue: "vue/dist/vue.esm-bundler.js",
    },
  },
  plugins: [
    laravel({
      input: [
        //Css
        "resources/css/app.css",
        "resources/css/multipleSelectCreate.css",
        "resources/css/custom.css",

        //Js
        "resources/js/app.js",
        "resources/js/bootstrap.js",

        //Admin
        "resources/js/admin/dailyIncome/calculate.js",
        "resources/js/admin/dailyIncome/totalCalculate.js",
        "resources/js/admin/product/cloud-store.js",
        "resources/js/admin/product/local-store.js",
        "resources/js/admin/product/product-quill.js",
        "resources/js/admin/product/productStartQuillEditor.js",

        //common total (25)
        "resources/js/common/comma-formatter.js",
        "resources/js/common/customCropper.js",
        "resources/js/common/customVideoUploadHandler.js",
        "resources/js/common/deleteConfirm.js",
        "resources/js/common/errorValidation.js",
        "resources/js/common/fieldsetToggler.js",
        "resources/js/common/googlemapsetter.js",
        "resources/js/common/loading.js",
        "resources/js/common/loginEyes.js",
        "resources/js/common/loginEyesAll.js",
        "resources/js/common/loginEyesNew.js",
        "resources/js/common/logoutConfirm.js",
        "resources/js/common/maxFileSize.js",
        "resources/js/common/multi_img_upload.js",
        "resources/js/common/multipleSelectCreate.js",
        "resources/js/common/navShowHide.js",
        "resources/js/common/previewTemplate.js",
        "resources/js/common/previewTemplateForUpdate.js",
        "resources/js/common/richEditor.js",
        "resources/js/common/rolePermission.js",
        "resources/js/common/search.js",
        "resources/js/common/sort_item.js",
        "resources/js/common/startQuillEditor.js",
        "resources/js/common/uploadErrorHandle.js",
        "resources/js/common/uploadToDigitalOcean.js",
        "resources/js/common/validateDisappear.js",

        //dashboard total (1)
        "resources/js/dashboard/chart.js",

        //fileUpload total (1)
        "resources/js/fileUpload/base64ToFile.js",

        //quill total (7)
        "resources/js/quill/addAudioButton.js",
        "resources/js/quill/init.js",
        "resources/js/quill/insertToEditor.js",
        "resources/js/quill/saveToServer.js",
        "resources/js/quill/selectLocalAudio.js",
        "resources/js/quill/selectLocalImage.js",
        "resources/js/quill/startQuillEditor.js",

        //tag
        "resources/js/tag/tagPreview.js",
        "resources/js/tag/showPreview.js",

        //Theme total (1)
        "resources/js/Theme/darkLight.js",
      ],
      refresh: true,
    }),
    vue(),
  ],
});
