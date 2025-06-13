import { createI18n } from 'vue-i18n'
import rs from './lang/rs.js' // ← this is the .ts file
import en from './lang/en.js' // ← this is the .ts file

export const i18n = createI18n({
    legacy: false,
    locale: 'en',
    fallbackLocale: 'en',
    messages: {
        rs,
        en
    }
})
