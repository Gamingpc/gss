import Plugin from 'src/script/helper/plugin/plugin.class';
import PluginManager from 'src/script/helper/plugin/plugin.manager';
import Iterator from 'src/script/helper/iterator.helper';
import DomAccess from 'src/script/helper/dom-access.helper';
import FormSerializeUtil from 'src/script/utility/form/form-serialize.util';

export default class AddToCartPlugin extends Plugin {

    init() {
        this._getForm();

        if (!this._form) {
            throw new Error(`No form found for the plugin: ${this.constructor.name}`);
        }

        this._registerEvents();
    }

    /**
     * tries to get the closest form
     *
     * @returns {HTMLElement|boolean}
     * @private
     */
    _getForm() {
        if (this.el && this.el.nodeName === 'FORM') {
            this._form = this.el;
        } else {
            this._form = this.el.closest('form');
        }
    }

    _registerEvents() {
        this.el.addEventListener('submit', this._onFormSubmit.bind(this));
    }

    /**
     * On submitting the form the OffCanvas shall open, the product has to be posted
     * against the storefront api and after that the current cart template needs to
     * be fetched and shown inside the OffCanvas
     * @param {Event} event
     * @private
     */
    _onFormSubmit(event) {
        event.preventDefault();

        const requestUrl = DomAccess.getAttribute(this._form, 'action').toLowerCase();
        const formData = FormSerializeUtil.serialize(this._form);

        this._openOffCanvasCarts(requestUrl, formData);
    }

    /**
     *
     * @param {string} requestUrl
     * @param {{}|FormData} formData
     * @private
     */
    _openOffCanvasCarts(requestUrl, formData) {
        const offCanvasCartInstances = PluginManager.getPluginInstances('OffCanvasCart');
        Iterator.iterate(offCanvasCartInstances, instance => this._openOffCanvasCart(instance, requestUrl, formData));
    }

    /**
     *
     * @param {OffCanvasCartPlugin} instance
     * @param {string} requestUrl
     * @param {{}|FormData} formData
     * @private
     */
    _openOffCanvasCart(instance, requestUrl, formData) {
        instance.openOffCanvas(requestUrl, formData);
    }
}
