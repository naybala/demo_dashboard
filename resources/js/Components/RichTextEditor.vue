<script setup>
import { onMounted, onUnmounted, ref, watch } from "vue";
import InputLabel from "./InputLabel.vue";
import InputError from "./InputError.vue";
import { __ } from "@/helpers";

const props = defineProps({
  modelValue: {
    type: String,
    default: "",
  },
  label: {
    type: String,
    default: "",
  },
  error: {
    type: String,
    default: "",
  },
  placeholder: {
    type: String,
    default: "",
  },
  id: {
    type: String,
    default: () => "editor-" + Math.random().toString(36).substr(2, 9),
  },
  minHeight: {
    type: String,
    default: "200px",
  },
  uploadedPath: {
    type: String,
    default: "uploads/quill",
  },
});

const emit = defineEmits(["update:modelValue"]);

const editorElement = ref(null);
let quill = null;

const uploadImage = async (file) => {
  const fd = new FormData();
  fd.append("image", file);
  fd.append("path", props.uploadedPath);

  try {
    const response = await fetch("/upload/image/local/quill", {
      method: "POST",
      headers: {
        "X-CSRF-TOKEN":
          document
            .querySelector('meta[name="csrf-token"]')
            ?.getAttribute("content") ?? "",
      },
      body: fd,
    });

    if (response.ok) {
      const data = await response.json();
      return data.url;
    }
    return null;
  } catch (err) {
    console.error("Image upload error", err);
    return null;
  }
};

const base64ToFile = (base64) => {
  const arr = base64.split(",");
  const mime = arr[0].match(/:(.*?);/)[1];
  const bstr = atob(arr[1]);
  let n = bstr.length;
  const u8arr = new Uint8Array(n);
  while (n--) {
    u8arr[n] = bstr.charCodeAt(n);
  }
  const ext = mime.split("/")[1] || "png";
  return new File([u8arr], `pasted_image_${Date.now()}.${ext}`, { type: mime });
};

const handleBase64Images = async () => {
  const imgs = quill.root.querySelectorAll('img[src^="data:image/"]');
  for (const img of imgs) {
    const base64 = img.getAttribute("src");
    const file = base64ToFile(base64);
    if (file) {
      const url = await uploadImage(file);
      if (url) {
        img.setAttribute("src", url);
        emit("update:modelValue", quill.root.innerHTML);
      }
    }
  }
};

onMounted(async () => {
  const [{ default: Quill }] = await Promise.all([
    import("quill"),
    import("quill/dist/quill.snow.css"),
  ]);

  const toolbarOptions = [
    ["bold", "italic", "underline", "strike"],
    ["blockquote", "code-block"],
    [{ header: 1 }, { header: 2 }],
    [{ list: "ordered" }, { list: "bullet" }],
    [{ script: "sub" }, { script: "super" }],
    [{ indent: "-1" }, { indent: "+1" }],
    [{ direction: "rtl" }],
    [{ size: ["small", false, "large", "huge"] }],
    [{ header: [1, 2, 3, 4, 5, 6, false] }],
    [{ color: [] }, { background: [] }],
    [{ font: [] }],
    [{ align: [] }],
    ["image", "clean"],
  ];

  quill = new Quill(editorElement.value, {
    theme: "snow",
    modules: {
      toolbar: {
        container: toolbarOptions,
        handlers: {
          image: () => {
            const input = document.createElement("input");
            input.setAttribute("type", "file");
            input.setAttribute("accept", "image/*");
            input.click();
            input.onchange = async () => {
              const file = input.files[0];
              if (!file) return;
              const url = await uploadImage(file);
              if (url) {
                const range = quill.getSelection(true);
                quill.insertEmbed(range.index, "image", url);
                quill.setSelection(range.index + 1);
              }
            };
          },
        },
      },
    },
    placeholder: props.placeholder,
  });

  quill.root.innerHTML = props.modelValue || "";

  quill.on("text-change", (delta, oldDelta, source) => {
    if (source === "user") {
      handleBase64Images();
    }
    emit("update:modelValue", quill.root.innerHTML);
  });
});

watch(
  () => props.modelValue,
  (newValue) => {
    if (quill && newValue !== quill.root.innerHTML) {
      quill.root.innerHTML = newValue || "";
    }
  },
);

onUnmounted(() => {
  quill = null;
});
</script>

<template>
  <div>
    <InputLabel v-if="label" :value="label" />
    <div
      class="mt-1 bg-white dark:bg-gray-900 rounded-md border border-gray-300 dark:border-gray-700"
      :style="{ minHeight }"
    >
      <div ref="editorElement" :id="id"></div>
    </div>
    <InputError v-if="error" :message="error" />
  </div>
</template>

<style>
.ql-toolbar {
  border-top-left-radius: 0.375rem;
  border-top-right-radius: 0.375rem;
  background-color: rgb(249, 250, 251);
}
.ql-container {
  border-bottom-left-radius: 0.375rem;
  border-bottom-right-radius: 0.375rem;
  font-family: inherit;
}
.dark .ql-toolbar {
  background-color: rgb(17, 24, 39);
  border-color: rgb(55, 65, 81);
  color: white;
}
.dark .ql-container {
  border-color: rgb(55, 65, 81);
}
.dark .ql-stroke {
  stroke: white;
}
.dark .ql-fill {
  fill: white;
}
.dark .ql-picker {
  color: white;
}
</style>
