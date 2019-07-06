const productPage = require('administration/page-objects/module/sw-product.page-object.js');

module.exports = {
    '@tags': ['category', 'category-create', 'create'],
    before: (browser, done) => {
        global.ProductFixtureService.setProductFixture().then(() => {
            done();
        });
    },
    'go to category module': (browser) => {
        browser
            .openMainMenuEntry({
                targetPath: '#/sw/category/index',
                mainMenuId: 'sw-catalogue',
                subMenuId: 'sw-category'
            });
    },
    'make sure the system default language is set to english': (browser) => {
        browser
            .expect.element('.smart-bar__language-switch').to.have.text.that.contains('English');
    },
    'wait for tree visible': (browser) => {
        browser
            .expect.element('.sw-tree-actions__headline').to.have.text.that.contains('Category structure');
    },
    'add category after default category': (browser) => {
        const page = productPage(browser);
        browser
            .waitForElementVisible('.sw-tree-item__icon')
            .clickContextMenuItem(page.elements.contextMenuButton, {
                menuActionSelector: '.sw-tree-item__after-action'
            })
            .fillField('.sw-tree-item__content input', 'Category after first')
            .keys(browser.Keys.ENTER)
            .waitForElementVisible('.sw-confirm-field__button--cancel')
            .click('.sw-confirm-field__button--cancel')
            .waitForElementVisible('.sw-tree-item:nth-child(2)')
            .refresh()
            .expect.element('.sw-tree-item:nth-child(2)').to.have.text.that.equals('Category after first');
    },
    'add category before current': (browser) => {
        const page = productPage(browser);
        browser
            .waitForElementVisible('.sw-tree-item__icon')
            .clickContextMenuItem(page.elements.contextMenuButton, {
                menuActionSelector: '.sw-tree-item__before-action'
            })
            .fillField('.sw-tree-item__content input', 'Category before')
            .keys(browser.Keys.ENTER)
            .waitForElementVisible('.sw-confirm-field__button--cancel')
            .click('.sw-confirm-field__button--cancel')
            .waitForElementVisible('.sw-tree-item:nth-child(3)')
            .refresh()
            .expect.element('.sw-tree-item:nth-child(1)').to.have.text.that.equals('Category before');
    },
    'add subcategory': (browser) => {
        const page = productPage(browser);
        browser
            .waitForElementVisible('.sw-tree-item__icon')
            .clickContextMenuItem(page.elements.contextMenuButton, {
                menuActionSelector: '.sw-tree-item__sub-action'
            })
            .waitForElementPresent('.sw-tree-item__content input')
            .fillField('.sw-tree-item__content input', 'Subcategory')
            .keys(browser.Keys.ENTER)
            .waitForElementVisible('.sw-confirm-field__button--cancel')
            .click('.sw-confirm-field__button--cancel')
            .refresh()
            .waitForElementVisible('.icon--small-arrow-small-right')
            .click('.icon--small-arrow-small-right')
            .waitForElementVisible('.icon--small-arrow-small-down')
            .waitForElementNotPresent(page.elements.loader)
            .expect.element('.sw-tree-item__children').to.have.text.that.equals('Subcategory');
    },
    'set category active': (browser) => {
        const page = productPage(browser);
        browser
            .click('.sw-tree-item')
            .waitForElementNotPresent(page.elements.loader)
            .expect.element('#categoryActive').to.not.be.selected;
        browser.tickCheckbox('#categoryActive', true);
    },
    'set product assignment': (browser) => {
        browser
            .moveToElement('.sw-select__input-single', 15, 5)
            .mouseButtonClick()
            .waitForElementVisible('.sw-select-option--0')
            .click('.sw-select-option--0')
            .expect.element('.sw-grid__row--0 ').to.have.text.that.contains('Product name');
    },
    'save and verify product assignment': (browser) => {
        const page = productPage(browser);
        browser
            .getLocationInView(page.elements.primaryButton)
            .click(page.elements.primaryButton)
            .waitForElementVisible('.icon--small-default-checkmark-line-medium');
    }
};
