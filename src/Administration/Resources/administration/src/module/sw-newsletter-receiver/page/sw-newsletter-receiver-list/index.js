import { Component, Mixin, State } from 'src/core/shopware';
import LocalStore from 'src/core/data/LocalStore';
import EntityCollection from 'src/core/data-new/entity-collection.data';
import Criteria from 'src/core/data-new/criteria.data';
import template from './sw-newsletter-receiver-list.twig';
import './sw-newsletter-receiver-list.scss';

Component.register('sw-newsletter-receiver-list', {
    template,

    inject: [
        'repositoryFactory',
        'context'
    ],

    mixins: [
        Mixin.getByName('listing')
    ],

    data() {
        return {
            isLoading: false,
            items: null,
            total: 0,
            repository: null,
            sortBy: 'createdAt',
            sortDirection: 'DESC',
            filterSidebarIsOpen: false,
            languageFilters: [],
            salesChannelFilters: [],
            tagFilters: [],
            internalFilters: {},
            tagCollection: null
        };
    },

    metaInfo() {
        return {
            title: this.$createTitle()
        };
    },

    created() {
        this.createdComponent();
    },

    computed: {
        columns() {
            return this.getColumns();
        },

        languageStore() {
            return this.repositoryFactory.create('language');
        },

        salesChannelStore() {
            return this.repositoryFactory.create('sales_channel');
        },

        tagStore() {
            return State.getStore('tag');
        },

        tagAssociationStore() {
            return new LocalStore([], 'id', 'name');
        }
    },

    methods: {
        createdComponent() {
            this.tagCollection = new EntityCollection('/tag', 'tag', this.context, new Criteria());

            const criteria = new Criteria(1, 100);
            this.languageStore.search(criteria, this.context).then((items) => {
                this.languageFilters = items;
            });

            this.salesChannelStore.search(criteria, this.context).then((items) => {
                this.salesChannelFilters = items;
            });

            this.getList();
        },

        getList() {
            this.isLoading = true;
            const criteria = new Criteria(this.page, this.limit, this.term);
            criteria.addSorting(Criteria.sort(this.sortBy, this.sortDirection));

            Object.values(this.internalFilters).forEach((item) => {
                criteria.addFilter(item);
            });

            this.repository = this.repositoryFactory.create('newsletter_receiver');
            this.repository.search(criteria, this.context).then((searchResult) => {
                this.items = searchResult;
                this.total = searchResult.total;

                this.isLoading = false;
            });
        },

        handleTagFilter(filter) {
            if (filter.length === 0) {
                delete this.internalFilters.tags;
                return;
            }

            const ids = filter.map((item) => {
                return item.id;
            });

            this.internalFilters.tags = Criteria.equalsAny('tags.id', ids);
        },

        handleBooleanFilter(filter) {
            if (!Array.isArray(this[filter.group])) {
                this[filter.group] = [];
            }

            if (!filter.value) {
                this[filter.group] = this[filter.group].filter((x) => { return x !== filter.id; });

                if (this[filter.group].length > 0) {
                    this.internalFilters[filter.group] = Criteria.equalsAny(filter.group, this[filter.group]);
                } else {
                    delete this.internalFilters[filter.group];
                }

                return;
            }

            this[filter.group].push(filter.id);
            this.internalFilters[filter.group] = Criteria.equalsAny(filter.group, this[filter.group]);
        },

        onChange(filter) {
            if (filter === null) {
                filter = [];
            }

            if (Array.isArray(filter)) {
                this.handleTagFilter(filter);
                this.getList();
                return;
            }

            this.handleBooleanFilter(filter);
            this.getList();
        },

        closeContent() {
            if (this.filterSidebarIsOpen) {
                this.$refs.filterSideBar.closeContent();
                this.filterSidebarIsOpen = false;
                return;
            }

            this.$refs.filterSideBar.openContent();
            this.filterSidebarIsOpen = true;
        },

        getColumns() {
            return [{
                property: 'firstName',
                dataIndex: 'firstName,lastName',
                inlineEdit: 'string',
                label: this.$tc('sw-newsletter-receiver.list.name'),
                routerLink: 'sw.newsletter.receiver.detail',
                allowResize: true,
                primary: true
            }, {
                property: 'email',
                dataIndex: 'email',
                label: this.$tc('sw-newsletter-receiver.list.email'),
                allowResize: true,
                inlineEdit: 'string'
            }, {
                property: 'status',
                dataIndex: 'status',
                label: this.$tc('sw-newsletter-receiver.list.status'),
                allowResize: true
            }, {
                property: 'zipCode',
                dataIndex: 'zipCode',
                label: this.$tc('sw-newsletter-receiver.list.zipCode'),
                allowResize: true,
                align: 'right'
            }, {
                property: 'city',
                dataIndex: 'city',
                label: this.$tc('sw-newsletter-receiver.list.city'),
                allowResize: true
            }, {
                property: 'street',
                dataIndex: 'street',
                label: this.$tc('sw-newsletter-receiver.list.street'),
                allowResize: true
            }, {
                property: 'updatedAt',
                dataIndex: 'updatedAt',
                label: this.$tc('sw-newsletter-receiver.list.updatedAt'),
                allowResize: true,
                visible: false
            }, {
                property: 'createdAt',
                dataIndex: 'createdAt',
                label: this.$tc('sw-newsletter-receiver.list.createdAt'),
                allowResize: true,
                visible: false
            }];
        }
    }
});
