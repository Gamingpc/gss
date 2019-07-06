const _ = require('lodash');
const glob = require('glob');
const path = require('path');
const uuid = require('uuid/v4');
const AdminApiService = require('./admin-api.service');

export default class AdminFixtureService {
    constructor() {
        this.apiClient = new AdminApiService(process.env.APP_URL);
        this.basicFixture = '';

        // Automatic loading of fixtures
        glob.sync(path.join(__dirname, './fixture/*.js')).forEach((fileName) => {
            require(fileName);
        });
    }

    setBasicFixture(json) {
        this.basicFixture = this.loadJson(json);
    }

    create(type, userData = {}) {
        const startTime = new Date();
        global.logger.title(`Set ${type} fixtures...`);

        this.setBasicFixture(`${type}.json`);
        const finalRawData = this.mergeFixtureWithData(this.basicFixture, userData);

        return this.apiClient.post(`/v1/${type}?response=true`, finalRawData)
            .then(() => {
                return this.apiClient.post(`/v1/search/${type}?response=true`, {
                    filter: [{
                        field: 'name',
                        type: 'equals',
                        value: finalRawData.name
                    }]
                });
            }).then((data) => {
                const endTime = new Date() - startTime;
                global.logger.success(`${data.id} (${endTime / 1000}s)`);
                global.logger.lineBreak();
                return data;
            }).catch((err) => {
                global.logger.error(err);
                global.logger.lineBreak();
            });
    }

    update(userData) {
        if (userData.id) {
            return this.apiClient.patch(`/v1/${userData.type}/${userData.id}?_response=true`, userData.data);
        }

        return this.search(userData.type, userData.data).then((result) => {
            this.apiClient.patch(`/v1/${userData.type}/${result.id}?_response=true`, userData.data);
        });
    }

    search(type, filter) {
        return this.apiClient.post(`/v1/search/${type}?response=true`, {
            filter: [{
                field: filter.identifier ? filter.identifier : 'name',
                type: 'equals',
                value: filter.value
            }]
        });
    }

    createUuid() {
        return uuid();
    }

    mergeFixtureWithData(...args) {
        const result = _.merge({}, ...args);
        return result;
    }

    loadJson(fileName) {
        try {
            return require(`./../../@fixtures/${fileName}`);
        } catch (err) {
            global.logger.error(err);
        }
    }

    getClientId(salesChannelName = 'Storefront') {
        return this.apiClient.post('/v1/search/sales-channel?response=true', {
            filter: [{
                field: 'name',
                type: 'equals',
                value: salesChannelName
            }]
        }).then((result) => {
            return result.attributes.accessKey;
        });
    }
}

global.AdminFixtureService = new AdminFixtureService();
