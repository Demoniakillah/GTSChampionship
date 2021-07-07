$(document).ready(function () {
    $('.driver_stats').on('click', function () {
        let url = $(this).data('url')
        $.get({
            url: url,
            success: function (html) {
                $.dialog({
                    title: '',
                    content: html,
                    width: "100%",
                    boxWidth: "80%",
                    useBootstrap: false,
                })
            },
            error: function (errorMsg) {
                $.alert({
                    title: 'Error!',
                    content: errorMsg.responseText
                })
            }
        })
    })
})