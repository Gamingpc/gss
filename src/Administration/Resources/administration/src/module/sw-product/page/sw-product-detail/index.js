import { Component, Mixin, State } from 'src/core/shopware';
import CriteriaFactory from 'src/core/factory/criteria.factory';
import template from './sw-product-detail.html.twig';

Component.register('sw-product-detail', {
    template,

    inject: ['mediaService'],

    mixins: [
        Mixin.getByName('notification'),
        Mixin.getByName('placeholder'),
        Mixin.getByName('discard-detail-page-changes')('product')
    ],

    data() {
        return {
            product: {},
            manufacturers: [],
            currencies: [],
            taxes: [],
            customFieldSets: [],
            priceIsCalculating: false,
            isLoading: false,
            isSaveSuccessful: false
        };
    },

    metaInfo() {
        return {
            title: this.$createTitle(this.identifier)
        };
    },

    computed: {
        identifier() {
            return this.placeholder(this.product, 'name');
        },

        productStore() {
            return State.getStore('product');
        },

        manufacturerStore() {
            return State.getStore('product_manufacturer');
        },

        currencyStore() {
            return State.getStore('currency');
        },

        productMediaStore() {
            return this.product.getAssociation('media');
        },

        taxStore() {
            return State.getStore('tax');
        },

        customFieldSetStore() {
            return State.getStore('custom_field_set');
        },

        disableSaving() {
            return this.product.isLoading;
        }
    },

    created() {
        this.createdComponent();
    },

    watch: {
        '$route.params.id'() {
            this.createdComponent();
        }
    },

    methods: {
        createdComponent() {
            if (this.$route.params.id) {
                this.productId = this.$route.params.id;
                this.loadEntityData();
            }

            this.$root.$on('sw-product-media-form-open-sidebar', this.openMediaSidebar);
        },

        updatePriceIsCalculating(value) {
            this.priceIsCalculating = value;
        },

        loadEntityData() {
            this.product = this.productStore.getById(this.productId);

            this.product.getAssociation('media').getList({
                page: 1,
                limit: 50,
                sortBy: 'position',
                sortDirection: 'ASC'
            });

            this.manufacturerStore.getList({ page: 1, limit: 100 }).then((response) => {
                this.manufacturers = response.items;
            });

            this.currencyStore.getList({ page: 1, limit: 100 }).then((response) => {
                this.currencies = response.items;
            });

            this.taxStore.getList({ page: 1, limit: 100 }).then((response) => {
                this.taxes = response.items;
            });

            this.customFieldSetStore.getList({
                page: 1,
                limit: 100,
                criteria: CriteriaFactory.equals('relations.entityName', 'product'),
                associations: {
                    customFields: {
                        limit: 100,
                        sort: 'config.customFieldPosition'
                    }
                }
            }, true).then((response) => {
                this.customFieldSets = response.items;
            });
        },

        abortOnLanguageChange() {
            return this.product.hasChanges();
        },

        saveOnLanguageChange() {
            return this.onSave();
        },

        onChangeLanguage() {
            this.loadEntityData();
        },

        openMediaSidebar() {
            this.$refs.mediaSidebarItem.openContent();
        },

        saveFinish() {
            this.isSaveSuccessful = false;
        },

        onSave() {
            const productName = this.product.name || this.product.translated.name;
            const titleSaveError = this.$tc('global.notification.notificationSaveErrorTitle');
            const messageSaveError = this.$tc(
                'global.notification.notificationSaveErrorMessage', 0, { entityName: productName }
            );
            this.isSaveSuccessful = false;
            this.isLoading = true;

            return this.product.save().then(() => {
                this.isLoading = false;
                this.isSaveSuccessful = true;
                this.$refs.mediaSidebarItem.getList();
            }).catch((exception) => {
                this.createNotificationError({
                    title: titleSaveError,
                    message: messageSaveError
                });
                this.isLoading = false;
                throw exception;
            });
        },

        onAddItemToProduct(mediaItem) {
            if (this._checkIfMediaIsAlreadyUsed(mediaItem.id)) {
                this.createNotificationInfo({
                    message: this.$tc('sw-product.mediaForm.errorMediaItemDuplicated')
                });
                return;
            }
            const productMedia = this.productMediaStore.create();
            productMedia.mediaId = mediaItem.id;
            this.product.media.push(productMedia);
        },

        _checkIfMediaIsAlreadyUsed(mediaId) {
            return this.product.media.some((productMedia) => {
                return productMedia.mediaId === mediaId;
            });
        }
    }
});
