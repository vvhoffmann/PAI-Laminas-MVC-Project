document.querySelectorAll('.aDodajDoKoszyka').forEach((elem) => {
    elem.addEventListener('click', async (e) => {
        e.preventDefault()
        const a = e.currentTarget
        const href = a.getAttribute('href')
        const resp = await fetch(href, {method: 'post'})
        const text = await resp.text()

        if (text === 'ok') {
            const ok = document.createElement('i')
            ok.classList.add('fas', 'fa-check-circle', 'text-success')
            a.parentNode.replaceChild(ok, a)

            var str1 = document.getElementById('liczba_ofert').innerText.replace ( /[^\d.]/g, '' );
            value = parseInt(str1, 10);
            value++;
            document.getElementById('liczba_ofert').innerHTML = "Liczba ofert w koszyku: <strong>" + value.toString() + "</strong>";

        } else {
            alert('Wystąpił błąd')
        }
    })
})