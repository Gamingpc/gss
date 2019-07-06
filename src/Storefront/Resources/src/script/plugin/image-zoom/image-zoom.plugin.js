import Plugin from 'src/script/helper/plugin/plugin.class';
import Hammer from 'hammerjs';
import DomAccess from 'src/script/helper/dom-access.helper';
import { Vector2, Vector3 } from 'src/script/helper/vector.helper';
import PluginManager from 'src/script/helper/plugin/plugin.manager';

const IMAGE_SLIDER_INIT_SELECTOR = '[data-image-slider]';

/**
 * ImageZoomPlugin class
 */
export default class ImageZoomPlugin extends Plugin {

    static options = {

        /**
         * maximum zoom of the image
         * or 'auto' for automatic calculation
         *
         * @type string|number
         */
        maxZoom: 'auto',

        /**
         * amount of steps for the zoom to be at its max/min values
         * when the action buttons are pressed
         *
         * @type string|number
         */
        zoomSteps: 5,

        /**
         * selector for the image to be zoomed
         *
         * @type string
         */
        imageSelector: '.js-image-zoom-element',

        /**
         * selector for the element which will zoom the image in
         *  when clicked
         *
         * @type string
         */
        zoomInActionSelector: '.js-image-zoom-in',

        /**
         * selector for the element which will reset the zoom
         *  when clicked
         *
         * @type string
         */
        zoomResetActionSelector: '.js-image-zoom-reset',

        /**
         * selector for the element which will zoom the image out
         * when clicked
         *
         * @type string
         */
        zoomOutActionSelector: '.js-image-zoom-out',


        /**
         * selector to determent if the image is active at the moment
         * set to fall if this should be ignored
         *
         * @type string|boolean
         */
        activeClassSelector: '.tns-slide-active',
    };

    /**
     * init the plugin
     */
    init() {
        this._image = DomAccess.querySelector(this.el, this.options.imageSelector);
        this._zoomInActionElement = DomAccess.querySelector(document, this.options.zoomInActionSelector);
        this._zoomResetActionElement = DomAccess.querySelector(document, this.options.zoomResetActionSelector);
        this._zoomOutActionElement = DomAccess.querySelector(document, this.options.zoomOutActionSelector);

        this._imageMaxSize = new Vector2(DomAccess.getDataAttribute(this._image, 'data-max-width'), DomAccess.getDataAttribute(this._image, 'data-max-height'));
        this._imageSize = new Vector2(this._image.offsetWidth, this._image.offsetHeight);
        this._containerSize = new Vector2(this.el.offsetWidth, this.el.offsetHeight);

        this._storedTransform = new Vector3(0, 0, 1);
        this._transform = this._storedTransform;
        this._translateRange = new Vector2(0, 0);

        this._updateTranslateRange();
        this._initHammer();
        this._registerEvents();
        this._setActionButtonState();
    }

    /**
     * init hammer instance
     *
     * @private
     */
    _initHammer() {
        this._hammer = new Hammer(this.el);
        this._hammer.get('pinch').set({ enable: true });
        this._hammer.get('pan').set({ direction: Hammer.DIRECTION_ALL });
    }

    /**
     * register all needed events
     *
     * @private
     */
    _registerEvents() {
        this._hammer.on('pan', event => this._onPan(event));
        this._hammer.on('pinch pinchmove', event => this._onPinch(event));
        this._hammer.on('doubletap', event => this._onDoubleTap(event));
        this._hammer.on('panend pancancel pinchend pinchcancel', event => this._onInteractionEnd(event));

        this.el.addEventListener('wheel', event => this._onMouseWheel(event), false);
        this._image.addEventListener('mousedown', event => event.preventDefault(), false);
        window.addEventListener('resize', event => this._onResize(event), false);

        this._zoomInActionElement.addEventListener('click', event => this._onZoomIn(event), false);
        this._zoomResetActionElement.addEventListener('click', event => this._onResetZoom(event), false);
        this._zoomOutActionElement.addEventListener('click', event => this._onZoomOut(event), false);

        this._registerImageZoomButtonUpdate();
    }

    /**
     * registers a callback which
     * sets the button state when an image zoom
     * element within a slider is active
     *
     * @private
     */
    _registerImageZoomButtonUpdate() {
        const slider = this.el.closest(IMAGE_SLIDER_INIT_SELECTOR);
        if (slider) {
            const imageSliderPlugin = PluginManager.getPluginInstanceFromElement(slider, 'ImageSlider');
            if (imageSliderPlugin) {
                imageSliderPlugin.registerChangeListener(() => {
                    const activeSlide = imageSliderPlugin.getActiveSlideElement();
                    const imageZoomElement = activeSlide.querySelector('[data-image-zoom]');
                    const imageZoomPlugin = PluginManager.getPluginInstanceFromElement(imageZoomElement, 'ImageZoom');
                    imageZoomPlugin._setActionButtonState();
                });
            }
        }
    }

    /**
     * returns if the current element is active
     *
     * @return {boolean}
     *
     * @private
     */
    _isActive() {
        if (this.options.activeClassSelector === false) return true;

        return this.el.closest(this.options.activeClassSelector) !== null;
    }

    /**
     * listener for panning
     *
     * @param {Event} event
     * @private
     */
    _onPan(event) {
        if (this._isActive()) {
            this._transform = this._storedTransform.add(event.deltaX, event.deltaY, 0);
            this._unsetTransition();
            this._updateTransform();
            this._setCursor('move');
        }
    }

    /**
     * listener for pinching
     *
     * @param {Event} event
     * @private
     */
    _onPinch(event) {
        if (this._isActive()) {
            const x = this._storedTransform.x + event.deltaX;
            const y = this._storedTransform.x + event.deltaY;
            const z = this._storedTransform.z * event.scale;

            this._transform = this._transform.set(x, y, z);
            this._unsetTransition();
            this._updateTransform();
            this._setCursor('move');
        }
    }

    /**
     * listener for double tapping
     *
     * @private
     */
    _onDoubleTap() {
        if (this._isActive()) {
            const maxZoom = this._getMaxZoomValue();
            const z = (this._storedTransform.z >= maxZoom) ? 1 : maxZoom;

            this._transform = this._transform.set(false, false, z);
            this._setTransition();
            this._updateTransform(true);
        }
    }

    /**
     * listener for zooming in
     *
     * @private
     */
    _onZoomIn() {
        if (this._isActive()) {
            const zoomAmount = this._getMaxZoomValue() / this.options.zoomSteps;
            this._transform = this._transform.add(0, 0, zoomAmount);
            this._setTransition();
            this._updateTransform(true);
        }
    }

    /**
     * listener for resetting zoom
     *
     * @private
     */
    _onResetZoom() {
        if (this._isActive()) {
            this._transform = this._transform.set(false, false, 1);
            this._setTransition();
            this._updateTransform(true);
        }
    }

    /**
     * listener for zooming out
     *
     * @private
     */
    _onZoomOut() {
        if (this._isActive()) {
            const zoomAmount = this._getMaxZoomValue() / this.options.zoomSteps;
            this._transform = this._transform.subtract(0, 0, zoomAmount);
            this._setTransition();
            this._updateTransform(true);
        }
    }

    /**
     * listener for the mousewheel
     *
     * @param {Event} event
     * @private
     */
    _onMouseWheel(event) {
        if (this._isActive()) {
            this._transform = this._transform.add(0, 0, (event.wheelDelta / 800));
            this._unsetTransition();
            this._updateTransform(true);
        }
    }

    /**
     * callback when interaction with zoom container ends
     *
     * @private
     */
    _onInteractionEnd() {
        if (this._isActive()) {
            this._updateTransform(true);
            this._setCursor('default');
        }
    }

    /**
     * listener for resize
     * updates needed values on resize
     *
     * @private
     */
    _onResize() {
        this._getElementSizes();
        this._updateTransform(true);
    }

    /**
     * sets to needed element sizes
     * to the current context
     *
     * @private
     */
    _getElementSizes() {
        this._imageSize = new Vector2(this._image.offsetWidth, this._image.offsetHeight);
        this._containerSize = new Vector2(this.el.offsetWidth, this.el.offsetHeight);
    }

    /**
     * updates the image transform values
     *
     * @param updateStoredTransform
     * @private
     */
    _updateTransform(updateStoredTransform) {
        this._updateTranslateRange();
        this._clampTransform();
        this._setActionButtonState();

        const translateX = `translateX(${Math.round(this._transform.x)}px)`;
        const translateY = `translateY(${Math.round(this._transform.y)}px)`;
        const scale = `scale(${this._transform.z},${this._transform.z})`;

        const transform = `${translateX} ${translateY} translateZ(0px) ${scale}`;
        this._image.style.transform = transform;
        this._image.style.WebkitTransform = transform;
        this._image.style.msTransform = transform;

        if (updateStoredTransform) {
            this._updateStoredTransformVector();
        }
    }

    _setActionButtonState() {
        if (this._transform.z === 1 && this._getMaxZoomValue() === 1) {
            this._setButtonDisabledState(this._zoomResetActionElement);
            this._setButtonDisabledState(this._zoomOutActionElement);
            this._setButtonDisabledState(this._zoomInActionElement);
        } else if (this._getMaxZoomValue() === this._transform.z) {
            this._unsetButtonDisabledState(this._zoomResetActionElement);
            this._unsetButtonDisabledState(this._zoomOutActionElement);
            this._setButtonDisabledState(this._zoomInActionElement);
        } else if (this._transform.z === 1) {
            this._setButtonDisabledState(this._zoomResetActionElement);
            this._setButtonDisabledState(this._zoomOutActionElement);
            this._unsetButtonDisabledState(this._zoomInActionElement);
        } else {
            this._unsetButtonDisabledState(this._zoomResetActionElement);
            this._unsetButtonDisabledState(this._zoomOutActionElement);
            this._unsetButtonDisabledState(this._zoomInActionElement);
        }
    }

    /**
     * toggle the active state of the zoom in action element
     *
     * @private
     */
    _setButtonDisabledState(el) {
        el.classList.add('disabled');
    }

    /**
     * toggle the active state of the zoom in action element
     *
     * @private
     */
    _unsetButtonDisabledState(el) {
        el.classList.remove('disabled');
    }

    /**
     * updates the stored transform vector
     *
     * @private
     */
    _updateStoredTransformVector() {
        this._clampTransform();
        this._storedTransform = this._storedTransform.set(this._transform);
    }

    /**
     * updates the x/y translate range for the image
     *
     * @private
     */
    _updateTranslateRange() {
        this._getElementSizes();
        const scaledImageSize = this._imageSize.multiply(this._transform.z).round();
        this._translateRange = scaledImageSize.subtract(this._containerSize).clamp(0, scaledImageSize).divide(2);
    }

    /**
     * returns the max zoom value of the element
     *
     * @return {number}
     * @private
     */
    _getMaxZoomValue() {
        this._getElementSizes();

        if (this._imageSize.x === 0 || this._imageSize.y === 0) {
            return 0;
        }

        const max = this._imageMaxSize.divide(this._imageSize);

        return Math.max(max.x, max.y);
    }

    /**
     * @param type
     * @private
     */
    _setCursor(type) {
        this.el.style.cursor = type;
    }

    /**
     * sets the image transition
     *
     * @private
     */
    _setTransition() {
        const transition = 'all 350ms ease 0s';

        this._image.style.transition = transition;
        this._image.style.WebkitTransition = transition;
        this._image.style.msTransition = transition;
    }

    /**
     * unsets the image transition
     *
     * @private
     */
    _unsetTransition() {
        const transition = '';

        this._image.style.transition = transition;
        this._image.style.WebkitTransition = transition;
        this._image.style.msTransition = transition;
    }

    /**
     * clamps the vector to its possible min/max values
     *
     * @private
     */
    _clampTransform() {
        const minVector = new Vector3(-this._translateRange.x, -this._translateRange.y, 1);
        const maxVector = new Vector3(this._translateRange.x, this._translateRange.y, this._getMaxZoomValue());

        this._transform = this._transform.clamp(minVector, maxVector);
    }
}
