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

        //admin total (6)
        "resources/js/admin/disableSubmitButton.js",
        "resources/js/admin/enableSubmitButton.js",
        "resources/js/admin/pagecreate.js",
        "resources/js/admin/pageupdate.js",
        "resources/js/admin/usercreate.js",
        "resources/js/admin/uploadMediaToPresignedUrl.js",

        //common total (23)
        "resources/js/common/customCropper.js",
        "resources/js/common/croppersetter.js",
        "resources/js/common/customVideoUploadHandler.js",
        "resources/js/common/deleteConfirm.js",
        "resources/js/common/fieldsetToggler.js",
        "resources/js/common/googlemapsetter.js",
        "resources/js/common/errorValidation.js",
        "resources/js/common/loading.js",
        "resources/js/common/loginEyes.js",
        "resources/js/common/loginEyesAll.js",
        "resources/js/common/loginEyesNew.js",
        "resources/js/common/logoutConfirm.js",
        "resources/js/common/maxFileSize.js",
        "resources/js/admin/bankPartner/sortUpdate.js",
        "resources/js/common/multi_img_upload.js",
        "resources/js/common/multipleSelectCreate.js",
        "resources/js/common/navShowHide.js",
        "resources/js/common/previewTemplate.js",
        "resources/js/common/previewTemplateForUpdate.js",
        "resources/js/common/richEditor.js",
        "resources/js/common/rolePermission.js",
        "resources/js/common/search.js",
        "resources/js/common/sideActive.js",
        "resources/js/common/startQuillEditor.js",
        "resources/js/common/uploadErrorHandle.js",
        "resources/js/common/uploadToDigitalOcean.js",
        "resources/js/common/validateDisappear.js",
        "resources/js/common/sort_item.js",

        //dashboard total (1)
        "resources/js/dashboard/chart.js",

        //fileUpload total (1)
        "resources/js/fileUpload/base64ToFile.js",

        "resources/js/admin/articlecreate.js",
        "resources/js/admin/articleupdate.js",

        //quill total (6)
        "resources/js/quill/addAudioButton.js",
        "resources/js/quill/insertToEditor.js",
        "resources/js/quill/saveToServer.js",
        "resources/js/quill/selectLocalAudio.js",
        "resources/js/quill/selectLocalImage.js",
        "resources/js/quill/startQuillEditor.js",
        "resources/js/quill/init.js",

        "resources/js/admin/listing/send-telegram-notification.js",
        //Theme total (1)
        "resources/js/Theme/darkLight.js",

        //Page
        "resources/js/page/axiosPostRequest.js",
        "resources/js/page/axiosPutRequest.js",
        "resources/js/page/checkPageValidationFrontend.js",
        "resources/js/page/createFormData.js",
        "resources/js/page/handleUploadError.js",
        "resources/js/page/handleUploadSuccess.js",
        "resources/js/page/updateFormData.js",

        //tag
        "resources/js/tag/tagPreview.js",
        "resources/js/tag/showPreview.js",

        "resources/js/admin/listing/land-calculator.js",
        "resources/js/admin/mapPrice/polygonsetter.js",
        "resources/js/admin/mapPrice/map-point-index.js",
        "resources/js/admin/mapPrice/map-polygon-index.js",
        "resources/js/admin/mapPrice/pointsetter.js",
        "resources/js/admin/mapPrice/clickHandler.js",
        "resources/js/admin/mapPrice/drawingMapShapeOption.js",
        "resources/js/admin/mapPrice/mapObjectCreator.js",
        'resources/js/admin/newMapPrice/composables/useEditableMapObject.js',
        //Sponsor
        "resources/js/admin/sendsponsorAd.js",
        "resources/js/admin/sponsorAdcreate.js",
        "resources/js/admin/sponsorAdedit.js",
        "resources/js/admin/listing/map-index-view.js",
        "resources/js/admin/listing/region-selector.js",
        "resources/js/admin/listing/store.js",
        "resources/js/admin/listing/update.js",
        "resources/js/admin/newMapPrice/main.js",
        "resources/js/admin/newMapPrice/composables/saveMapZoomInLocalStorage.js",

        //User Guides
        "resources/js/admin/userGuideCreate.js",
        "resources/js/admin/userGuideUpdate.js",

        //Package
        "resources/js/admin/package/store.js",
        "resources/js/admin/package/update.js",
      ],
      refresh: true,
    }),
    vue(),
  ],
});
