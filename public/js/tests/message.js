
describe("MessageBoxController", function() {

    let documentMock, messageElementMock, messageController;

    beforeEach(function () {
        messageElementMock = {
            style: {
                display: null,
                color: null
            },
            innerHTML: null
        };

        documentMock = jasmine.createSpyObj('document', ['getElementById']);
        documentMock.getElementById.and.returnValue(messageElementMock);

        messageController = new MessageBoxController(documentMock);

        expect(documentMock.getElementById).toHaveBeenCalledTimes(1);
        expect(documentMock.getElementById).toHaveBeenCalledWith('message');
    });

    describe ("hideMessage method", function () {

        it("erases the inner content of the message HTML element", function() {
            messageController.hideMessage();
            expect(messageElementMock.innerHTML).toEqual('');
        });

        it("changes display style to none", function() {
            messageController.hideMessage();
            expect(messageElementMock.style.display).toEqual('none');
        });

    });

});
