$(() => {
    $(document).on("ajaxStart", () => {
        $('#loader').show()
    })

    $(document).on("ajaxComplete", () => {
        $('#loader').hide()
    })
    
    $('#submitForm').submit(e => {
        e.preventDefault()
        let $this = $(e.currentTarget)
        let url = $this.attr('action')

        $.ajax({
            url,
            type: 'post',
            data: new FormData($this[0]),
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: () => {
                $('#form-errors-alert, #form-success-alert').empty().hide()
            },
            complete: () => { },
            success: data => {
                if (data.status) {
                    $('#form-success-alert').html(`<b>${data.message}</b>`).show()
                    $this[0].reset()
                } else {
                    $('#form-errors-alert').html(`<b>${data.message}</b>`).show()
                }
            },
            error: error => {
                if (error.responseJSON) {
                    let html = `<b>${error.responseJSON.message}</b>`
                    if (error.responseJSON.errors) {
                        html += '<ul>'
                        for (let key in error.responseJSON.errors) {
                            error.responseJSON.errors[key].forEach(err => {
                                html += `<li>${err}</li>`
                            })
                        }
                        html += '</ul>'
                    }
                    $('#form-errors-alert').html(html).show()
                } else {
                    alert(error.statusText)
                }
            }
        })
    })
})