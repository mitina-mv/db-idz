'use strict'

window.addEventListener('DOMContentLoaded', function() {
    let table = document.querySelector('.main-table'),
        sqlTable = table.getAttribute('data-table'),
        identityField = table.getAttribute('data-identity');

    let btnAdd = document.querySelector('#btn-addElement'),
        btnDel = document.querySelector('#btn-delElements');

    let arrIdentity = new Array();

    table.addEventListener('change', (event) => {
        let id = event.target.getAttribute('data-id'),
            pos = arrIdentity.indexOf(id);
        if(pos == -1) {
            arrIdentity.push(id);
        } else {
            arrIdentity.splice(pos, 1);
        }

        console.log(arrIdentity);
    })

    btnDel.addEventListener('click', () => {
        if(arrIdentity.length === 0) return;

        let fData = new FormData();

        fData.append('ids', arrIdentity);
        fData.append('table', sqlTable);
        fData.append('identityField', identityField);
        fData.append('method', 'delete');

        postData('/admin/api/table', fData, {})
            .then(data => {
                showUserMessage('Успех', 'Удалено успешно', 'success');
                // modalStart.remove();
            })
            .catch((error) => {
                let cookie = decodeURIComponent(getCookie('query_error'));
                let errorMessage = cookie ? JSON.parse(cookie).message : 'Ошибка при попытке удалить пост.';

                showUserMessage('Ошибка', errorMessage, 'error');
            })
    })
})