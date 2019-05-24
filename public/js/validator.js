/**
 * @constructor
 */
let Validator = function () {

    this.isString = function (value) {
        return typeof value === 'string';
    };

    this.isLongerThan = function(size, value) {
        return value.length > size;
    };

    this.isShorterThan = function(size, value) {
        return value.length < size;
    };

    this.isEmpty = function(value) {
        return value === '';
    };

    this.isValidLocale = function (value) {
        return ['en_GB', 'de_DE', 'de_CH', 'de_AU'].indexOf(value) >= 0;
    };

};
