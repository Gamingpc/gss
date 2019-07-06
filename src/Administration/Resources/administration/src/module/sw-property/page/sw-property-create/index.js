import { Component, State } from 'src/core/shopware';
import utils from 'src/core/service/util.service';
import template from './sw-property-create.html.twig';

Component.extend('sw-property-create', 'sw-property-detail', {
    template,

    beforeRouteEnter(to, from, next) {
        if (to.name.includes('sw.property.create') && !to.params.id) {
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

            this.group = this.groupStore.create(this.$route.params.id);
            this.group.sortingType = 'alphanumeric';
            this.group.displayType = 'text';
            this.groupId = this.group.id;
        },

        saveFinish() {
            this.isSaveSuccessful = false;
            this.$router.push({ name: 'sw.property.detail', params: { id: this.groupId } });
        },

        onSave() {
            this.$super.onSave();
        }
    }
});
