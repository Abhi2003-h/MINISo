import tailwindcss from '@tailwindcss/vite'

// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  compatibilityDate: '2025-07-15',
  devtools: { enabled: true },
  css: ['~/assets/css/main.css'],
  vite: {
    plugins: [tailwindcss()],
  },
  app: {
    head: {
      htmlAttrs: { lang: 'en' },
      titleTemplate: (title?: string) => (title ? `${title} · MiniShop` : 'MiniShop — Small shop. Big finds.'),
      meta: [
        { name: 'viewport', content: 'width=device-width, initial-scale=1' },
        { name: 'description', content: 'Discover products from independent sellers. Simple shopping, curated products, trusted sellers.' },
        { name: 'theme-color', content: '#2563eb' },
      ],
    },
  },
})
