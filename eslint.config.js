import js from '@eslint/js'
import prettier from 'eslint-config-prettier'

export default [
  js.configs.recommended,
  prettier,
  {
    languageOptions: {
      ecmaVersion: 'latest',
      sourceType: 'module',
      globals: {
        window: 'readonly',
        document: 'readonly',
        console: 'readonly',
        setTimeout: 'readonly',
        setInterval: 'readonly',
        clearTimeout: 'readonly',
        clearInterval: 'readonly',
        fetch: 'readonly',
        FormData: 'readonly',
        URLSearchParams: 'readonly',
        location: 'readonly',
        wp: 'readonly',
        wpData: 'readonly',
        data: 'readonly',
        Swal: 'readonly'
      }
    },
    rules: {
      'no-console': 'warn',
      'no-unused-vars': 'warn',
      'prefer-const': 'error',
      'no-var': 'error'
    }
  },
  {
    ignores: [
      'node_modules/**',
      'vendor/**',
      'resources/assets/dist/**'
    ]
  }
]
