document.querySelector('#btnWyslij').addEventListener('click', async (e) => {
    e.preventDefault()

    const form = document.querySelector('#formZapytanie')
    const action = form.getAttribute('action')

    const resp = await fetch(action, {
        method:'post',
        body: new FormData(form)
    })
    const text = await resp.text()

    if (text === 'ok') {
        alert("Dziękujemy za wysłanie zapytania.")
        form.tresc.value = ''
        $('#modalZapytanie').modal('hide')
    } else {
        alert('Wystąpił błąd')
    }
})