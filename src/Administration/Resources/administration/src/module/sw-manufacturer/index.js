import { Module } from 'src/core/shopware';
import './page/sw-manufacturer-list';
import './page/sw-manufacturer-detail';
import './page/sw-manufacturer-create';

import deDE from './snippet/de-DE.json';
import enGB from './snippet/en-GB.json';

Module.register('sw-manufacturer', {
    type: 'core',
    name: 'manufacturer',
    title: 'sw-manufacturer.general.mainMenuItemGeneral',
    description: 'Manages the manufacturer of the application',
    version: '1.0.0',
    targetVersion: '1.0.0',
    color: '#57D9A3',
    icon: 'default-symbol-products',
    favicon: 'icon-module-products.png',
    entity: 'product_manufacturer',

    snippets: {
        'de-DE': deDE,
        'en-GB': enGB
    },

    routes: {
        index: {
            components: {
                default: 'sw-manufacturer-list'
            },
            path: 'index'
        },
        create: {
            component: 'sw-manufacturer-create',
            path: 'create',
            meta: {
                parentPath: 'sw.manufacturer.index'
            }
        },
        detail: {
            component: 'sw-manufacturer-detail',
            path: 'detail/:id',
            meta: {
                parentPath: 'sw.manufacturer.index'
            }
        }
    },

    navigation: [{
        path: 'sw.manufacturer.index',
        label: 'sw-manufacturer.general.mainMenuItemList',
        id: 'sw-manufacturer',
        parent: 'sw-catalogue',
        color: '#57D9A3',
        position: 50
    }]
});
