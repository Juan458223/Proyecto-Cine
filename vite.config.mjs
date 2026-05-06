import { defineConfig } from 'vite'
import tailwindcss from '@tailwindcss/vite'

export default defineConfig({
  plugins: [
    tailwindcss(),
  ],
  build:{
    rollupOptions: {
        input:[
            'public/css/style.css',
            'public/js/index.js'
        ], 
    },
  },
})