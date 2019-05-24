
describe("Validator", function() {
    var validator = new Validator();

    describe ("isString method", function () {

        it("validates correctly if value is a string", function() {
            let value = 'a string';

            expect(validator.isString(value)).toBe(true);
        });

        it("validates correctly if value is not a string", function() {
            let value = 40;

            expect(validator.isString(value)).toBe(false);
        });

    })

});

