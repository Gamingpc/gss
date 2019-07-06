import { Mixin } from 'src/core/shopware';
import Picker from 'vanilla-picker';
import SwColorPicker from '../sw-colorpicker/index';
import template from './sw-compact-colorpicker.html.twig';
import './sw-compact-colorpicker.scss';

/**
 * @public
 * @description Compact color picker input field.
 * @status ready
 * @example-type dynamic
 * @component-example
 * <sw-compact-colorpicker label="Color picker" value="#dd4800"></sw-colorpicker>
 */

export default {
    name: 'sw-compact-colorpicker',
    extends: SwColorPicker,
    template,
    inheritAttrs: false,

    mixins: [
        Mixin.getByName('sw-form-field')
    ],

    watch: {
        color() {}
    },

    methods: {
        mountedComponent() {
            this.colorPicker = new Picker({
                parent: this.$el,
                onClose: this.onClose,
                onOpen: this.onOpen,
                onChange: null,
                onDone: this.onDone
            });

            this.$nextTick(() => {
                if (this.isOverflowingLeft()) {
                    this.config.popup = 'right';
                }

                this.colorPicker.setOptions(this.config);
                this.setColor(this.value);
            });
        },

        isOverflowingLeft() {
            const boundary = this.$el.getBoundingClientRect();
            const pickerWidth = 250;


            const modalDialog = this.$el.closest('.sw-modal__dialog');
            if (modalDialog) {
                if (modalDialog.offsetLeft > (boundary.left - pickerWidth)) {
                    return true;
                }
            }

            return boundary.left < pickerWidth;
        },

        onDone(value) {
            this.resetFormError();
            this.$emit('input', value[this.colorCallback]);
            this.color = '';
        },

        onClose() {
            this.open = false;
            this.$emit('sw-colorpicker-closed');
        }
    }
};
