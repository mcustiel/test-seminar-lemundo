
describe("Validator", function() {

    let validator;

    beforeEach(function() {
        validator = new Validator();
    });

    describe ("ensuresString method", function () {

        it("does not throw error when value is a string", function() {
            const value = 'I am a string';

            expect(function() { validator.ensureIsString(value); }).not.toThrow();
        });

        it("validates correctly if value is not a string", function() {
            const value = 40;

            expect(function() { validator.ensureIsString(value); }).toThrow(new Error('Expected string. Got number'));
        });

    });

});
