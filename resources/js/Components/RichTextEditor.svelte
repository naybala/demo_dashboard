<script>
  import { onMount, onDestroy } from "svelte";
  import InputLabel from "./InputLabel.svelte";
  import InputError from "./InputError.svelte";
  import { __ } from "@/helpers";

  export let value = "";
  export let label = "";
  export let error = "";
  export let placeholder = "";
  export let id = "editor-" + Math.random().toString(36).substr(2, 9);
  export let minHeight = "200px";
  export let uploadedPath = "uploads/quill";

  let quill;
  let editorElement;

  onMount(async () => {
    // Lazy-load Quill — only fetched when this page is visited
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

    async function uploadImage(file) {
      const fd = new FormData();
      fd.append("image", file);
      fd.append("path", uploadedPath);

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
        } else {
          console.error("Image upload failed", response.status);
          return null;
        }
      } catch (err) {
        console.error("Image upload error", err);
        return null;
      }
    }

    function imageHandler() {
      const input = document.createElement("input");
      input.setAttribute("type", "file");
      input.setAttribute("accept", "image/*");
      input.click();

      input.onchange = async () => {
        const file = input.files[0];
        if (!file) return;

        if (file.size > 2097152) {
          alert("Max upload size 2 MB");
          return;
        }
        if (!/^image\//.test(file.type)) {
          alert("Please upload only an image");
          return;
        }

        const url = await uploadImage(file);
        if (url) {
          const range = quill.getSelection(true);
          quill.insertEmbed(range.index, "image", url);
          quill.setSelection(range.index + 1);
        }
      };
    }

    function base64ToFile(base64) {
      const arr = base64.split(",");
      const mime = arr[0].match(/:(.*?);/)[1];
      const bstr = atob(arr[1]);
      let n = bstr.length;
      const u8arr = new Uint8Array(n);
      while (n--) {
        u8arr[n] = bstr.charCodeAt(n);
      }
      const ext = mime.split("/")[1] || "png";
      return new File([u8arr], `pasted_image_${Date.now()}.${ext}`, {
        type: mime,
      });
    }

    async function handleBase64Images() {
      const imgs = quill.root.querySelectorAll('img[src^="data:image/"]');
      for (const img of imgs) {
        const base64 = img.getAttribute("src");
        const file = base64ToFile(base64);
        if (file) {
          const url = await uploadImage(file);
          if (url) {
            img.setAttribute("src", url);
            value = quill.root.innerHTML;
          }
        }
      }
    }

    quill = new Quill(editorElement, {
      theme: "snow",
      modules: { toolbar: toolbarOptions },
      placeholder,
    });

    quill.getModule("toolbar").addHandler("image", imageHandler);

    quill.root.innerHTML = value || "";

    quill.on("text-change", async (delta, oldDelta, source) => {
      if (source === "user") {
        const hasBase64 = quill.root.querySelector('img[src^="data:image/"]');
        if (hasBase64) {
          await handleBase64Images();
        }
      }
      value = quill.root.innerHTML;
    });
  });

  onDestroy(() => {
    quill = null;
  });
</script>

<div>
  {#if label}
    <InputLabel value={label} />
  {/if}
  <div
    class="mt-1 bg-white dark:bg-gray-900 rounded-md border border-gray-300 dark:border-gray-700"
    style="min-height: {minHeight};"
  >
    <div bind:this={editorElement} {id}></div>
  </div>
  {#if error}
    <InputError message={error} />
  {/if}
</div>

<style>
  :global(.ql-toolbar) {
    border-top-left-radius: 0.375rem;
    border-top-right-radius: 0.375rem;
    background-color: rgb(249, 250, 251);
  }
  :global(.ql-container) {
    border-bottom-left-radius: 0.375rem;
    border-bottom-right-radius: 0.375rem;
    font-family: inherit;
  }
  :global(.dark .ql-toolbar) {
    background-color: rgb(17, 24, 39);
    border-color: rgb(55, 65, 81);
    color: white;
  }
  :global(.dark .ql-container) {
    border-color: rgb(55, 65, 81);
  }
  :global(.dark .ql-stroke) {
    stroke: white;
  }
  :global(.dark .ql-fill) {
    fill: white;
  }
  :global(.dark .ql-picker) {
    color: white;
  }
</style>
