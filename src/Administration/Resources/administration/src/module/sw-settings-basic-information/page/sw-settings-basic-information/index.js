import { Component, Mixin } from 'src/core/shopware';
import template from './sw-settings-basic-information.html.twig';

Component.register('sw-settings-basic-information', {
    template,

    mixins: [
        Mixin.getByName('notification')
    ],


    data() {
        return {
            isLoading: false,
            isSaveSuccessful: false
        };
    },

    metaInfo() {
        return {
            title: this.$createTitle()
        };
    },

    methods: {
        saveFinish() {
            this.isSaveSuccessful = false;
        },

        onSave() {
            this.isSaveSuccessful = false;
            this.isLoading = true;

            this.$refs.systemConfig.saveAll().then(() => {
                this.isLoading = false;
                this.isSaveSuccessful = true;
            }).catch((err) => {
                this.isLoading = false;
                this.createNotificationError({
                    title: this.$tc('sw-settings-basic-information.general.titleSaveError'),
                    message: err
                });
            });
        }
    }
});
