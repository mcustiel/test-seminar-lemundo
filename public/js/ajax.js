/**
 * @constructor
 */
var Ajax = function() {

    const validateArguments = function (url, data, callback) {
        if (typeof url !== 'string') {
            throw new Error('Expected string as url');
        }

        if (typeof data !== 'string') {
            throw new Error('Expected string as data');
        }

        if (typeof callback !== 'function') {
            throw new Error('Expected function as callback');
        }
    };

    /**
     * @param {string} url
     * @param {string} data
     * @param {function} callback
     */
    this.putJson = function (url, data, callback) {
        validateArguments(url, data, callback);

        const request = new XMLHttpRequest();

        request.addEventListener('load', function () {
            callback(this.status, this.response);
        });
        request.open('PUT', url);
        request.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
        request.send(data);
    };
};