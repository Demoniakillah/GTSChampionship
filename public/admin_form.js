function updateTableWithEmptyRow(tbody) {
    tbody.children('.empty_list').remove()
    $.each(tbody, function (i, element) {
        if ($(element).children().length < 1) {
            $(element).append('<tr class="empty_list"><td colspan="4">empty</td></tr>')
            $(element).parent('table').parent('div').find('span.nb').text(0)
        } else {
            $(element).parent('table').parent('div').find('span.nb').text($(element).children('tr').length)
        }
    })
}

$(document).ready(() => {
    $('.remove_element').on('click', function () {
        let $multiAction = $('.multi_action:checked');
        if ($multiAction.length > 0) {
            let url = $(this).data('remove_multi_url')
            let list = []
            $.each($multiAction, function (i, inscription) {
                list.push({id: $(inscription).data('item_id'), token: $(inscription).data('token')})
            })
            $.confirm({
                title: '',
                content: 'Are you sure ?',
                buttons: {
                    yes: {
                        btnClass: 'btn-red',
                        action: function () {
                            $.post({
                                url: url,
                                data: {remove_list: list},
                                dataType: 'json',
                                success: function () {
                                    window.location.reload()
                                },
                                error: function (errorMsg) {
                                    $.alert({
                                        title: 'Error!',
                                        content: errorMsg.responseText
                                    })
                                }
                            })
                        }
                    },
                    cancel: {
                        btnClass: 'btn-grey'
                    }
                }
            })
        } else {
            let url = $(this).data('remove_url')
            let token = $(this).data('token')
            $.confirm({
                title: '',
                content: 'Are you sure ?',
                buttons: {
                    yes: {
                        btnClass: 'btn-red',
                        action: function () {
                            $.post({
                                url: url,
                                data: {_token: token},
                                redirect: true,
                                success: function () {
                                    window.location.reload()
                                },
                                error: function (errorMsg) {
                                    $.alert({
                                        title: 'Error!',
                                        content: errorMsg.responseText
                                    })
                                }
                            })

                        }
                    },
                    cancel: {
                        btnClass: 'btn-grey'
                    }
                }
            })
        }
    })
    $('.get_form_button').on('click', function () {
        let formUrl = $(this).data('form_url')
        let reload = !$(this).hasClass('no_reload')
        $.get({
            url: formUrl,
            success: function (html) {
                let confirm = $.confirm({
                    title: '',
                    content: (reload ? '' : '<span class="text-danger">Mods will take effect after reload</span>') + html,
                    useBootstrap: false,
                    boxWidth: '100%',
                    width: '100%',
                    onContentReady: function () {
                        formatInput()
                    },
                    buttons: {
                        cancel: {
                            btnClass: 'btn-orange'
                        },
                        save: {
                            btnClass: 'btn-blue',
                            action: function () {
                                let error = false
                                let validateForm = true
                                let $required = $('[required="required"]');
                                if ($required.length > 0) {
                                    error = false
                                    $.each($required, function (i, required) {
                                        if (required.value === '') {
                                            $(required).css('border-color', 'red')
                                            validateForm = false
                                        }
                                    })
                                }
                                if (validateForm === true) {
                                    let data = {}
                                    $.each($('input,select,textarea'), function (i, input) {
                                        if ($(input).attr('type') === 'checkbox' || $(input).attr('type') === 'radio') {
                                            if ($(input).is(':checked')) {
                                                data[$(input).attr('name')] = 1
                                            }
                                        } else {
                                            data[$(input).attr('name')] = $(input).val()
                                        }
                                    })
                                    $.post({
                                        url: $('#form_action_url').val(),
                                        data: data,
                                        success: function () {
                                            if (reload) {
                                               // window.location.reload()
                                            }
                                            confirm.close()
                                        },
                                        error: function (errorMsg) {
                                            $.alert({
                                                title: 'Error!',
                                                content: errorMsg.responseText
                                            })
                                            error = true
                                        }
                                    })
                                }
                                return error
                            }
                        }
                    }
                })
            }
        })
    })
})