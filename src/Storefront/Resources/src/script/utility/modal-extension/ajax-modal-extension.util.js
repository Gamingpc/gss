import HttpClient from 'src/script/service/http-client.service';
import DomAccess from 'src/script/helper/dom-access.helper';
import PageLoadingIndicatorUtil from 'src/script/utility/loading-indicator/page-loading-indicator.util';
import PseudoModalUtil from 'src/script/utility/modal-extension/pseudo-modal.util';
import Iterator from 'src/script/helper/iterator.helper';

const URL_DATA_ATTRIBUTE = 'data-url';

/**
 * This class extends the Bootstrap modal functionality by
 * adding an event listener to modal triggers that contain
 * a special "data-url" attribute which is needed to load
 * the modal content by AJAX
 *
 * Notice: The response template needs to have the markup as defined in the Bootstrap docs
 * https://getbootstrap.com/docs/4.3/components/modal/#live-demo
 */
export default class AjaxModalExtensionUtil {

    /**
     * Constructor.
     */
    constructor() {
        this._client = new HttpClient(window.accessKey, window.contextToken);
        this._registerEvents();
    }

    /**
     * Register events
     * @private
     */
    _registerEvents() {
        this._registerAjaxModalExtension();
    }

    /**
     * Handle modal trigger that contain the "data-url" attribute
     * and thus need to load the modal content via AJAX
     * @private
     */
    _registerAjaxModalExtension() {
        const modalTriggers = document.querySelectorAll(`[data-toggle="modal"][${URL_DATA_ATTRIBUTE}]`);
        if (modalTriggers) {
            Iterator.iterate(modalTriggers, trigger => trigger.addEventListener('click', this._onClickHandleAjaxModal.bind(this)));
        }
    }

    /**
     * When clicking/touching the modal trigger the button shall
     * show a loading indicator and an AJAX request needs to be triggered.
     * The response then has to be placed inside the modal which will show up.
     * @param {Event} event
     * @private
     */
    _onClickHandleAjaxModal(event) {
        event.preventDefault();
        event.stopPropagation();

        const trigger = event.target;
        const url = DomAccess.getAttribute(trigger, URL_DATA_ATTRIBUTE);
        PageLoadingIndicatorUtil.create();
        this._client.get(url, response => this._openModal(response));
    }

    /**
     * opens the ajax modal
     *
     * @param response
     * @private
     */
    _openModal(response) {
        PageLoadingIndicatorUtil.remove();
        const modal = new PseudoModalUtil(response);

        modal.open();
    }
}
