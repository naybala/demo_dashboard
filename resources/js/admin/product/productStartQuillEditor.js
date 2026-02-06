// resources/js/admin/product/productStartQuillEditor.js
import Quill from "quill";
import "quill/dist/quill.snow.css";

export default function productStartQuillEditor(
  selector,
  path,
  defaultText = "",
) {
  const toolbarOptions = [
    ["bold", "italic", "underline"], // toggled buttons
    ["link", "image"],

    [{ header: 1 }, { header: 2 }], // custom button values
    [{ list: "ordered" }, { list: "bullet" }],

    [{ color: [] }, { background: [] }], // dropdown with defaults from theme
  ];
  const quill = new Quill(selector, {
    theme: "snow",
    placeholder: "Text Here.",
    modules: {
      toolbar: toolbarOptions,
    },
  });

  if (defaultText) {
    quill.root.innerHTML = defaultText;
  }

  const toolbar = quill.getModule("toolbar");
  toolbar.addHandler("image", () => selectLocalImage(quill, path));

  return quill;
}

function selectLocalImage(quill, path) {
  const input = document.createElement("input");
  input.setAttribute("type", "file");
  input.click();

  input.onchange = () => {
    const file = input.files[0];
    const allowSize = 2097152; // 2MB

    if (file.size > allowSize) {
      alert("Max upload size 2 MB");
      return;
    } else if (/^image\//.test(file.type) === false) {
      alert("Please upload only Image");
    } else {
      saveToLocalServer(file, quill, path);
    }
  };
}

function saveToLocalServer(file, quill, path) {
  const fd = new FormData();
  fd.append("image", file);
  fd.append("path", path);

  axios
    .post("/upload/image/local", fd)
    .then((response) => {
      if (response.status === 200) {
        const url = response.data.url;
        insertToEditor(url, quill);
      }
    })
    .catch((error) => {
      console.error("Error uploading the image to local server:", error);
    });
}

function insertToEditor(url, quill) {
  const range = quill.getSelection();
  quill.insertEmbed(range.index, "image", `${url}`);
}
