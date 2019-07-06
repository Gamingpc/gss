const checkoutPage = require('./../../page-objects/checkout.page-object.js');

let currentProduct = '';

module.exports = {
    '@tags': ['checkout', 'checkout-basic'],
    before: (browser, done) => {
        global.CustomerFixtureService.setCustomerFixture().then(() => {
            return global.ProductFixtureService.setProductFixture();
        }).then((result) => {
            return global.ProductFixtureService.setProductVisible(result);
        }).then(() => {
            return global.ProductFixtureService.search('product', {
                value: global.ProductFixtureService.productFixture.name
            });
        })
            .then((data) => {
                currentProduct = data;
                done();
            });
    },
    'find product': (browser) => {
        browser
            .url(process.env.APP_URL)
            .waitForElementVisible('input[name=search]')
            .setValue('input[name=search]', currentProduct.attributes.name)
            .expect.element('.result-product .result-link').to.have.text.that.contains(currentProduct.attributes.name);

        browser
            .click('.result-product .result-link')
            .waitForElementVisible('.product-detail-content')
            .assert.containsText('.product-detail-name', currentProduct.attributes.name)
            .assert.containsText('.product-detail-price', currentProduct.attributes.price.gross)
            .assert.containsText('.product-detail-ordernumber', currentProduct.attributes.productNumber);
    },
    'add product to card': (browser) => {
        const page = checkoutPage(browser);

        browser
            .click('.btn-buy')
            .waitForElementVisible(`${page.elements.offCanvasCart}.is-open`);
    },
    'check off-canvas cart and continue': (browser) => {
        const page = checkoutPage(browser);

        browser
            .waitForElementVisible(page.elements.cartItem)
            .assert.containsText('.cart-item-label', currentProduct.attributes.name)
            .assert.containsText('.cart-item-price', currentProduct.attributes.price.gross)
            .assert.containsText('.summary-value.summary-total', currentProduct.attributes.price.gross)
            .click('.offcanvas-cart-actions .btn-link')
            .waitForElementVisible('.card-body');
    },
    'check checkout page and continue': (browser) => {
        const page = checkoutPage(browser);

        browser
            .waitForElementVisible('.card-body')
            .assert.containsText(`${page.elements.cartItem}-label`, currentProduct.attributes.name)
            .assert.containsText(`${page.elements.cartItem}-unit-price`, currentProduct.attributes.price.gross)
            .assert.containsText('.checkout-summary-list .summary-value.summary-total', currentProduct.attributes.price.gross)
            .click(`.checkout-aside-action ${page.elements.primaryButton}`);
    },
    'log in customer': (browser) => {
        const page = checkoutPage(browser);

        browser
            .waitForElementVisible('.checkout.is-register')
            .click('.login-collapse-toggle')
            .waitForElementVisible('.login-form')
            .fillField('#loginMail', 'test@example.com')
            .fillField('#loginPassword', 'shopware')
            .click(`.login-submit ${page.elements.primaryButton}`);
    },
    'check checkout confirm page': (browser) => {
        const page = checkoutPage(browser);

        browser.expect.element(page.elements.cardTitle).to.have.text.that.contains('Terms, conditions and cancellation policy');

        browser
            .waitForElementVisible('.confirm-address')
            .assert.containsText('.confirm-address', 'Pep Eroni')
            .getLocationInView('.checkout-main')
            .waitForElementVisible('.checkout-main')
            .assert.containsText(`${page.elements.cartItem}-label`, currentProduct.attributes.name)
            .assert.containsText(`${page.elements.cartItem}-total-price`, currentProduct.attributes.price.gross)
            .assert.containsText('.cart-item-total-price', currentProduct.attributes.price.gross)
            .assert.containsText('.checkout-summary-list .summary-value.summary-total', currentProduct.attributes.price.gross);
    },
    'finish order': (browser) => {
        browser
            .waitForElementVisible('.custom-checkbox label')
            .moveToElement('.custom-checkbox label', 1, 1)
            .mouseButtonClick('left')
            .getLocationInView('#confirmFormSubmit')
            .waitForElementVisible('#confirmFormSubmit')
            .click('#confirmFormSubmit')
            .waitForElementVisible('.finish-header')
            .expect.element('.finish-header').to.have.text.that.contains('Thank you for your order with Shopware Storefront!');
    },
    after: (browser) => {
        browser.end();
    }
};
