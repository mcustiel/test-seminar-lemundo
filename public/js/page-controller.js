/**
 * @param {SetTranslationFormController} formController
 * @param {MessageBoxController} messageController
 * @param {AppClient} appClient
 * @constructor
 */
let SetTranslationPageController = function(formController, messageController, appClient) {

    const
        setTranslationResponseHandler = function (statusCode, response) {
            if (statusCode === 201) {
                messageController.showSuccess();
                return;
            }
            messageController.showError(statusCode + ': ' + response);
        },

        submitHandler = function(validationResult) {
            if (!validationResult.valid) {
                messageController.showError(validationResult.error);
            } else {
                appClient.setTranslation(
                    formController.getLocaleValue(),
                    formController.getIdentifierValue(),
                    formController.getTextValue(),
                    setTranslationResponseHandler
                );
            }
            setTimeout(messageController.hideMessage, 5000);
        };

    this.init = function() {
        formController.listenSubmit(submitHandler);
    };

};
