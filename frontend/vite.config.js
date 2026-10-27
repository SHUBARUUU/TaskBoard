import tailwindcss from "@tailwindcss/vite"; //  Configured/Addes this part the moment I installed tailwind from npm.

import react from "@vitejs/plugin-react";
import { defineConfig } from "vite";

// https://vite.dev/config/
export default defineConfig({
  plugins: [react(), tailwindcss()], //  Configured as well added tailwind plugin
});
