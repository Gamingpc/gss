import { Component, Mixin, State } from 'src/core/shopware';
import template from './sw-category-tree.html.twig';
import './sw-category-tree.scss';

Component.register('sw-category-tree', {
    template,

    mixins: [Mixin.getByName('placeholder')],

    props: {
        categories: {
            type: Array,
            required: true
        },

        isLoadingInitialData: {
            type: Boolean,
            required: true
        },

        activeCategory: {
            type: [Object, null],
            required: false,
            default: null
        },

        disableContextMenu: {
            type: Boolean,
            required: false,
            default: false
        }
    },

    data() {
        return {
            translationContext: 'sw-category',
            linkContext: 'sw.category.detail',
            currentEditCategory: null,
            item: null,
            activeTreeItemId: '',
            loadedParentIds: [],
            hasInitiallyLoaded: false
        };
    },

    created() {
        if (this.activeCategory && this.activeCategory.id) {
            this.openTreeById();
        }
    },

    computed: {
        categoryStore() {
            return State.getStore('category');
        }
    },

    watch: {
        activeCategory() {
            if (this.hasInitiallyLoaded) {
                return;
            }

            if (this.activeCategory && this.activeCategory.id) {
                if (!this.loadedParentIds.includes(this.activeCategory.parentId)) {
                    this.hasInitiallyLoaded = true;
                    this.openTreeById();
                }
            }
        }
    },

    methods: {
        onUpdatePositions() {
            this.saveCategories();
        },

        saveCategories() {
            this.$emit('sw-category-on-save');
        },

        refreshCategories() {
            this.$emit('sw-category-on-refresh');
        },

        onDeleteCategory(item) {
            const category = this.categoryStore.getById(item.id);
            category.delete(true).then(() => {
                this.refreshCategories();
                if (this.activeCategory && item.id === this.activeCategory.id) {
                    this.$emit('sw-category-on-reset-details');
                }
            });
        },

        createNewCategory(name, parentId, childCount = 0) {
            const newCategory = this.categoryStore.create();

            newCategory.name = name;
            newCategory.parentId = parentId;
            newCategory.childCount = childCount;
            newCategory.active = false;
            newCategory.visible = true;

            return newCategory;
        },

        changeCategory(category) {
            const route = { name: 'sw.category.detail', params: { id: category.id } };
            if (this.activeCategory && this.activeCategory.hasChanges()) {
                this.$emit('sw-category-tree-on-unsaved-changes', route);
            } else {
                this.$router.push(route);
            }
        },

        getItemById(itemId) {
            return this.categoryStore.getByIdAsync(itemId);
        },

        onGetTreeItems(parentId) {
            this.$emit('sw-category-load-children', parentId);
        },

        getChildrenFromParent(parentId) {
            return this.$parent.$parent.getCategories(parentId);
        },

        openTreeById() {
            this.getParentIdsByItemId();
        },

        getParentIdsByItemId() {
            if (!this.activeCategory || !this.activeCategory.id) {
                return;
            }

            const category = this.activeCategory;

            if (!category.path) {
                return;
            }

            const parentPath = category.path;
            if (!parentPath) {
                return;
            }

            let parentIds = parentPath.split('|').reverse();
            parentIds = parentIds.filter((parent) => {
                return parent !== '';
            });

            this.getParentItems(parentIds);
        },

        getParentItems(ids) {
            const promises = [];
            ids.forEach((id) => {
                this.loadedParentIds.push(id);
                promises.push(this.getChildrenFromParent(id));
            });

            Promise.all(promises).then(() => {
                this.activeTreeItemId = this.activeCategory.id;
            });
        },

        createNewElement(contextItem, parentId, name = '') {
            if (!parentId && contextItem) {
                parentId = contextItem.parentId;
            }
            const newCategory = this.createNewCategory(name, parentId);
            this.categories.push(newCategory);
            return newCategory;
        }
    }
});
