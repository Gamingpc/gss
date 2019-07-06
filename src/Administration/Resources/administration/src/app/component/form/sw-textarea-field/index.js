import { Mixin } from 'src/core/shopware';
import template from './sw-textarea-field.html.twig';
import './sw-textarea-field.scss';

/**
 * @description textarea input field.
 * @status ready
 * @example-type static
 * @component-example
 * <sw-textarea-field type="textarea" label="Name" placeholder="placeholder goes here..."></sw-textarea-field>
 */
export default {
    name: 'sw-textarea-field',
    template,
    inheritAttrs: false,

    mixins: [
        Mixin.getByName('sw-form-field')
    ],

    props: {
        value: {
            type: String,
            required: false
        },

        placeholder: {
            type: String,
            required: false,
            default: null
        }
    },

    data() {
        return {
            currentValue: this.value || ''
        };
    },

    watch: {
        value() { this.currentValue = this.value; }
    },

    methods: {
        onInput(event) {
            this.resetFormError();
            this.$emit('input', event.target.value);
        },

        onChange(event) {
            this.resetFormError();
            this.$emit('change', event.target.value);
        }
    }
};
