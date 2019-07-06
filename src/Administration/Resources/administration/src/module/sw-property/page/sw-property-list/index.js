import { Component, State, Mixin } from 'src/core/shopware';
import template from './sw-property-list.html.twig';

Component.register('sw-property-list', {
    template,

    mixins: [
        Mixin.getByName('listing')
    ],

    data() {
        return {
            properties: [],
            isLoading: false,
            showDeleteModal: false
        };
    },

    metaInfo() {
        return {
            title: this.$createTitle()
        };
    },

    computed: {
        propertiesStore() {
            return State.getStore('property_group');
        },

        propertiesColumns() {
            return [{
                property: 'name',
                dataIndex: 'name',
                label: this.$tc('sw-property.list.columnName'),
                routerLink: 'sw.property.detail',
                inlineEdit: 'string',
                allowResize: true,
                primary: true
            }, {
                property: 'options',
                label: this.$tc('sw-property.list.columnOptions'),
                allowResize: true
            }, {
                property: 'description',
                label: this.$tc('sw-property.list.columnDescription'),
                allowResize: true
            }];
        }
    },

    methods: {
        onDelete(id) {
            this.showDeleteModal = id;
        },

        onCloseDeleteModal() {
            this.showDeleteModal = false;
        },

        onConfirmDelete(id) {
            this.showDeleteModal = false;

            return this.propertiesStore.getById(id).delete(true).then(() => {
                this.getList();
            });
        },

        onChangeLanguage() {
            this.getList();
        },

        getList() {
            this.isLoading = true;
            const params = this.getListingParams();

            this.properties = [];

            params.associations = {
                options: {
                    page: 1,
                    limit: 5
                }
            };

            return this.propertiesStore.getList(params, true).then((response) => {
                this.total = response.total;
                this.properties = response.items;
                this.isLoading = false;

                this.$nextTick(() => {
                    this.$refs.propertyList.compact = false;
                });

                return this.properties;
            });
        }
    }
});
