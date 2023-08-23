/*
    **********
    # Dexa Classes
    **********
*/

window.$dexa = function () {

    // 
};

// Redirect to url with post data
class _redirectPost {

    constructor() {
        this.postData = {};
    }

    init() {

        this.postData = {};
        return this;
    }

    setPostData(object = {}) {

        Object.keys(object).forEach((key) => {

            this.postData[key] = object[key];
        });

        return this;
    }

    to(url = '') {

        let form;

        if (Object.keys(this.postData).length >= 1) {

            form = document.createElement('form');
            form.setAttribute('action', url);
            form.style.display = 'none';
            form.setAttribute('method', 'post');

            Object.keys(this.postData).forEach((key) => {

                const input = document.createElement('input');
                input.setAttribute('type', 'hidden');
                input.setAttribute('value', this.postData[key]);
                input.setAttribute('name', key);

                form.append(input);
            });
        }

        const empty = [undefined, null, ''];

        if (
            empty.indexOf(url) < 0
            && url.length >= 1
        ) {

            if (Object.keys(this.postData).length >= 1) {

                const submit = document.createElement('input');
                submit.setAttribute('type', 'submit');
                submit.setAttribute('value', 'submit');

                form.append(submit);

                document.getElementsByTagName('body')[0].appendChild(form);

                form.submit();

                setTimeout(($this) => {

                    $this.remove();
                }, 150, form);

                return this.init();
            } else {

                window.location.href = url;

                return this.init();
            }
        }
    }
}

// App Console
class _notifConsole {

    constructor() {
        this.option = {
            text: '',
            duration: 1000,
            background: 'rgb(70, 70, 70)',
            colorType: 'bold'
        };
    }

    // Method
    removeElem(elem) {

        elem.style.transform = 'translate3d(-50%, 100%, 1px) scale(1)';
        elem.style.opacity = '0';

        setTimeout((elem) => {

            elem.remove();
        }, 301, elem);
    }

    base() {

        const elem = document.createElement('div');

        elem.innerHTML = this.option.text;

        elem.style.cursor = 'default';
        elem.style.transition = '300ms';
        elem.style.width = 'fit-content';
        elem.style.textAlign = 'center';
        elem.style.maxWidth = '90vw';
        elem.style.borderRadius = '10px';
        elem.style.fontSize = '14px';
        elem.style.padding = '15px 20px';
        elem.style.boxSizing = 'border-box';
        elem.style.fontWeight = 'normal';
        elem.style.position = 'fixed';
        elem.style.bottom = '20px';
        elem.style.left = '50%';
        elem.style.transform = 'translate3d(-50%, 0, 1px) scale(0)';
        elem.style.zIndex = '999';
        elem.style.background = this.option.background;

        if (this.option.colorType == 'bold') {

            elem.style.color = '#fff';
            elem.style.border = '1px solid transparent';
        } else if (this.colorType == 'light') {

            elem.style.color = 'rgb(20, 20, 20)';
            elem.style.border = this.option.border;
        }

        document.getElementsByTagName('body')[0].appendChild(elem);

        setTimeout((elem) => {

            elem.style.transform = 'translate3d(-50%, 0, 1px) scale(1)';

            elem.addEventListener('click', () => {

                this.removeElem(elem);
            });

            setTimeout((elem) => {

                this.removeElem(elem);
            }, this.option.duration, elem);
        }, 100, elem);
    }

    default(text = '', duration = 1500, colorType = 'bold') {

        this.option.background = 'rgb(70, 70, 70)';
        this.option.border = '1px solid rgb(70, 70, 70)';

        if (colorType == 'light') this.option.background = 'rgb(200, 200, 200)';

        this.option.colorType = colorType;
        this.option.text = text;
        this.option.duration = duration;
        this.base();

        return this;
    }

    success(text = '', duration = 1500, colorType = 'bold') {

        this.option.background = 'rgba(111, 167, 77, 1)';
        this.option.border = '1px solid rgba(111, 167, 77, 1)';

        if (colorType == 'light') this.option.background = 'rgb(225, 255, 207)';

        this.option.colorType = colorType;
        this.option.text = text;
        this.option.duration = duration;
        this.base();

        return this;
    }

    info(text = '', duration = 1500, colorType = 'bold') {

        this.option.background = 'rgba(56, 167, 171, 1)';
        this.option.border = '1px solid rgba(56, 167, 171, 1)';

        if (colorType == 'light') this.option.background = 'rgb(184, 252, 255)';

        this.option.colorType = colorType;
        this.option.text = text;
        this.option.duration = duration;
        this.base();

        return this;
    }

    warning(text = '', duration = 1500, colorType = 'bold') {

        this.option.background = 'rgba(233, 132, 41, 1)';
        this.option.border = '1px solid rgba(233, 132, 41, 1)';

        if (colorType == 'light') this.option.background = 'rgb(255, 197, 145)';

        this.option.colorType = colorType;
        this.option.text = text;
        this.option.duration = duration;
        this.base();

        return this;
    }

    error(text = '', duration = 1500, colorType = 'bold') {

        this.option.background = 'rgba(233, 70, 89, 1)';
        this.option.border = '1px solid rgba(233, 70, 89, 1)';

        if (colorType == 'light') this.option.background = 'rgb(255, 140, 154)';

        this.option.colorType = colorType;
        this.option.text = text;
        this.option.duration = duration;
        this.base();

        return this;
    }
}

class _modalBox {

    title = null;
    description = null;
    options = null;

    constructor() {

    }

    init() {

        this.title = null;
        this.description = null;

        return this;
    }

    setup($options = {
        title: null,
        description: null,
        button: {
            confirm: {
                text: null,
                color: rgba(111, 167, 77, 1),
                cssClass: null
            },
            cancel: {
                text: null,
                color: rgba(233, 70, 89, 1),
                cssClass: null
            },
            alternative: {
                text: null,
                color: rgba(150, 150, 150, 1),
                cssClass: null
            }
        },
        animation: null
    }) {

        if ([undefined, null].indexOf($options['title']) >= 0) {

            console.error(
                `error from class %c${this.constructor.name}() %c\nMessage: Modal title must be filled`,
                'color: blue',
                'color: red',
            );
            return;
        }

        // Set default value
        if ([undefined, null].indexOf($options['description']) >= 0) $options['description'] = '';
        if ([undefined, null].indexOf($options['animation']) >= 0) $options['animation'] = '';
        if ([undefined, null].indexOf($options['button']) >= 0) {

            $options['button'] = null;
        } else {

            if ([undefined, null, ''].indexOf($options['button']['confirm']) >= 0
                || [undefined, null, ''].indexOf($options['button']['confirm']['text']) >= 0
            ) $options['button']['confirm'] = null;

            if ([undefined, null, ''].indexOf($options['button']['cancel']) >= 0
                || [undefined, null, ''].indexOf($options['button']['cancel']['text']) >= 0
            ) $options['button']['cancel'] = null;

            if ([undefined, null, ''].indexOf($options['button']['alternative']) >= 0
                || [undefined, null, ''].indexOf($options['button']['alternative']['text']) >= 0
            ) $options['button']['alternative'] = null;
        }

        // Update modal box options
        this.title = $options['title'];
        delete $options['title'];

        this.description = $options['description'];
        delete $options['description'];;

        this.options = $options;

        return this;
    }

    open($callback = {}) {

        const mdlContainer = document.createElement('div');
        mdlContainer.innerHTML = `
            <div class="mdl_box" style="display: flex; align-items: center; justify-content: center; box-sizing: border-box; padding: 15px; z-index: 10; position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(0, 0, 0, 0.4);">
                <div class="content_box ${this.options['animation']}" style="position: relative; padding: 15px; box-sizing: border-box; z-index: 1; background: #fff; border-radius: 5px; border: 1px solid rgb(230, 230, 230); box-shadow: 0 2px 3px rgba(0, 0, 0, 0.1);">
                    <div style="font-weight: bold; font-size: 1.7rem; text-align: center; line-height: 1.2;">
                        ${this.title}
                    </div>
                </div>
            </div>
        `;

        document.body.appendChild(mdlContainer);
    }
}

class _formCollect {

    // Class to collect value from form input, select, and textarea
    formTarget = null;
    requiredData = null;

    constructor() {

    }

    // Set value to default
    init() {

        this.formTarget = null;
        this.requiredData = null;
        return this;
    }

    target($target) {

        // Check $target is instance of HTMLElement
        this.formTarget = $target;
        return this;
    }

    required($required = []) {

        /*
            Array object
            {
                name: form name,
                type: form type,
                return: {
                    encode: encode return value (base64)
                }
            }
        */

        // A method for specifying which items in a form are required
        this.requiredData = $required;
        return this;
    }

    collect($cbSuccess, $cbError, option = {}) {

        if (this.formTarget == null) {

            console.error(
                `error from class %c${this.constructor.name}() %c\nMessage: Form target is empty\nDetail: %c.target() %cmethod must be filled`,
                'color: blue',
                'color: red',
                'color: blue',
                'color: red'
            );
        } else {

            let jsonData = {};

            // Collect all form data
            try {

                this.formTarget.querySelectorAll('input[type="text"], input[type="email"], input[type="password"], input[type="date"], input[type="file"], input[type="month"], input[type="range"], input[type="tel"], input[type="week"], input[type="text"], input[type="search"], input[type="number"], input[type="url"], input[type="hidden"], input[type="checkbox"]:checked, input[type="radio"]:checked, textarea, select').forEach((elem) => {

                    let formType = elem.getAttribute('type');

                    // Set form type by attribute format
                    switch (elem.getAttribute('format')) {

                        case 'currency':

                            formType = 'number';
                            break;
                    }

                    let value = {
                        value: '',
                        type: formType,
                        dom: elem
                    };

                    if (['file'].indexOf(formType) >= 0) {

                        value['value'] = elem.files.length >= 1 ? elem.files : null;
                    } else if (['number'].indexOf(formType) >= 0) {

                        value['value'] = elem.value.strReplace(['.', ','], '') || null;
                    } else {

                        value['value'] = elem.value || null;
                    }

                    jsonData[elem.getAttribute('name')] = value;
                });
            } finally {

                // Check required data
                if (this.requiredData != null) {

                    try {

                        this.requiredData.forEach((value) => {

                            if (typeof jsonData[value['name']] !== 'undefined') {

                                // Type
                                if (typeof value['type'] !== 'undefined') {

                                    if (jsonData[value['name']]['type'] != value['type']
                                        || [undefined, null, ''].indexOf(jsonData[value['name']]['value']) >= 0
                                    ) {

                                        throw {
                                            code: 'REQUIRED_FORM_IS_EMPTY',
                                            form: jsonData[value['name']]
                                        };
                                    }
                                } else {

                                    if (typeof jsonData[value['name']]['value'] === 'object'
                                        && (jsonData[value['name']]['value'] instanceof Object || jsonData[value['name']]['value'] instanceof FileList)) {

                                        if (jsonData[value['name']]['value'].length <= 0) {

                                            throw {
                                                code: 'REQUIRED_FORM_IS_EMPTY',
                                                form: jsonData[value['name']]
                                            };
                                        }
                                    } else {

                                        if ([undefined, null, ''].indexOf(jsonData[value['name']]['value']) >= 0) {

                                            throw {
                                                code: 'REQUIRED_FORM_IS_EMPTY',
                                                form: jsonData[value['name']]
                                            };
                                        }
                                    }
                                }

                                // Return value
                                if (typeof value['return'] !== 'undefined') {

                                    // Encode value
                                    if (typeof value['return']['encode'] !== 'undefined') {

                                        switch (value['return']['encode']) {

                                            case 'base64':

                                                jsonData[value['name']]['value'] = btoa(jsonData[value['name']]['value']);
                                                break;
                                        }
                                    }
                                }
                            } else {

                                throw {
                                    code: 'FORM_NOT_FOUND',
                                    form: value
                                };
                            }
                        });
                    } catch (exp) {

                        if (typeof exp['code'] !== 'undefined') {

                            switch (exp['code']) {

                                case 'FORM_NOT_FOUND':

                                    console.error(
                                        `error from class %c${this.constructor.name}() %c\nMessage: Required form not found\nDetail: \nform-type: %c${exp.form['type']} "%c\nform-name: %c${exp.form['name']}`,
                                        'color: blue',
                                        'color: red',
                                        'color: blue',
                                        'color: red',
                                        'color: blue',
                                    );
                                    break;

                                case 'REQUIRED_FORM_IS_EMPTY':

                                    if (typeof $cbError === 'function') {

                                        $cbError(exp);
                                    } else {

                                        console.error(
                                            `error from class %c${this.constructor.name}() %c\nMessage: Required form is empty\nDetail: \nform-type: %c${exp.form['type']} %c\nform-name: %c${exp.form['name']}`,
                                            'color: blue',
                                            'color: red',
                                            'color: blue',
                                            'color: red',
                                            'color: blue',
                                        );
                                    }
                                    break;
                            }
                        }

                        this.init();
                        return this;
                    }
                }

                // Convert to json
                let json = {};

                Object.keys(jsonData).forEach((key) => {

                    // Remove null value
                    if ([undefined, null, ''].indexOf(jsonData[key]['value']) < 0) {

                        json[key] = jsonData[key]['value'];
                    }
                });

                if (typeof $cbSuccess === 'function') $cbSuccess(json);
                this.init();
                return this;
            }
        }
    }
}

class _compileURL {

    // Class to compile url snippets
    href = null; // Full url
    path = null; // Url path
    hash = null; // Url hash
    query = null; // Url query string

    constructor($origin = null) {

        this.origin = $origin;
    }

    // Check and set origin
    setBaseUrl() {

        if (this.origin != null) {

            if (this.origin[this.origin.length - 1] != '/') this.origin += '/';
        } else {

            this.origin = null;
        }

        return this;
    }

    // Set value to default
    init() {

        this.href = this.origin;
        this.path = null;
        this.hash = null;
        this.query = null;
        return this;
    }

    addPath($path = '') {

        // Method to fill in truncated url path to whole url
        if ($path[0] == '/') $path = $path.substring(1);
        this.path = $path;

        // Set default full url value
        this.setBaseUrl();

        let spHash = this.href.includes('#') ? this.href.split('#') : null,
            spQuery = this.href.includes('?') ? this.href.split('?') : null;

        // Insert url path
        if (this.path != null) {

            this.href = this.origin + this.path;

            if (spQuery != null) {

                if (spQuery[1].includes('#')) {

                    spQuery = spQuery[1].split('#');
                    !this.href.includes('?')
                        ? this.href += '?' + spQuery[0]
                        : this.href += '&' + spQuery[0];
                } else {

                    !this.href.includes('?')
                        ? this.href += '?' + spQuery[1]
                        : this.href += '&' + spQuery[1];
                }
            }

            if (spHash != null) this.href += '#' + spHash[1];
        }

        return this;
    }

    addQuery($query = {}) {

        // Method to populate query string to full url
        this.query = '';
        Object.keys($query).forEach((jsonKey) => {

            this.query += this.query == ''
                ? `${jsonKey}=${$query[jsonKey]}`
                : `&${jsonKey}=${$query[jsonKey]}`;
        });


        // Set default full url value
        this.setBaseUrl();

        let spHash = this.href.includes('#') ? this.href.split('#') : null;

        // Insert query string
        if (this.query != null) {

            this.href = this.origin;

            if (this.path != null) this.href += this.path;

            !this.href.includes('?')
                ? this.href += '?' + this.query
                : this.href += '&' + this.query;

            if (spHash != null) this.href += '#' + spHash[1];
        }

        return this;
    }

    addHash($hash = '') {

        // Method to fill in truncated url hash to whole url
        if ($hash[0] == '#') $hash = $hash.substring(1);
        this.hash = '#' + $hash;

        // Place hash at the end of url
        this.href += this.hash;
        return this;
    }
}


/*
    **********
    # Dexa dot function
    **********
*/

// Last item of array
Array.prototype.lsItem = function () {

    return this[this.length - 1];
};

// Remove item from array
Array.prototype.removeItem = function (valueItem = [] | null) {

    if (['', null, []].indexOf(valueItem) < 0) {

        if (Array.isArray(valueItem)) {

            valueItem.forEach((value) => {

                let index = this.indexOf(value);
                if (index >= 0) this.splice(index, 1);
            });
        } else {

            let index = this.indexOf(valueItem);
            if (index >= 0) this.splice(index, 1);
        }
    }

    return this;
};

// Replace character with another charater
String.prototype.strReplace = function (target = [] | null, to = null) {

    let str;

    if (!this instanceof String && !typeof this === 'string') str = this.toString();
    else str = this;

    try {

        if ((['', null].indexOf(target) >= 0 && to == null)
            || ([[]].indexOf(target) >= 0)
        ) throw {};

        if (Array.isArray(target)) {

            target.forEach((value) => {

                str = str.replaceAll(value, to);
            });
        } else {

            str = str.replaceAll(target, to);
        }

        return str;
    } catch (exp) {

        return console.error('Expected 2 Arguments');
    }
};

// Value of some array item
String.prototype.ucFirst = function () {

    return this[0].toUpperCase() + this.slice(1);
};

// Modify value of query parameter from URL
String.prototype.query = function (keys = {} | null, value = null) {

    try {

        if ((['', null].indexOf(keys) >= 0 && value == null)
            || ([{}].indexOf(keys) >= 0)
        ) throw {};

        const url = new URL(this);
        let href = url.href;

        if (!href.includes('?')) throw {};

        let query = url.href.split('?')[1].split('&'),
            queryString = null;

        query.forEach((lpVal) => {

            let split = lpVal.split('='),
                spVal = null;

            for (let i = 1; i < split.length; i++) {

                spVal == null
                    ? spVal = split[i]
                    : spVal += '=' + split[i];
            }

            if (typeof keys === 'object' && !Array.isArray(keys)) {

                if (isset(keys[split[0]])) {

                    queryString == null
                        ? queryString = split[0] + '=' + keys[split[0]]
                        : queryString += '&' + split[0] + '=' + keys[split[0]];
                } else {

                    queryString == null
                        ? queryString = split[0] + '=' + spVal
                        : queryString += '&' + split[0] + '=' + spVal;
                }
            } else {

                if (split[0] == keys) {

                    queryString == null
                        ? queryString = split[0] + '=' + value
                        : queryString += '&' + split[0] + '=' + value;
                } else {

                    queryString == null
                        ? queryString = split[0] + '=' + spVal
                        : queryString += '&' + split[0] + '=' + spVal;
                }
            }
        });

        return url.origin + url.pathname + '?' + queryString;
    } catch (exp) {

        return console.error('Invalid URL');
    }

};

// Get value of query parameter from URL
String.prototype.getQuery = function (key) {

    try {

        const url = new URL(this);
        return url.searchParams.get(key);
    } catch (exp) {

        return console.error('Invalid URL');
    }
};

// Get value of query parameter from URL
String.prototype.removeQuery = function (key) {

    let url = this.trim();
    urlParts = url.split('?');

    if (urlParts.length < 1) return null;

    let queryParts = urlParts[1].split('&'),
        finalQuery = null;

    queryParts.forEach((value) => {

        let split = value.split('='),
            spVal = null;

        if (split[0] != key) {

            for (let i = 1; i < split.length; i++) {

                spVal == null
                    ? spVal = split[i]
                    : spVal += '=' + split[i];
            }

            finalQuery == null
                ? finalQuery = split[0] + '=' + spVal
                : finalQuery += '&' + split[0] + '=' + spVal;
        }
    });

    return urlParts[0] + '?' + finalQuery;
};

// Client bounding status
HTMLElement.prototype.boundingStatus = function (
    offsetX = 20,
    offsetY = 40
) {

    let elemObj = {};

    elemObj['height'] = this.offsetHeight;
    elemObj['width'] = this.offsetWidth;

    let boundingObj = {};
    boundingObj['y'] = this.getBoundingClientRect().y;
    boundingObj['x'] = this.getBoundingClientRect().x;

    let status = {
        vertical: false,
        horizontal: false
    };

    // Vertical
    if (boundingObj['y'] - window.innerHeight + offsetY <= 0) {

        status['vertical'] = true;
    }

    // Vertical
    if (boundingObj['x'] - window.innerWidth + offsetX <= 0) {

        status['horizontal'] = true;
    }

    return status;
};

// Animate value
HTMLElement.prototype.animateValue = function (start, end, duration) {

    if (start === end) return;

    let range = end - start;
    let current = start;
    let increment = end > start ? 1 : -1;
    let stepTime = Math.abs(Math.floor(duration / range));

    let timer = setInterval((elem) => {

        current += increment;

        elem.innerHTML = current;

        if (current == end) {

            clearInterval(timer);
        }
    }, stepTime, this);
};



/*
    **********
    # Dexa function
    **********
*/

// Check is variable or object avaialable or not
const isset = function (variable = null) {

    if (variable === undefined
        || variable === null)
        return false;

    return typeof variable !== 'undefined';
};

// Rupiah
const rupiah = function (value = 0) {

    return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
};

// Convert json key - value to form data
const jsonToFormData = function (json = null, option = {}) {

    if (json == null) return null;

    let formData = new FormData();

    Object.keys(json).forEach((key) => {

        let value = json[key];

        if (!(value instanceof FileList)) {

            // Options
            if ([undefined, null, '', {}, []].indexOf(option) < 0) {

                // Encode value
                if (isset(option['encode'])) {

                    if (isset(option['keyToEncode'])) {

                        if (option['keyToEncode'].indexOf(key) >= 0
                            && typeof option['encode'] !== 'undefined'
                        ) {

                            switch (option['encode']) {

                                case 'base64':
                                    value = btoa(value);
                                    break;
                            }
                        }
                    } else {

                        if (typeof option['encode'] !== 'undefined') {

                            switch (option['encode']) {

                                case 'base64':
                                    value = btoa(value);
                                    break;
                            }
                        }
                    }
                }
            }

            formData.append(key, value);
        } else {

            if (value.length > 1) {

                Array.from(value).forEach((flVal, flKey) => {

                    formData.append(`${key}[]`, flVal);
                });
            } else {

                formData.append(key, value[0]);
            }
        }
    });

    return formData;
};

// Read or save cookies
const jsCookie = {
    save: function (
        args = {
            name: null,
            value: null,
            exp_time: null,
            path: null,
            domain: null,
        }
    ) {

        // Fill empty arguments
        if (!isset(args['name'])) args['name'] = null;
        if (!isset(args['value'])) args['value'] = null;
        if (!isset(args['exp_time'])) args['exp_time'] = null;
        if (!isset(args['path'])) args['path'] = null;
        if (!isset(args['domain'])) args['domain'] = null;

        let strCookie = '';

        if (
            args['name'] != null
            && args['value'] != null
        ) {

            strCookie = `${args['name']}=${args['value']};`;

            if (args['path'] != null) strCookie += `path=${args['path']};`;
            if (args['domain'] != null) strCookie += `domain=${args['domain']};`;
            if (args['exp_time'] != null) strCookie += `expires=${args['exp_time']};`;

            document.cookie = strCookie;
        }
    },
    get: function (name = null) {

        if (name == null) return null;

        let exp = document.cookie.strReplace('; ', ';').split(';'),
            resValue = null;

        exp.forEach((lpVal) => {

            let split = lpVal.split('=');

            if (split.length >= 1) {

                if (split[0] == name) {

                    for (let i = 1; i < split.length; i++) {

                        resValue == null
                            ? resValue = split[i]
                            : resValue += '=' + split[i];
                    }
                }
            }
        });

        return resValue;
    }
};


// Copy to clipboard
const copyToClipboard = (value) => {

    let tempInput = document.createElement("input");

    tempInput.value = value;
    document.body.appendChild(tempInput);

    tempInput.select();
    document.execCommand("copy");
    document.body.removeChild(tempInput);

    if (document.execCommand("copy")) {

        (new _notifConsole).success('Disalin ke clipboard', 1500);
    }
};