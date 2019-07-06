const GeneralPageObject = require('../sw-general.page-object');

class ManufacturerPageObject extends GeneralPageObject {
    constructor(browser) {
        super(browser);

        this.elements = {
            ...this.elements,
            ...{
                manufacturerSave: '.sw-manufacturer-detail__save-action'
            }
        };
    }

    createBasicManufacturer(manufacturerName) {
        this.browser
            .fillField('input[name=name]', manufacturerName)
            .fillField('input[name=link]', 'https://www.google.com/doodles')
            .fillField('.sw-text-editor__content-editor', 'De-scribe THIS!', false, 'editor')
            .waitForElementNotPresent('.icon--small-default-checkmark-line-medium')
            .click(this.elements.manufacturerSave)
            .waitForElementVisible('.icon--small-default-checkmark-line-medium');
    }

    addManufacturerLogo(imagePath) {
        this.browser
            .waitForElementNotPresent('.icon--small-default-checkmark-line-medium')
            .waitForElementVisible('.sw-media-upload__switch-mode')
            .click('.sw-media-upload__switch-mode')
            .fillField('input[name=sw-field--url]', imagePath)
            .click('.sw-media-url-form__submit-button')
            .checkNotification('A file has been saved successfully.');
    }

    deleteManufacturer(manufacturerName) {
        this.browser
            .waitForElementNotPresent(this.elements.loader)
            .clickContextMenuItem(this.elements.contextMenuButton, {
                menuActionSelector: `${this.elements.contextMenu
                }-item--danger`,
                scope: `${this.elements.gridRow}--0`
            })
            .expect.element(`${this.elements.modal}__body`).text.that.equals(`Are you sure you want to delete the manufacturer "${manufacturerName}"?`);

        this.browser
            .click(`${this.elements.modal}__footer ${this.elements.primaryButton}`)
            .waitForElementNotPresent(this.elements.modal);
    }
}

module.exports = (browser) => {
    return new ManufacturerPageObject(browser);
};
