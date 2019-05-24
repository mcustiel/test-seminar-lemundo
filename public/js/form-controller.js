let SetTranslationFormController = (function() {
    const
        MIN_LENGTH = 3,
        MAX_LENGTH = 32,
        FORM_ID = 'set-translation-form',
        LOCALE_SELECT_ID = 'locale-selection',
        IDENTIFIER_INPUT_ID = 'identifier',
        TEXT_TEXTAREA_ID = 'text'
    ;

    /**
     * @param {Validator} validator
     * @constructor
     */
    const ClassConstructor = function (document, validator) {

        let element, submitListener = function () {}, self = this;

        const
            ensureValidLocale = function () {
                let locale = self.getLocaleValue();
                if (!validator.isString(locale)) {
                    throw new Error('Expected string ')
                }
                if (!validator.isValidLocale(locale)) {
                    throw new Error('Expected valid locale ')
                }
            },

            ensureValidIdentifier = function () {
                let identifier = self.getIdentifierValue();
                if (!validator.isString(identifier)) {
                    throw new Error('Expected string ')
                }
                if (validator.isShorterThan(MIN_LENGTH, identifier)) {
                    throw new Error('Identifier too short')
                }
                if (validator.isLongerThan(MAX_LENGTH, identifier)) {
                    throw new Error('Identifier too long')
                }
            },

            ensureValidText = function () {
                let text = self.getTextValue();
                if (!validator.isString(text)) {
                    throw new Error('Expected string for text')
                }
                if (validator.isEmpty(text)) {
                    throw new Error('Text is empty')
                }
            },

            onSubmitHandler = function (event) {
                let result = {
                    valid: false
                };
                try {
                    ensureValidLocale();
                    ensureValidIdentifier();
                    ensureValidText();

                    result.valid = true;
                } catch (e) {
                    result.error = e;
                }
                submitListener(result);
            },

            constructor = function () {
                element = document.getElementById(FORM_ID);
                element.onsubmit = onSubmitHandler;
            };

        this.listenSubmit = function (callback) {
            if (typeof callback !== 'function') {
                throw new Error('Expected callback to listen form submit event');
            }
            submitListener = callback;
        };

        this.getLocaleValue = function () {
            const select = document.getElementById(LOCALE_SELECT_ID);
            return select.options[select.selectedIndex].value;
        };

        this.getIdentifierValue = function () {
            return document.getElementById(IDENTIFIER_INPUT_ID).value;
        };

        this.getTextValue = function () {
            return document.getElementById(TEXT_TEXTAREA_ID).value;
        };

        constructor();
    };

    return ClassConstructor;

})();
