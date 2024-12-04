import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';

// Import Font Awesome library and icons
import { library } from '@fortawesome/fontawesome-svg-core';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faHome, faUser, faCaretUp, faCaretDown, faClipboard, faCalendarCheck, faHistory,faChevronDown,faHeartbeat, faChevronRight, faCalendar , faFileMedical, faBars, faTimes, faEllipsisV, faSignOutAlt} from '@fortawesome/free-solid-svg-icons';

// Add icons to the Font Awesome library
library.add(faHome, faUser, faCaretUp, faCaretDown ,faClipboard, faCalendarCheck, faHistory, faHeartbeat,faChevronDown, faChevronRight, faCalendar, faFileMedical, faBars, faTimes, faSignOutAlt , faEllipsisV);

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .component('font-awesome-icon', FontAwesomeIcon) // Register globally
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
