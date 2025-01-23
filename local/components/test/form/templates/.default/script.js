window.TestForm = function (params) {
    this.componentName = params.componentName;
    this.formId = params.formId;
    this.blockId = params.blockId
}

window.TestForm.prototype = {

    init: function () {
        this.bxPhone = BX(this.formId).querySelector('#phone') ?? {};
        this.bxEmail = BX(this.formId).querySelector('#email') ?? {};
        this.bxName = BX(BX(this.formId).querySelector('#name')) ?? {};
        this.bxError = BX(BX(this.formId).querySelector('.error')) ?? {};
        this.bxNotice = BX(BX(this.formId).querySelector('.notice')) ?? {};

        this.MaskedPhone = new BX.MaskedInput({
            mask: '+7 (999)-999-99-99',
            input: this.bxPhone,
            placeholder: '_'
        })

        BX.bind(
            BX(this.formId),
            'submit',
            BX.proxy(this.sendForm,
                this)
        );
    },
    sendForm: function ($event) {
        $event.preventDefault();

        if (this.validate() === true) {
            const _this = this;


            let formData = {
                name: this.bxName.value,
                phone: this.bxPhone.value,
                email: this.bxEmail.value,
                blockId: this.blockId,
            };

            this.bxError.innerHTML = '';
            this.bxNotice.innerHTML = '';

            this.send(formData, 'sendForm', {}).then(function (response) {
                let errorItem = BX.create('div', {
                    attrs: {
                        className: 'notice__item',
                    },
                    html: response.data.status,
                });

                _this.bxNotice.append(errorItem);
            });
        }
    },
    send: function (formData, method) {
        const _this = this;

        this.bxError.innerHTML = '';
        this.bxNotice.innerHTML = '';

        let params = {
            mode: 'class',
        }

        params.data = formData

        return new Promise(function (resolve, reject) {
            BX.ajax.runComponentAction(
                _this.componentName,
                method,
                params
            )
                .then(function (response) {
                    resolve(response)
                }, function (response) {

                    response.errors.forEach((error) => {
                        let errorItem = BX.create('div', {
                            attrs: {
                                className: 'error__item',
                            },
                            html: error.message,
                        });

                        _this.bxError.append(errorItem);
                    })
                    reject(response.errors)
                })
                .catch(err => {
                    if (response.errors) {
                        response.errors.forEach((error) => {
                            let errorItem = BX.create('div', {
                                attrs: {
                                    className: 'error__item',
                                },
                                html: error.message,
                            });

                            _this.bxError.append(errorItem);
                        })
                    }
                })
        })
    },
    validate: function () {

        let isValid = true;
        const emailReg = /^(([^<>()[\].,;:\s@"]+(\.[^<>()[\].,;:\s@"]+)*)|(".+"))@(([^<>()[\].,;:\s@"]+\.)+[^<>()[\].,;:\s@"]{2,})$/iu;

        if (this.MaskedPhone.checkValue() === false || this.MaskedPhone.getValue().length == 0) {
            isValid = false;
            BX.addClass(this.bxPhone, 'error');
        }


        if (this.bxName.value.length < 3) {
            isValid = false;
            BX.addClass(this.bxName, 'error');
        }

        if (this.bxEmail.value.length < 3 || emailReg.test(this.bxEmail.value) === false) {
            isValid = false;
            BX.addClass(this.bxEmail, 'error');
        }

        return isValid;
    }
}