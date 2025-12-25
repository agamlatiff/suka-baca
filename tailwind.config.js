import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";
import typography from "@tailwindcss/typography";

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: "class",
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
    ],

    theme: {
        extend: {
            colors: {
                primary: {
                    DEFAULT: "#2F483A",
                    light: "#466555",
                    dark: "#1E2E26",
                },
                secondary: {
                    DEFAULT: "#3B5C56",
                    accent: "#E6A838",
                },
                background: {
                    light: "#F5F7F6",
                    dark: "#161D1A",
                },
                surface: {
                    light: "#FFFFFF",
                    dark: "#1E2623",
                },
                "text-color": {
                    light: "#242E29",
                    dark: "#E3E8E6",
                },
                accent: {
                    purple: "#8b5cf6",
                    orange: "#f97316",
                    teal: "#2D8A82",
                },
            },
            fontFamily: {
                sans: ["Poppins", ...defaultTheme.fontFamily.sans],
            },
            borderRadius: {
                DEFAULT: "0.75rem",
                xl: "1rem",
                "2xl": "1.5rem",
                "3xl": "2rem",
                "4xl": "2.5rem",
            },
            boxShadow: {
                soft: "0 10px 40px -10px rgba(47, 72, 58, 0.08)",
                glow: "0 0 20px rgba(230, 168, 56, 0.3)",
            },
        },
    },

    plugins: [forms, typography],
};
