/* eslint-disable import/no-extraneous-dependencies */
/* eslint-disable global-require */
module.exports = {
  future: {
    removeDeprecatedGapUtilities: true,
    purgeLayersByDefault: true,
  },
  purge: [
    './resources/assets/vue/src/views/**/*.vue',
    './resources/assets/vue/src/components/**/*.vue',
    './resources/assets/vue/src/App.vue',
  ],
  theme: {
    extend: {
      colors: {
        'royal-blue': '#0078B6',
      },
      boxShadow: {
        custom: '0 10px 15px -3px rgba(0, 0, 0, 0.6), 0 4px 6px -2px rgba(0, 0, 0, 0.1)',
      }
    },
  },
  variants: {
    opacity: ['responsive', 'hover', 'focus', 'disabled'],
  },
  plugins: [
    require('@tailwindcss/custom-forms'),
  ],
};
