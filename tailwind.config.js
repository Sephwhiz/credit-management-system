/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    darkMode: 'class',
    theme: {
        extend: {
            colors: {
                // STRICT BLUE-TO-WHITE TECH STARK PALETTE
                'stark-blue': '#0ea5e9',      // Primary Blue (Jarvis Core)
                'stark-light': '#e0f2fe',     // Light Blue Tint
                'stark-white': '#ffffff',     // Pure White
                'stark-dark': '#0c4a6e',      // Deep Blue (for contrast/text)
            },
            backgroundImage: {
                // THE EXACT JARVIS TRANSITION YOU REQUESTED
                'tech-gradient': 'linear-gradient(180deg, #0ea5e9 0%, #ffffff 100%)',
                'tech-glow': 'radial-gradient(circle at center, rgba(14, 165, 233, 0.2) 0%, transparent 70%)',
            },
            boxShadow: {
                'stark': '0 0 20px rgba(14, 165, 233, 0.3)', // Blue glow only
            }
        },
    },
    plugins: [],
};