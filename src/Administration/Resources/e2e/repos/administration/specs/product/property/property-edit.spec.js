const propertyPage = require('administration/page-objects/module/sw-property.page-object.js');

module.exports = {
    '@tags': ['product', 'property', 'property-edit', 'edit'],
    before: (browser, done) => {
        return global.PropertyFixtureService.setPropertyFixture({
            options: [{ name: 'Red' }, { name: 'Yellow' }, { name: 'Green' }]
        }).then(() => {
            done();
        });
    },
    'open product listing': (browser) => {
        const page = propertyPage(browser);

        browser
            .openMainMenuEntry({
                targetPath: '#/sw/property/index',
                mainMenuId: 'sw-catalogue',
                subMenuId: 'sw-property'
            })
            .expect.element(`${page.elements.dataGridRow}--0 a`).to.have.text.that.equals('Color');
    },
    'edit property group': (browser) => {
        const page = propertyPage(browser);

        browser
            .clickContextMenuItem(page.elements.contextMenuButton, {
                menuActionSelector: '.sw-context-menu-item',
                scope: `${page.elements.dataGridRow}--0`
            })
            .expect.element(page.elements.cardTitle).to.have.text.that.equals('Basic information');

        browser
            .fillField('input[name=sw-field--group-name]', 'Coleur', true)
            .click(page.elements.propertySaveAction)
            .waitForElementVisible('.icon--small-default-checkmark-line-medium');
    },
    'verify property in listing': (browser) => {
        const page = propertyPage(browser);

        browser
            .click(page.elements.smartBarBack)
            .refresh()
            .expect.element(`${page.elements.dataGridRow}--0 a`).to.have.text.that.equals('Coleur');
    }
};
