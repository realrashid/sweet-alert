import { defineConfig } from 'vitepress'

const year = new Date().getFullYear()

const title = 'Laravel SweetAlert'
const description =
    'Fluent, beautiful alerts for Laravel — full SweetAlert2 coverage with a Laravel-native builder API.'
const url = 'https://realrashid.github.io/sweet-alert/'
const twitter = 'rashidali05'
const github = 'https://github.com/realrashid/sweet-alert'

export default defineConfig({
    lang: 'en-US',
    title,
    description,
    ignoreDeadLinks: true,
    base: '/sweet-alert/',
    lastUpdated: true,
    cleanUrls: true,
    appearance: true,
    markdown: {
        defaultHighlightLang: 'php',
        theme: {
            dark: 'material-theme-palenight',
            light: 'github-light',
        },
    },

    head: [
        ['meta', { property: 'og:type', content: 'website' }],
        ['meta', { property: 'og:title', content: title }],
        ['meta', { property: 'og:url', content: url }],
        ['meta', { property: 'og:description', content: description }],
        ['meta', { name: 'twitter:card', content: 'summary' }],
        ['meta', { name: 'twitter:site', content: `@${twitter}` }],
        ['meta', { name: 'twitter:title', content: title }],
        ['meta', { name: 'twitter:description', content: description }],
        ['meta', { name: 'theme-color', content: '#0f172a' }],
    ],

    themeConfig: {
        nav: [
            { text: 'Guide', link: '/guide/getting-started', activeMatch: '^/guide/' },
            { text: 'API', link: '/api/alert-builder', activeMatch: '^/api/' },
        ],

        sidebar: {
            '/guide/': [
                {
                    text: 'Laravel SweetAlert',
                    collapsed: false,
                    items: [
                        { text: 'Getting Started', link: '/guide/getting-started' },
                        { text: 'Configuration', link: '/guide/configuration' },
                        { text: 'Alerts', link: '/guide/alerts' },
                        { text: 'Toast Notifications', link: '/guide/toasts' },
                        { text: 'Input Alerts', link: '/guide/inputs' },
                        { text: 'Confirm & Delete', link: '/guide/confirm-delete' },
                    ],
                },
                {
                    text: 'Customization',
                    collapsed: false,
                    items: [
                        { text: 'Buttons', link: '/guide/buttons' },
                        { text: 'Position', link: '/guide/position' },
                        { text: 'Animation', link: '/guide/animation' },
                        { text: 'Styling', link: '/guide/styling' },
                        { text: 'Timer & Persistence', link: '/guide/timer' },
                    ],
                },
                {
                    text: 'Advanced',
                    collapsed: false,
                    items: [
                        { text: 'Pre-Confirm Route', link: '/guide/pre-confirm' }, 
                        { text: 'Middleware', link: '/guide/middleware' },
                        { text: 'Helper Functions', link: '/guide/helpers' },
                        { text: 'Facade', link: '/guide/facade' },
                        { text: 'Enums', link: '/guide/enums' },
                    { text: 'Conditionals & Macros', link: '/guide/conditionals' },
                        { text: 'Custom Classes & HTML', link: '/guide/advanced' },
                    ],
                },
                {
                    text: 'Framework Integrations',
                    collapsed: false,
                    items: [
                        { text: 'Livewire v4', link: '/guide/livewire' },
                        { text: 'Inertia v3', link: '/guide/inertia' },
                        { text: 'npm / Asset Bundling', link: '/guide/npm' },
                        { text: 'Laravel Boost', link: '/guide/boost' },
                    ],
                },
                {
                    text: 'Migration',
                    collapsed: false,
                    items: [{ text: 'Upgrade Guide (v7 → v8)', link: '/guide/upgrade-guide' }],
                },
            ],
            '/api/': [
                {
                    text: 'API Reference',
                    collapsed: false,
                    items: [
                        { text: 'AlertBuilder', link: '/api/alert-builder' },
                        { text: 'ToastBuilder', link: '/api/toast-builder' },
                        { text: 'InputBuilder', link: '/api/input-builder' },
                        { text: 'AlertConfig', link: '/api/alert-config' },
                        { text: 'AlertFlasher', link: '/api/alert-flasher' },
                        { text: 'Enums', link: '/api/enums' },
                    ],
                },
            ],
        },

        editLink: {
            pattern: `${github}/edit/main/docs/:path`,
            text: 'Edit this page on GitHub',
        },

        socialLinks: [
            { icon: 'twitter', link: `https://twitter.com/${twitter}` },
            { icon: 'github', link: github },
        ],

        footer: {
            message: ' Made with ❤️ from Pakistan Released under the MIT License.',
            copyright: `Copyright © ${year} Rashid Ali `,
        },

        search: {
            provider: 'local',
        },
    },
})
