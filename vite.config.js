import { defineConfig } from 'vite'
import { resolve, dirname } from 'path'
import { fileURLToPath } from 'url'
import autoprefixer from 'autoprefixer'
import { copyFileSync, existsSync, mkdirSync } from 'fs'
import pkg from 'glob'
const { glob } = pkg

const __dirname = dirname(fileURLToPath(import.meta.url))

export default defineConfig({
  publicDir: false,
  plugins: [
    {
      name: 'copy-images',
      writeBundle() {
        const srcDir = 'resources/assets/src/images'
        const destDir = 'resources/assets/dist/images'

        if (!existsSync(destDir)) {
          mkdirSync(destDir, { recursive: true })
        }

        const files = glob.sync(`${srcDir}/**/*`, { nodir: true })
        files.forEach(file => {
          const relativePath = file.replace(srcDir + '/', '')
          const destFile = `${destDir}/${relativePath}`
          const destFileDir = dirname(destFile)

          if (!existsSync(destFileDir)) {
            mkdirSync(destFileDir, { recursive: true })
          }

          copyFileSync(file, destFile)
        })
      }
    }
  ],
  build: {
    outDir: 'resources/assets/dist',
    emptyOutDir: false,
    rollupOptions: {
      input: {
        app: resolve(__dirname, 'resources/assets/src/scripts/app.js'),
        styles: resolve(__dirname, 'resources/assets/src/styles/bundle.scss')
      },
      output: {
        entryFileNames: 'js/[name].min.js',
        assetFileNames: assetInfo => {
          if (assetInfo.names?.[0]?.endsWith('.css')) {
            return 'css/bundle.min.css'
          }
          if (assetInfo.names?.[0]?.match(/\.(png|jpe?g|svg|gif|webp|ico)$/)) {
            return 'images/[name][extname]'
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
      plugins: [autoprefixer]
    }
  }
})
