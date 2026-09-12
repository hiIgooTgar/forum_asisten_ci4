/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./app/Views/**/*.php",
    "./app/Views/**/*.html",
    "./public/**/*.php",
    "./public/**/*.html",
    "./public/**/*.js",
  ],
  theme: {
    extend: {
      colors: {
        brand: {
          primary: "#0a2481",
          primary_light: "#0037ff",
          primary_light_v2: "#0028b8",
          primary_combine: "#0c2996",
          primary_combine_v2: "#0c1d61",
          primary_hover: "#0c1d61",
          primary_hover_v2: "#081750",
          dark: "#0B0521",
        },
      },
      fontFamily: {
        fa: ["Google Sans", "sans-serif"],
      },
    },
  },
  plugins: [],
};
