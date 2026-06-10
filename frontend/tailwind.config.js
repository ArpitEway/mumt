/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./index.html",
    "./src/**/*.{js,jsx}",
  ],
  theme: {
    extend: {
      colors: {
        primary: {
          50: '#fffbf0',
          100: '#fef5e7',
          200: '#fde8c3',
          300: '#fdb813',
          400: '#f9a825',
          500: '#f39c12',
          600: '#d68910',
          700: '#b8750e',
          800: '#9a620c',
          900: '#7c4f0a',
        },
        accent: {
          50: '#fef5f5',
          100: '#fedede',
          200: '#fdadad',
          300: '#ef476f',
          400: '#e82856',
          500: '#d41a3a',
          600: '#b01630',
          700: '#8b1226',
          800: '#66091c',
          900: '#410512',
        },
        success: {
          50: '#ecf7f4',
          100: '#d1ede7',
          200: '#a3dcd6',
          300: '#20c997',
          400: '#17a2b8',
          500: '#0e8b99',
          600: '#0a6b7a',
          700: '#064b5b',
          800: '#032b3c',
          900: '#010b1d',
        },
      }
    },
  },
  plugins: [],
}
