/**
 * There is no need to validate on front-end side if date from and to range is correct,
 * because jQuery UI datepicker already has cared about this and doesn't allow to setup invalid range.
 */

let validators = {

    "company-symbol": function(value) {
        if (value.trim() == '') {
            return;
        }
        let deferred = $.Deferred();
        $.ajax({
            url: "/companies/" + value,
        }).done(function() {
            deferred.resolve();
        }).fail(function(jqXHR) {
            if (jqXHR.status == 404) {
                deferred.reject('Company symbol "' + value +'" is not found.');
                return;
            }
            alert("Server error is occurred!:(");
        });
        return deferred.promise();
    },

    /**
     *  Despite validators below are not async those return already resolved deferred object
     * in order to be consistent with async validators, like "company-symbol" above.
     */
    "required": function(value) {
        let deferred = $.Deferred();
        if (value.trim() == '') {
            return deferred.reject('This value should not be blank.').promise();
        }
        return deferred.resolve().promise();
    },
    "email": function(value) {
        let deferred = $.Deferred();
        let emailPattern = /^.+\@\S+\.\S+$/;
        if (!emailPattern.test(value)) {
            return deferred.reject('This value is not a valid email address.').promise();
        }
        return deferred.resolve().promise();
    },
    "date": function(value) {
        let deferred = $.Deferred();
        if (!moment(value, "YYYY-MM-DD", true).isValid()) {
            return deferred.reject('This value is not a valid date.').promise();
        }
        return deferred.resolve().promise();
    },
};

$('input[data-validation]').on('change', function(event) {
    validateInputs($(event.target));
});

function validateInputs(inputs) {

    inputs.siblings('.invalid-feedback').remove();
    inputs.removeClass('is-invalid');

    let promisesList = [];
    inputs.each(function(index, input) {
        input = $(input);
        for (let rule of input.data('validation')) {

            if (!(rule in validators)) {
                continue;
            }
            
            let promise = validators[rule](input.val());
            promisesList.push(promise);
            promise.fail(function(message) {
                input.addClass('is-invalid');
                input.after("<div class=\"invalid-feedback\">" + message + "</div>");
                $("button[type='submit']").prop('disabled', true);
            });
        }
    });
    
    return $.when.apply($, promisesList).done(function() {
        if ($('.is-invalid').length == 0) {
            $("button[type='submit']").prop('disabled', false);
        }
    });
};