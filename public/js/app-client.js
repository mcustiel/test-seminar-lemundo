let AppClient = (function () {

    const LOCALE_TRANSLATIONS_ENDPOINT = '/locale/{locale}/translation',
        TRANSLATION_ENDPOINT = '/locale/{locale}/translation/{translation_id}';

    /**
     * @param {Ajax} ajaxAdapter
     * @constructor
     */
    const ClassConstructor = function (ajaxAdapter) {

        this.setTranslation = function (locale, identifier, text, callback) {
            ajaxAdapter.putJson(
                LOCALE_TRANSLATIONS_ENDPOINT.split('{locale}').join(locale),
                JSON.stringify({id: identifier, text: text}),
                callback
            );
        };

    };

    return ClassConstructor;

})();
