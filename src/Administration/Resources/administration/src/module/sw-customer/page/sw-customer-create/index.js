import { Component } from 'src/core/shopware';
import utils from 'src/core/service/util.service';
import template from './sw-customer-create.html.twig';

Component.extend('sw-customer-create', 'sw-customer-detail', {
    template,

    inject: ['numberRangeService'],

    beforeRouteEnter(to, from, next) {
        if (to.name.includes('sw.customer.create') && !to.params.id) {
            to.params.id = utils.createId();
        }

        next();
    },

    data() {
        return {
            customerNumberPreview: ''
        };
    },

    provide() {
        return {
            swCustomerCreateOnChangeSalesChannel: this.onChangeSalesChannel
        };
    },


    methods: {
        createdComponent() {
            this.customer = this.customerRepository.create(this.context, this.$route.params.id);
            const addressRepository = this.repositoryFactory.create(
                this.customer.addresses.entity,
                this.customer.addresses.source
            );

            const defaultAddress = addressRepository.create(this.context);

            this.customer.addresses.add(defaultAddress);
            this.customer.defaultBillingAddressId = defaultAddress.id;
            this.customer.defaultShippingAddressId = defaultAddress.id;

            // ToDo: Change to actual password strategy
            this.customer.password = 'shopware';

            this.$super.createdComponent();

            this.isLoading = false;
            this.customerEditMode = true;
        },

        saveFinish() {
            this.isSaveSuccessful = false;
            this.$router.push({ name: 'sw.customer.detail', params: { id: this.customer.id } });
        },

        onSave() {
            if (this.customerNumberPreview === this.customer.customerNumber) {
                this.numberRangeService.reserve('customer', this.customer.salesChannelId).then((response) => {
                    this.customerNumberPreview = 'reserved';
                    this.customer.customerNumber = response.number;
                    this.$super.onSave();
                });
            } else {
                this.$super.onSave().then(() => {
                    this.customerNumberPreview = 'reserved';
                });
            }
        },

        onChangeSalesChannel(salesChannelId) {
            this.numberRangeService.reserve('customer', salesChannelId, true).then((response) => {
                this.customerNumberPreview = response.number;
                this.customer.customerNumber = response.number;
            });
        }
    }
});
