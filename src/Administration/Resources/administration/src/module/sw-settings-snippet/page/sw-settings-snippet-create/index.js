import { Component } from 'src/core/shopware';

Component.extend('sw-settings-snippet-create', 'sw-settings-snippet-detail', {

    data() {
        return {
            isCreate: true
        };
    }
});
