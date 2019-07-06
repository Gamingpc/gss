import template from './sw-text-editor-toolbar.html.twig';
import './sw-text-editor-toolbar.scss';

/**
 * @private
 */
export default {
    name: 'sw-text-editor-toolbar',
    template,

    props: {
        parentIsActive: {
            type: Boolean,
            required: false,
            default: false
        },

        isInlineEdit: {
            type: Boolean,
            required: false,
            default: false
        },

        selection: {
            type: Selection,
            required: false,
            default: null
        },

        buttonConfig: {
            type: Array,
            required: true
        }
    },

    data() {
        return {
            position: {},
            range: null,
            arrowPosition: {
                '--left': '49%;'
            }
        };
    },

    mounted() {
        this.mountedComponent();
    },

    destroyed() {
        this.destroyedComponent();
    },

    computed: {
        classes() {
            return {
                'is--boxedEdit': !this.isInlineEdit
            };
        }
    },

    methods: {
        mountedComponent() {
            if (this.isInlineEdit) {
                const body = document.querySelector('body');
                body.appendChild(this.$el);
            }

            document.addEventListener('mouseup', this.onMouseUp);
            this.setToolbarPosition();

            this.$emit('created-el', this.$el);
            this.$nextTick(() => {
                this.isOverlayingLeft();
            });
        },

        isOverlayingLeft() {
            if (!this.isInlineEdit) {
                return;
            }

            const el = this.$el;
            const leftSidebar = document.querySelector('.sw-admin-menu');
            if (!leftSidebar) {
                return;
            }

            const leftSidebarWidth = leftSidebar.offsetLeft + leftSidebar.offsetWidth;
            if (el.offsetLeft < leftSidebarWidth) {
                this.position.left = `${leftSidebarWidth + 4}px`;
                this.position.right = 'unset';
                const arrowWidth = 8;
                const selectionBoundary = this.range.getBoundingClientRect();

                let left = selectionBoundary.right - (selectionBoundary.width / 2);
                left -= (leftSidebarWidth + arrowWidth);
                this.arrowPosition['--left'] = `${left}px`;
                this.arrowPosition['--right'] = 'unset';
            }
        },

        destroyedComponent() {
            this.closeExpandedMenu();
            document.removeEventListener('mouseup', this.onMouseUp);
            this.$emit('destroyed-el');
        },

        onMouseUp(event) {
            const path = [];
            let source = event.target;
            while (source) {
                path.push(source);
                source = source.parentNode;
            }

            if (!path.includes(this.$el)) {
                this.closeExpandedMenu();
                return;
            }

            if (path.indexOf(this.$el) > -1 || !this.parentIsActive) {
                return;
            }

            this.setToolbarPosition();
        },

        setToolbarPosition() {
            if (!this.selection) {
                return;
            }

            if (!this.isInlineEdit) {
                this.setSelectionRange();
                return;
            }

            this.setSelectionRange();
            const boundary = this.range.getBoundingClientRect();

            let offsetTop = window.pageYOffset;
            const arrowHeight = 8;
            offsetTop += boundary.top - (this.$el.clientHeight + arrowHeight);

            const middleBoundary = (boundary.left + boundary.width / 2) + 4;
            const halfWidth = this.$el.clientWidth / 2;
            const offsetLeft = middleBoundary - halfWidth;

            this.position = {
                left: `${offsetLeft}px`,
                top: `${offsetTop}px`
            };
        },

        setSelectionRange() {
            if (this.selection.anchorNode) {
                this.range = this.selection.getRangeAt(0);
            }
        },

        handleToolbarClick(event) {
            if (!event.target.classList.contains('sw-text-editor-toolbar')) {
                return;
            }

            this.keepSelection();
        },

        handleButtonClick(button) {
            if (button.type === 'link' && !button.handler) {
                return;
            }

            if (!button.children) {
                this.closeExpandedMenu();
            }

            this.keepSelection();

            if (button.handler) {
                button.handler(button);
                return;
            }

            this.$emit('text-style-change', button.type, button.value);
        },

        handleTextStyleChangeLink(button) {
            let target = '_self';
            if (button.newTab) {
                target = '_blank';
            }

            this.keepSelection(true);

            if (button.value) {
                this.$emit('on-set-link', this.prepareLink(button.value), target);

                this.range = document.getSelection().getRangeAt(0);
                this.range.setStart(this.range.startContainer, 0);
            }
        },

        prepareLink(link) {
            link = link.trim();
            link = this.addProtocol(link);
            return link;
        },

        addProtocol(link) {
            if (/^(\w+):\/\//.test(link)) {
                return link;
            }

            const isInternal = /^\/[^\/\s]/.test(link);
            const isAnchor = link.substring(0, 1) === '#';
            const isProtocolRelative = /^\/\/[^\/\s]/.test(link);

            if (!isInternal && !isAnchor && !isProtocolRelative) {
                link = `http://${link}`;
            }

            return link;
        },

        keepSelection(keepRange) {
            if (!this.selection) {
                return;
            }

            if (!keepRange) {
                this.setSelectionRange();
            }

            this.selection.removeAllRanges();
            this.selection.addRange(this.range);
        },

        onToggleMenu(event, button) {
            if (button.type !== 'link' && !button.children) {
                return;
            }

            if (button.type === 'link' && event.target.closest('.sw-text-editor-toolbar__link-menu')) {
                return;
            }

            this.keepSelection();

            this.buttonConfig.forEach((item) => {
                if (item === button) {
                    return;
                }
                if (item.expanded) {
                    item.expanded = false;
                }
            });

            button.expanded = !button.expanded;
        },

        closeExpandedMenu() {
            this.buttonConfig.forEach((item) => {
                if (item.expanded) {
                    item.expanded = false;
                }
            });
        }
    }
};
