import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import { svelte } from "@sveltejs/vite-plugin-svelte";
import path from "path";

export default defineConfig({
  resolve: {
    alias: {
      "@": path.resolve(__dirname, "resources/js"),
    },
  },
  server: {
    host: "localhost",
    hmr: {
      host: "localhost",
    },
  },
  plugins: [
    laravel({
      input: ["resources/js/app.js", "resources/css/app.css"],
      refresh: true,
    }),
    svelte(),
  ],
});
