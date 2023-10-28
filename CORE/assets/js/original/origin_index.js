if (baseUrl === undefined) var baseUrl = 'http://localhost:6059/';

// Constanta
$.const = {};
$.const.baseUrl = baseUrl;
// $.const.cdnUrl = $.const.baseUrl.replace('://', '://cdn.');
// $.const.apiUrl = $.const.baseUrl.replace('://', '://api.');
$.const.cdnUrl = $.const.baseUrl + 'myu_cdn/';
$.const.apiUrl = $.const.baseUrl + 'myu_api/';
$.const.accountUrl = $.const.baseUrl.replace('://', '://accounts.');
$.const.basic = 'Basic ZGFwdXJmaXJfdXNlcjpkYXB1cmZpcl8xMjMxMjM=';
$.const.bearer = jsCookie.get('token');
if ([undefined, null, ''].indexOf($.const.bearer) >= 0) {

    $.const.bearer = null;
} else {

    $.const.bearer = 'Bearer ' + $.const.bearer;
}

$.const.authorization = $.const.bearer != null ? $.const.bearer : $.const.basic;


// Functions
$.notif = new _notifConsole(); // Notification
$.formCollect = new _formCollect(); // Collect form data

$.makeURL = { // Compile URL
    base: function () {

        const base = new _compileURL($.const.baseUrl);
        base.init();
        return base;
    },
    api: function () {

        const base = new _compileURL($.const.apiUrl);
        base.init();
        return base;
    },
    account: function () {

        const base = new _compileURL($.const.accountUrl);
        base.init();
        return base;
    },
    cdn: function () {

        const base = new _compileURL($.const.cdnUrl);
        base.init();
        return base;
    }
};

$.token = { // Update API Token
    update: function (value = null) {

        // Update token
        let baseUrl = $.const.baseUrl.strReplace(['https', 'http', '://', ':8083'], '');
        baseUrl = baseUrl.substr(0, baseUrl.length - 1);

        jsCookie.save({
            name: 'token',
            value: value,
            domain: '.' + baseUrl
        });

        let accountURL = $.const.accountUrl.strReplace(['https', 'http', '://', ':8083'], '');
        accountURL = accountURL.substr(0, accountURL.length - 1);

        jsCookie.save({
            name: 'token',
            value: value,
            domain: accountURL
        });

        // Get token
        $.const.bearer = jsCookie.get('token');
    }
};

$.modalBox = new _modalBox();

// Ajax setup
$.ajaxSetup({
    headers: {
        'Authorization': $.const.authorization
    },
    crossDomain: true,
    processData: false,
    contentType: false
});

/*
    ***********
    jQuery Method
    ***********
*/
(function ($) {

    // Set attribute to multiple elements at once
    $.fn.attrs = function (attribute, value = '') {

        if (typeof attribute == 'object') {

            $.each(this, function () {

                $(this).attr(attribute);
            });
        } else {

            $.each(this, function () {

                $(this).attr(attribute, value);
            });
        }
    };

    // Remove attribute from multiple elements at once
    $.fn.removeAttrs = function (attribute) {

        $.each(this, function () {

            $(this).removeAttr(attribute);
        });
    };

    // Get element class list
    $.fn.classList = function () {

        return this[0].classList;
    };

    // Get element class exist
    $.fn.classExists = function (attribute) {

        let returnValue = $.inArray(attribute, this[0].classList);

        return returnValue >= 0 ? true : false;
    };

    // Remove Element with fade
    $.fn.removeFading = function (duration = 500) {

        $.each(this, function () {

            this.style.transition = duration + 'ms';
            this.style.opacity = '0';

            setTimeout((elem) => {

                elem.remove();
            }, (duration + 1), this);

        });
    };
})(jQuery);


/*
    ***********
    jQuery Events
    ***********
*/

// ### Foldable box
$('body').on('click', '.fold_box > .initial', function () {

    $($(this).parents('.fold_box')[0]).toggleClass('expand');
});


// ### Image loading lazy
$('*[loading="lazy"]').on('load', function () {

    $(this).removeAttr('loading');
});

// ### Force input caret to end of value
$('body').on('focusin', 'input[forcetoend="true"]', function () {

    setTimeout(() => {

        this.selectionStart = this.selectionEnd = this.value.length;
    }, 1);
});


// ### Quantity control
HTMLElement.prototype.qtyControls = function () {

    let jsonFunc = {};

    // Prepare quantity controller
    jsonFunc.prep = () => {

        let inpTarget = $(this).find('input'),
            min = parseInt($(inpTarget).attr('min')),
            max = parseInt($(inpTarget).attr('max')),
            value = parseInt($(inpTarget).val());

        if (isNaN(value)) value = min;

        if (value >= max) $(this).find('button#inc').attr('disabled', '');
        else $(this).find('button#inc').removeAttr('disabled');

        if (value <= min) $(this).find('button#dec').attr('disabled', '');
        else $(this).find('button#dec').removeAttr('disabled');
    };

    // Control quantity with button
    jsonFunc.btnQty = () => {

        let elemPar = $(this).parents('*[class*="qty_field"]')[0],
            inpTarget = $(elemPar).find('input'),
            value = $(inpTarget).val(),
            buttonId = $(this).attr('id');

        if (buttonId == 'dec') value--;
        else if (buttonId == 'inc') value++;

        $(inpTarget).focus();
        $(inpTarget).val(value).blur().change();
    };

    // Control quantity with input
    jsonFunc.inputQty = () => {

        let elemPar = $(this).parents('*[class*="qty_field"]')[0],
            min = $(this).attr('min'),
            max = $(this).attr('max'),
            value = parseInt($(this).val());

        if ($.inArray(value, [undefined, null, '']) < 0
            && !isNaN(value)) {

            // Decrease button
            if (value > min) $(elemPar).find('button#dec').removeAttr('disabled');
            else $(elemPar).find('button#dec').attr('disabled', '');

            // Increase button
            if (value < max) $(elemPar).find('button#inc').removeAttr('disabled');
            else $(elemPar).find('button#inc').attr('disabled', '');
        }
    };

    // Default value
    jsonFunc.defaultVal = () => {

        let elemPar = $(this).parents('*[class*="qty_field"]')[0],
            min = $(this).attr('min'),
            max = $(this).attr('max'),
            value = parseInt($(this).val());

        if ($.inArray(value, [undefined, null, '']) < 0
            && !isNaN(value)) {

            if (value < min) $(this).val(min);
            else if (value > max) $(this).val(max);
        }
    };

    return jsonFunc;
};
$('body').on('click', '*[class*="qty_field"] button#dec, *[class*="qty_field"] button#inc', function () {

    this.qtyControls().btnQty();
}).on('input change', 'input', function () {

    this.qtyControls().inputQty();
}).on('focusin focusout', 'input', function () {

    this.qtyControls().defaultVal();
}).find('*[class*="qty_field"]').each(function () {

    this.qtyControls().prep();
}).end();


// ### Switches
HTMLElement.prototype.switches = function () {

    jsonFunc = {};

    // Prepare switches
    jsonFunc.prep = () => {

        let obey = this.getAttribute('obey-for') || null;
        let status = this.getAttribute('switch') == 'true' ? true : false || false;

        if (obey != null) {

            // This switch must obey other switch
            let obeyTarget = $('body').find('input[type="checkbox"][id="' + obey + '"]')[0];

            $(obeyTarget).prop('checked')
                ? $(this).removeAttr('disabled')
                : $(this).attr('disabled', '');
        }

        $(this).find('input[type="checkbox"]')
            .prop('checked', status).change();
    };

    // Switch switches
    jsonFunc.switch = () => {

        let label = $(this).parents('label[type="switch"]');
        let mandatoryId = $(this).attr('mandatory-id') || null;

        $(this).prop('checked')
            ? $(label).attr('switch', 'true')
            : $(label).attr('switch', 'false');

        if (mandatoryId != null) {

            if ($(this).prop('checked')) {

                $('body')
                    .find(`label[type="switch"][obey-for="${mandatoryId}"]`).removeAttr('disabled')
                    .find(`input[type="checkbox"]`).removeAttr('disabled');
            } else {

                $('body')
                    .find(`label[type="switch"][obey-for="${mandatoryId}"]`).attr('disabled', '')
                    .find(`input[type="checkbox"]`).attr('disabled', '');
            }
        }
    };

    return jsonFunc;
};
$('body').on('change', 'input[type="checkbox"]', function (elem) {

    this.switches().switch();
}).find('label[type="switch"]').each(function () {

    this.switches().prep();
});


// ### Label checkbox
HTMLElement.prototype.checkboxes = function () {

    let jsonFunc = {};

    // Prepare checkboxes
    jsonFunc.prep = () => {

        let status = ([undefined, null].indexOf(this.getAttribute('checked')) < 0) ? true : false;

        // Checkbox status
        if (status) {

            $(this).find('input[type="checkbox"]')
                .prop('checked', true);
        } else {

            $(this).find('input[type="checkbox"]')
                .prop('checked', false);
        }
    };

    // When label checkbox clicked
    jsonFunc.cxLabel = () => {

        // Label checkbox single
        let checkbox = $(this).find('input[type="checkbox"]');

        $('body')
            .find(`input[type="checkbox"][name="${$(checkbox).attr('name')}"][id!="${$(checkbox).attr('id')}"]`)
            .prop('checked', false)
            .change();
    };

    // When input checkbox changed
    jsonFunc.cxInput = () => {

        let label = $(this).parents('label[type="checkbox"]');

        $(this).prop('checked')
            ? $(label).attr('checked', '')
            : $(label).removeAttr('checked');
    };

    return jsonFunc;
};
$('body').on('click', 'label[type="checkbox"][multi="false"]', function (evt) {

    this.checkboxes().cxLabel();
}).on('change', 'input[type="checkbox"]', function (evt) {

    this.checkboxes().cxInput();
}).find('label[type="checkbox"]').each(function () {

    this.checkboxes().prep();
});


// ### Resizable textarea
HTMLElement.prototype.textareas = function () {

    let jsonFunc = {};

    // Resize textarea
    jsonFunc.resize = () => {

        let minRow = this.getAttribute('min-row') || 1;

        const txareaHelper = document.createElement('textarea');
        txareaHelper.id = 'tx_resizer';
        txareaHelper.style.overflow = 'scroll';
        txareaHelper.style.zIndex = '-1';
        txareaHelper.style.boxSizing = 'border-box';
        txareaHelper.style.padding = $(this).css('padding');
        txareaHelper.style.lineHeight = parseInt($(this).css('line-height'), 10) - 2;
        txareaHelper.style.width = this.offsetWidth + 'px';
        txareaHelper.style.position = 'fixed';
        txareaHelper.value = this.value;

        document.body.appendChild(txareaHelper);

        txareaHelper.setAttribute('rows', minRow);
        lh = parseInt($(txareaHelper).css('line-height'), 10),
            rows = Math.floor(txareaHelper.scrollHeight / lh);

        txareaHelper.setAttribute('rows', rows);
        txareaHelper.remove();

        this.setAttribute('rows', rows);
    };

    return jsonFunc;
};
$('body').on('keyup change focusin', 'textarea[ptx_resizable]', function (event) {

    this.textareas().resize();
}).find('textarea[ptx_resizable]').each(function () {

    this.textareas().resize();
});


// ### Textarea chars counter
HTMLElement.prototype.countChars = function () {

    let valLength = this.value.length,
        maxLength = this.getAttribute('maxlength');

    let parent = $(this).parents('.input_item');

    if ($(parent).find('.counter').length <= 0)
        $(parent).append(`<div class="counter">${valLength}/${maxLength}</div>`);

    $(parent).find('.counter').html(`${valLength}/${maxLength}`);
};
$('body').on('input', '*[class*="tx_field"] textarea[maxlength]', function () {

    this.countChars();
}).find('*[class*="tx_field"] textarea[maxlength]').each(function () {

    this.countChars();
});


// ### Input currency
HTMLElement.prototype.inputCurrency = function () {

    let jsonFunc = {};

    // Prepare input currency
    jsonFunc.prep = () => {

        this.setAttribute('pattern', '[0-9]*');

        // Placeholder
        if ([undefined, null, ''].indexOf(this.getAttribute('placeholder')) < 0) {

            let placeholder = this.getAttribute('placeholder');
            placeholder = parseInt(placeholder.replaceAll('.', ''));

            this.setAttribute('placeholder', rupiah(placeholder));
        }

        // Value
        if ([undefined, null, ''].indexOf(this.getAttribute('value')) < 0) {

            let value = this.getAttribute('value');
            value = parseInt(value.replaceAll('.', ''));

            this.setAttribute('value', rupiah(value));
        }
    };

    // Format input number to currency
    jsonFunc.format = () => {

        // Only allow number 0-9
        this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\.*?)\.*/g, '$1');

        let value = this.value.replaceAll('.', '');
        value = rupiah(value);

        this.value = value;
    };

    return jsonFunc;
};
$('body').on('change input', 'input[ptx_format="currency"]', function () {

    this.inputCurrency().format();
}).find('input[ptx_format="currency"]').each(function () {

    this.inputCurrency().prep();
});

// ### Input number maxlength
$('body').on('keypress', 'input[type="number"][maxlength]', function (evt) {

    let maxLength = $(this).attr('maxlength'),
        value = $(this).val();

    if ($.inArray(maxLength, [undefined, null, '']) < 0) {

        if (evt.keyCode != 8
            && value.length >= maxLength) {

            return false;
        }
    }
});

// ### On scroll animation
$('body').on('scroll', function (evt) {

    $(this).find('.anim_onscroll').each(function () {

        if (this.boundingStatus(20, -30)['vertical']) {

            let classList = $(this).classList();

            $.each(classList, (key, val) => {

                if (val.includes('anim')
                    && val != 'anim_onscroll') {

                    $(this)
                        .removeClass(val)
                        .removeClass('anim_onscroll');

                    setTimeout(() => {

                        $(this).addClass(val);
                    }, 1);
                    return false;
                }
            });
        }
    });
});