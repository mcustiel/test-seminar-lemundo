/**
 * @constructor
 */
let Validator = function () {

    const VALID_LOCALES = ['en_GB', 'de_DE', 'de_CH', 'de_AU'];

    this.ensureIsString = function (value) {
        if (typeof value !== 'string') {
            throw new Error('Expected string. Got ' + typeof value);
        }
    };

    this.ensureIsShorterOrEqualThan = function(size, value) {
        if (value.length > size) {
            throw new Error('String is too short. Minimum length is ' + size + ' but has ' + value.length);
        }
    };

    this.ensureIsLongerOrEqualThan = function(size, value) {
        if (value.length < size) {
            throw new Error('String is too long. Maximum length is ' + size + ' but has ' + value.length);
        }
    };

    this.ensureIsNotEmpty = function(value) {
        if (value === '') {
            throw new Error('Got empty string');
        }
    };

    this.ensureIsValidLocale = function (value) {
        if (VALID_LOCALES.indexOf(value) < 0) {
            throw new Error('Expected valid locale. Got: ' + typeof value);
        }
    };

};
