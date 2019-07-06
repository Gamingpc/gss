import { Component } from 'src/core/shopware';
import template from './sw-customer-detail-base.html.twig';

Component.register('sw-customer-detail-base', {
    template,

    data() {
        return {
            createMode: false
        };
    },

    props: {
        customer: {
            type: Object,
            required: true
        },
        customerEditMode: {
            type: Boolean,
            required: true,
            default: false
        },
        customerName: {
            type: String,
            required: true,
            default: ''
        },
        salesChannels: {
            type: Array,
            required: true,
            default() {
                return [];
            }
        },
        customerGroups: {
            type: Array,
            required: true,
            default() {
                return [];
            }
        },
        paymentMethods: {
            type: Array,
            required: true,
            default() {
                return [];
            }
        },
        languages: {
            type: Array,
            required: true,
            default() {
                return [];
            }
        },
        language: {
            type: Object,
            required: true,
            default() {
                return {};
            }
        },
        countries: {
            type: Array,
            required: true,
            default() {
                return [];
            }
        },
        customerCustomFieldSets: {
            type: Array,
            required: true
        },
        isLoading: {
            type: Boolean,
            required: false,
            default: false
        }
    },

    computed: {
        editMode() {
            return this.customerEditMode;
        }
    },

    methods: {
        onActivateCustomerEditMode() {
            this.$emit('activateCustomerEditMode');
        }
    }
});
