import { Component, State } from 'src/core/shopware';
import utils from 'src/core/service/util.service';
import template from './sw-settings-payment-create.html.twig';

Component.extend('sw-settings-payment-create', 'sw-settings-payment-detail', {
    template,

    beforeRouteEnter(to, from, next) {
        if (to.name.includes('sw.settings.payment.create') && !to.params.id) {
            to.params.id = utils.createId();
        }

        next();
    },

    computed: {
        languageStore() {
            return State.getStore('language');
        }
    },

    methods: {
        createdComponent() {
            if (this.languageStore.getCurrentId() !== this.languageStore.systemLanguageId) {
                this.languageStore.setCurrentId(this.languageStore.systemLanguageId);
            }

            if (this.$route.params.id) {
                this.paymentMethodStore.create(this.$route.params.id);
            }

            this.$super.createdComponent();
        },

        saveFinish() {
            this.isSaveSuccessful = false;
            this.$router.push({ name: 'sw.settings.payment.detail', params: { id: this.paymentMethod.id } });
        },

        onSave() {
            this.$super.onSave();
        }
    }
});
