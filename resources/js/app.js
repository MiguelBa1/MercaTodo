import './bootstrap';
import '../css/app.css';

import {createApp, h} from 'vue';
import {createInertiaApp} from '@inertiajs/vue3';
import {resolvePageComponent} from 'laravel-vite-plugin/inertia-helpers';
import {ZiggyVue} from '../../vendor/tightenco/ziggy/dist/vue.m';
import VueToast from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-sugar.css';
import {createPinia} from "pinia";
import {useCartStore} from "./store/cart";

const pinia = createPinia();
const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

pinia.use(async ({store}) => {
    if (store.$id === 'mainStore') {
        const mainStore = useCartStore();
        await mainStore.syncCart();
    }
})

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({el, App, props, plugin}) {
        return createApp({render: () => h(App, props)})
            .use(plugin)
            .use(VueToast)
            .use(ZiggyVue, Ziggy)
            .use(pinia)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
