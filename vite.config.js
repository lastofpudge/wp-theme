import { defineConfig } from 'vite'
import { resolve } from 'path'
import autoprefixer from 'autoprefixer'

export default defineConfig({
  build: {
    outDir: 'resources/assets/dist',
    emptyOutDir: true,
    rollupOptions: {
      input: {
        app: resolve(__dirname, 'resources/assets/src/scripts/app.js'),
        styles: resolve(__dirname, 'resources/assets/src/styles/bundle.scss')
      },
      output: {
        entryFileNames: 'js/[name].min.js',
        assetFileNames: (assetInfo) => {
          if (assetInfo.name.endsWith('.css')) {
            return 'css/bundle.min.css'
          }
          return 'assets/[name][extname]'
        }
      }
    },
    watch: {
      include: 'resources/assets/src/**'
    }
  },

  css: {
    preprocessorOptions: {
      scss: {
        api: 'modern-compiler'
      }
    },
    postcss: {
      plugins: [
        autoprefixer
      ]
    }
  }
})