import { Component, Mixin } from 'src/core/shopware';
import Criteria from 'src/core/data-new/criteria.data';
import template from './sw-sales-channel-detail.html.twig';

Component.register('sw-sales-channel-detail', {

    template,

    inject: [
        'repositoryFactory',
        'context'
    ],

    mixins: [
        Mixin.getByName('notification'),
        Mixin.getByName('placeholder')
    ],

    data() {
        return {
            salesChannel: null,
            isLoading: false,
            customFieldSets: [],
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
            return this.placeholder(this.salesChannel, 'name');
        },

        isStoreFront() {
            return this.salesChannel.typeId === '8a243080f92e4c719546314b577cf82b';
        },

        salesChannelRepository() {
            return this.repositoryFactory.create('sales_channel');
        },

        customFieldRepository() {
            return this.repositoryFactory.create('custom_field_set');
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
            this.loadEntityData();
        },

        loadEntityData() {
            if (!this.$route.params.id) {
                return;
            }

            if (this.$route.params.typeId) {
                return;
            }

            if (this.salesChannel) {
                this.salesChannel = null;
            }

            this.loadSalesChannel();
            this.loadCustomFieldSets();
        },

        loadSalesChannel() {
            const criteria = new Criteria();

            criteria.addAssociation('paymentMethods');
            criteria.addAssociation('shippingMethods');
            criteria.addAssociation('countries');
            criteria.addAssociation('currencies');
            criteria.addAssociation('languages');
            criteria.addAssociation('domains');

            this.isLoading = true;
            this.salesChannelRepository
                .get(this.$route.params.id, this.context, criteria)
                .then((entity) => {
                    this.salesChannel = entity;
                    this.isLoading = false;
                });
        },

        loadCustomFieldSets() {
            const criteria = new Criteria(1, 100);

            criteria.addFilter(Criteria.equals('relations.entityName', 'sales_channel'));
            criteria.addAssociation(
                'customFields',
                (new Criteria(1, 100)).addSorting(Criteria.sort('config.customFieldPosition'))
            );

            this.customFieldRepository
                .search(criteria, this.context)
                .then(({ items }) => {
                    this.customFieldSets = Object.values(items);
                });
        },

        saveFinish() {
            this.isSaveSuccessful = false;
        },

        onSave() {
            this.isLoading = true;

            this.isSaveSuccessful = false;

            return this.salesChannelRepository
                .save(this.salesChannel, this.context)
                .then(() => {
                    this.isLoading = false;
                    this.isSaveSuccessful = true;

                    this.$root.$emit('changed-sales-channel');
                    this.loadEntityData();
                }).catch(() => {
                    this.isLoading = false;

                    this.createNotificationError({
                        title: this.$tc('sw-sales-channel.detail.titleSaveError'),
                        message: this.$tc('sw-sales-channel.detail.messageSaveError', 0, {
                            name: this.salesChannel.name || this.placeholder(this.salesChannel, 'name')
                        })
                    });
                });
        },

        abortOnLanguageChange() {
            return this.salesChannelRepository.hasChanges(this.salesChannel);
        },

        saveOnLanguageChange() {
            return this.onSave();
        },

        onChangeLanguage() {
            this.loadEntityData();
        }
    }
});
