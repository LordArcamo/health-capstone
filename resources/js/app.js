import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import Notifications from '@kyvg/vue3-notification';

// Import Font Awesome library and icons
import { library } from '@fortawesome/fontawesome-svg-core';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import {
    faHome,
    faUser,
    faCaretUp,
    faCaretDown,
    faClipboard,
    faCalendarCheck,
    faHistory,
    faChevronDown,
    faHeartbeat,
    faChevronRight,
    faCalendar,
    faFileMedical,
    faBars,
    faTimes,
    faEllipsisV,
    faSignOutAlt,
    faChartBar,
    faPlusCircle,
    faFileDownload,
    faSearch,      // Added faSearch
    faFilter,      // Added faFilter
    faFileImport   // Added faFileImport
} from '@fortawesome/free-solid-svg-icons';

// Add icons to the Font Awesome library
library.add(
    faHome,
    faUser,
    faCaretUp,
    faCaretDown,
    faClipboard,
    faCalendarCheck,
    faHistory,
    faHeartbeat,
    faChevronDown,
    faChevronRight,
    faCalendar,
    faFileMedical,
    faBars,
    faTimes,
    faSignOutAlt,
    faEllipsisV,
    faChartBar,
    faPlusCircle,
    faFileDownload,
    faSearch,      // Added faSearch
    faFilter,      // Added faFilter
    faFileImport   // Added faFileImport
);

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) });
        app.use(plugin)
           .use(ZiggyVue)
           .use(Notifications)
           .component('font-awesome-icon', FontAwesomeIcon) // Register globally
           .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
