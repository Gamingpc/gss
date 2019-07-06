import { Application } from 'src/core/shopware';
import EntityStore from './EntityStore';

export default class LanguageStore extends EntityStore {
    constructor(
        apiService,
        EntityClass,
        currentLanguageId = ''
    ) {
        super('language', apiService, EntityClass);

        this.systemLanguageId = Application.getContainer('init').contextService.systemLanguageId;

        if (!currentLanguageId || !currentLanguageId.length) {
            currentLanguageId = this.systemLanguageId;
        }

        this.currentLanguageId = currentLanguageId;
    }

    /**
     * Set the current languageId and calls the init method to fetch the data from server if necessary.
     *
     * @param {String} languageId
     * @return {Promise<{}>}
     */
    setCurrentId(languageId) {
        this.currentLanguageId = languageId;
        return this.getByIdAsync(languageId);
    }

    /**
     * Get the current languageId
     *
     * @returns {String}
     */
    getCurrentId() {
        return this.currentLanguageId;
    }

    /**
     * Get the current language entity proxy
     *
     * @return {EntityProxy}
     */
    getCurrentLanguage() {
        return this.store[this.currentLanguageId];
    }

    getLanguageStore() {
        return this;
    }

    init() {
        return this.getByIdAsync(this.currentLanguageId);
    }
}
