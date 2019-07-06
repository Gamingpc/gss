import template from './sw-description-list.html.twig';
import './sw-description-list.scss';

/**
 * @public
 * @description A definition list which uses CSS grid for a column layout.
 * @status ready
 * @example-type static
 * @component-example
 * <sw-description-list>
 *     <dt>Product name</dt>
 *     <dd>Example product</dd>
 *     <dt>Price</dt>
 *     <dd>$4.99</dd>
 *     <dt>Description</dt>
 *     <dd>Lorem ipsum dolor sit amet, consetetur sadipscing elitr</dd>
 * </sw-description-list>
 */
export default {
    name: 'sw-description-list',
    template,

    props: {
        grid: {
            type: String,
            required: false,
            default: '1fr 1fr'
        }
    },

    computed: {
        descriptionListStyles() {
            return {
                'grid-template-columns': this.grid
            };
        }
    }
};
