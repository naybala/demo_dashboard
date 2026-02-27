import "./bootstrap";
import "../css/app.css";

import { createInertiaApp } from "@inertiajs/svelte";
createInertiaApp({
  progress: {
    delay: 0,
    color: "#004bfa",
    showSpinner: false,
  },
  resolve: (name) => {
    const pages = import.meta.glob("./Pages/**/*.svelte", { eager: true });
    return pages[`./Pages/${name}.svelte`];
  },
  setup({ el, App, props }) {
    new App({ target: el, props });
  },
});
