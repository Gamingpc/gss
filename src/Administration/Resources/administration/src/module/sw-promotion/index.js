import { Module } from 'src/core/shopware';

import deDE from './snippet/de-DE.json';
import enGB from './snippet/en-GB.json';

import './component/sw-promotion-sales-channel-select';

import './component/sw-promotion-basic-form';
import './component/sw-promotion-code-form';
import './component/sw-promotion-order-condition-form';
import './component/sw-promotion-persona-form';
import './component/sw-promotion-discount-component';
import './component/sw-promotion-scope-form';

import './view/sw-promotion-create-base';
import './view/sw-promotion-detail-base';
import './view/sw-promotion-detail-discounts';
import './view/sw-promotion-detail-restrictions';

import './page/sw-promotion-create';
import './page/sw-promotion-detail';
import './page/sw-promotion-list';

Module.register('sw-promotion', {
    type: 'core',
    name: 'promotion',
    title: 'sw-promotion.general.mainMenuItemGeneral',
    description: 'sw-promotion.general.description',
    version: '1.0.0',
    targetVersion: '1.0.0',
    color: '#FFD700',
    icon: 'default-package-gift',
    favicon: 'icon-module-marketing.png',
    entity: 'promotion',

    snippets: {
        'de-DE': deDE,
        'en-GB': enGB
    },

    routes: {
        index: {
            components: {
                default: 'sw-promotion-list'
            },
            path: 'index'
        },

        create: {
            component: 'sw-promotion-create',
            path: 'create',
            redirect: {
                name: 'sw.promotion.create.base'
            },
            children: {
                base: {
                    component: 'sw-promotion-create-base',
                    path: 'base',
                    meta: {
                        parentPath: 'sw.promotion.index'
                    }
                }
            }
        },

        detail: {
            component: 'sw-promotion-detail',
            path: 'detail/:id',
            redirect: {
                name: 'sw.promotion.detail.base'
            },
            children: {
                base: {
                    component: 'sw-promotion-detail-base',
                    path: 'base',
                    meta: {
                        parentPath: 'sw.promotion.index'
                    }
                },
                restrictions: {
                    component: 'sw-promotion-detail-restrictions',
                    path: 'restrictions',
                    meta: {
                        parentPath: 'sw.promotion.index'
                    }
                },
                discounts: {
                    component: 'sw-promotion-detail-discounts',
                    path: 'discounts',
                    meta: {
                        parentPath: 'sw.promotion.index'
                    }
                }
            }
        }
    },

    navigation: [{
        id: 'sw-promotion',
        path: 'sw.promotion.index',
        label: 'sw-promotion.general.mainMenuItemGeneral',
        color: '#FFD700',
        icon: 'default-package-gift',
        position: 100,
        parent: 'sw-marketing'
    }]
});
