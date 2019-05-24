/**
 * @constructor
 */
let MessageBoxController = function (document) {

    let errorMessage;

    function construct() {
        errorMessage = document.getElementById('message');
    }

    this.showError = function (error) {
        errorMessage.style.display = 'block';
        errorMessage.style.color = 'red';
        errorMessage.innerHTML = error;
    };

    this.showSuccess = function () {
        errorMessage.style.display = 'block';
        errorMessage.style.color = 'green';
        errorMessage.innerHTML = 'Translation was successfully set';
    };

    this.hideMessage = function () {
        errorMessage.style.display = 'none';
        errorMessage.innerHTML = '';
    };

    construct();

};
