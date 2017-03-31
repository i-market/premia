module.exports = {
  'Signup' : function(browser) {
    browser
      .url(browser.launch_url)
      .waitForElementVisible('header .reg', 1000)
      .click('header .reg')
      .waitForElementVisible('#signup-modal', 1000)
      .assert.containsText('#signup-modal', 'Регистрация участника')
      .elements('css selector', '#signup-modal input', function(result) {
        result.value.forEach(function(el) {
          browser.elementIdValue(el.ELEMENT, 'some value');
        })
      })
      .clearValue('#signup-modal input[name=email]')
      .setValue('#signup-modal input[name=email]', 'some@email.ru')
      .click('#signup-modal [type=submit]')
      .waitForElementVisible('#signup-modal .form-message', 5000)
      .assert.containsText('#signup-modal .form-message', 'успешно')
      .end();
  }
};