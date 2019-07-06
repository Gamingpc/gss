const loginPage = require('administration/page-objects/module/sw-login.page-object.js');

module.exports = {
    '@tags': ['profile-edit', 'profile', 'edit'],
    'open user profile and edit values': (browser) => {
        const page = loginPage(browser);

        browser
            .waitForElementVisible('.sw-dashboard-index__content')
            .waitForElementNotPresent('.sw-admin-menu__user-actions-toggle .sw-loader')
            .openUserActionMenu()
            .click('.sw-admin-menu__profile-item')
            .expect.element(page.elements.smartBarHeader).to.have.text.that.equals('Your profile');

        browser
            .fillField('input[name=sw-field--user-email]', 'test@test.de', true)
            .click(page.elements.primaryButton)
            .waitForElementVisible('.icon--small-default-checkmark-line-medium');
    },
    'log out': (browser) => {
        const page = loginPage(browser);
        browser.openUserActionMenu();
        page.logout();
    },
    'log in user with updated credentials': (browser) => {
        const page = loginPage(browser);
        page.login('admin', 'shopware');
    },
    'verify changed data': (browser) => {
        const page = loginPage(browser);

        browser
            .waitForElementVisible('.sw-dashboard-index__content')
            .openUserActionMenu()
            .click('.sw-admin-menu__profile-item')
            .expect.element(page.elements.smartBarHeader).to.have.text.that.equals('Your profile');
        browser
            .expect.element('input[name=sw-field--user-email]').to.have.value.that.equals('test@test.de');
    }
};
