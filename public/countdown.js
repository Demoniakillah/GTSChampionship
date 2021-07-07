function countdown() {
    $.each($('.countdown'), function (i, element) {
        if (
            element.hasAttribute('data-year') && $(element).data('year') !== '' &&
            element.hasAttribute('data-month') && $(element).data('month') !== '' &&
            element.hasAttribute('data-day') && $(element).data('day') !== '' &&
            element.hasAttribute('data-hour') && $(element).data('hour') !== '' &&
            element.hasAttribute('data-minute') && $(element).data('minute') !== ''
        ) {
            const MINUTE = 60;
            const HOUR = MINUTE * 60;
            const DAY = HOUR * 24;
            let year = $(element).data('year')
            let month = $(element).data('month')
            let day = $(element).data('day')
            let hour = $(element).data('hour')
            let minute = $(element).data('minute')

            function refreshCountDown() {
                const EVENT_DATE = Date.parse(year + '-' + month + '-' + day + 'T' + hour + ':' + minute) / 1000
                const DIFFERENCE = EVENT_DATE - Date.now() / 1000
                if (DIFFERENCE < 0) {
                    $(element).empty().text('Race in progress or finished')
                    if (!$(element).hasClass('text-secondary')) {
                        $(element).addClass('text-secondary')
                        $(element).removeClass('text-danger')
                    }
                } else {
                    const DIFF = {
                        days: Math.floor(DIFFERENCE / DAY),
                        hours: Math.floor(DIFFERENCE % DAY / HOUR),
                        minutes: Math.floor(DIFFERENCE % HOUR / MINUTE),
                        seconds: Math.floor(DIFFERENCE % MINUTE)
                    }
                    if (
                        DIFF.days === 0 &&
                        DIFF.hours === 0 &&
                        DIFF.minutes < 15
                    ) {
                        if (!$(element).hasClass('text-danger')) {
                            $(element).addClass('text-danger')
                        }
                    }
                    let finalText = ''
                    let dayText = DIFF.days + 'd '
                    finalText += dayText
                    let hourText = DIFF.hours + 'h '
                    finalText += hourText
                    let minuteText = DIFF.minutes + 'm '
                    finalText += minuteText + ' and '
                    let secondText = DIFF.seconds + 's'
                    finalText += secondText
                    $(element).text(finalText)
                    setTimeout(() => {
                        requestAnimationFrame(() => {
                            refreshCountDown()
                        })
                    }, 1000)
                }
            }

            refreshCountDown()
        }
    })

}