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
    const ClassConstructor = function (documentElement, validator) {

        let element, submitListener = function () {}, self = this;

        const
            ensureValidLocale = function () {
                let locale = self.getLocaleValue();
                validator.ensureIsString(locale);
                validator.ensureIsValidLocale(locale);
            },

            ensureValidIdentifier = function () {
                let identifier = self.getIdentifierValue();
                validator.ensureIsString(identifier);
                validator.ensureIsShorterOrEqualThan(MAX_LENGTH, identifier);
                validator.ensureIsLongerOrEqualThan(MIN_LENGTH, identifier);
            },

            ensureValidText = function () {
                let text = self.getTextValue();
                validator.ensureIsString(text);
                validator.ensureIsNotEmpty(text);
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
                event.stopPropagation()
                return false;
            },

            constructor = function () {
                element = documentElement.getElementById(FORM_ID);
                element.onsubmit = onSubmitHandler;
            };

        this.listenSubmit = function (callback) {
            if (typeof callback !== 'function') {
                throw new Error('Expected callback to listen form submit event');
            }
            submitListener = callback;
        };

        this.getLocaleValue = function () {
            const select = documentElement.getElementById(LOCALE_SELECT_ID);
            return select.options[select.selectedIndex].value;
        };

        this.getIdentifierValue = function () {
            return documentElement.getElementById(IDENTIFIER_INPUT_ID).value;
        };

        this.getTextValue = function () {
            return documentElement.getElementById(TEXT_TEXTAREA_ID).value;
        };

        constructor();
    };

    return ClassConstructor;

})();
