/** @type {import('tailwindcss').Config} */
export default {
  content: ["./app/Views/**/*.{php,html,js}"],
  theme: {
    extend: {},
  },
  plugins: [require("daisyui")],
  daisyui: {
    themes: ["business"],
  },  
};
