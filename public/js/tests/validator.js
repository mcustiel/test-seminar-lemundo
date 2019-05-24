
describe("Validator", function() {

    const validator = new Validator();

    describe ("isString method", function () {

        it("validates correctly if value is a string", function() {
            const value = 'a string';

            expect(validator.isString(value)).toBe(true);
        });

        it("validates correctly if value is not a string", function() {
            const value = 40;

            expect(validator.isString(value)).toBe(false);
        });

    })

});
