import { Module } from 'src/core/shopware';

import './extension/sw-settings-index';
import './page/sw-settings-currency-list';
import './page/sw-settings-currency-detail';
import './page/sw-settings-currency-create';

import deDE from './snippet/de-DE.json';
import enGB from './snippet/en-GB.json';

Module.register('sw-settings-currency', {
    type: 'core',
    name: 'settings-currency',
    title: 'sw-settings-currency.general.mainMenuItemGeneral',
    description: 'Currency section in the settings module',
    color: '#9AA8B5',
    icon: 'default-action-settings',
    favicon: 'icon-module-settings.png',
    entity: 'currency',

    snippets: {
        'de-DE': deDE,
        'en-GB': enGB
    },

    routes: {
        index: {
            component: 'sw-settings-currency-list',
            path: 'index',
            meta: {
                parentPath: 'sw.settings.index'
            }
        },
        detail: {
            component: 'sw-settings-currency-detail',
            path: 'detail/:id',
            meta: {
                parentPath: 'sw.settings.currency.index'
            }
        },
        create: {
            component: 'sw-settings-currency-create',
            path: 'create',
            meta: {
                parentPath: 'sw.settings.currency.index'
            }
        }
    }
});
