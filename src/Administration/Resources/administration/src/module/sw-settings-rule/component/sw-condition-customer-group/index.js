import { Component, State } from 'src/core/shopware';
import template from './sw-condition-customer-group.html.twig';

/**
 * @public
 * @description Condition for the CustomerGroupRule. This component must a be child of sw-condition-tree.
 * @status prototype
 * @example-type code-only
 * @component-example
 * <sw-condition-customer-group :condition="condition" :level="0"></sw-condition-customer-group>
 */
Component.extend('sw-condition-customer-group', 'sw-condition-base', {
    template,
    inject: ['ruleConditionDataProviderService'],

    computed: {
        fieldNames() {
            return ['operator', 'customerGroupIds'];
        },
        defaultValues() {
            return {
                operator: this.ruleConditionDataProviderService.operators.isOneOf.identifier
            };
        }
    },

    methods: {
        getCustomerGroupStore() {
            return State.getStore('customer_group');
        }
    }
});
