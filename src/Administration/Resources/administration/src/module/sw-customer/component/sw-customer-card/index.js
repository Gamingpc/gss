import { Component, Mixin } from 'src/core/shopware';
import Criteria from 'src/core/data-new/criteria.data';
import template from './sw-customer-card.html.twig';
import './sw-customer-card.scss';

Component.register('sw-customer-card', {
    template,

    inject: [
        'repositoryFactory',
        'context'
    ],

    mixins: [
        Mixin.getByName('salutation')
    ],

    props: {
        customer: {
            type: Object,
            required: true
        },
        title: {
            type: String,
            required: true
        },
        editMode: {
            type: Boolean,
            required: false,
            default: false
        },
        isLoading: {
            type: Boolean,
            required: false,
            default: false
        }
    },

    data() {
        return {
            salutations: null
        };
    },

    computed: {
        hasActionSlot() {
            return !!this.$slots.actions;
        },

        hasAdditionalDataSlot() {
            return !!this.$slots['data-additional'];
        },

        hasSummarySlot() {
            return !!this.$slots.summary;
        },

        moduleColor() {
            if (!this.$route.meta.$module) {
                return '';
            }
            return this.$route.meta.$module.color;
        },

        fullName() {
            const name = {
                name: this.salutation(this.customer),
                company: this.customer.company
            };

            return Object.values(name).filter(item => item !== null).join(' - ').trim();
        },

        salutationRepository() {
            return this.repositoryFactory.create('salutation');
        }
    },

    created() {
        this.createdComponent();
    },

    methods: {
        createdComponent() {
            const criteria = new Criteria(1, 500);
            return this.salutationRepository.search(criteria, this.context).then(({ items }) => {
                this.salutations = items;
            });
        },

        getMailTo(mail) {
            return `mailto:${mail}`;
        }
    }
});
