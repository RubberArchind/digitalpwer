var logoutModal = new bootstrap.Modal(document.getElementById('logoutModal'), {
    keyboard: false
});
var copyToast = new bootstrap.Toast(document.getElementById('copyToast'));

$('#btnLogout').on('click', function () {
    console.log("LOGGING OUT");
    logoutModal.toggle();
});
$('#btnLogoutNo').on('click', function () {
    logoutModal.hide();
});
$('#btnLogoutYes').on('click', function () {
    $.post("/auth/logout", function (data) {
        console.log("LOGGED OUT...")
    });
});
$('#copyToastBtn').on('click', function () {
    copyToast.show();
})

function copyRef() {
    navigator.clipboard.writeText(refCode.value);
}
const configsTwo = { messageLoading: 'Loading', resetForm: false };
yii2AjaxRequest('#deposit-form', configsTwo,
    (data) => {
        console.log('success', data);
        if (data.data.url) {
            window.location.replace(data.data.url);
        } else if (data.data.login == false || data.data.errors.password[0] == 'Incorrect username or password.') {
            alert("Deposit gagal")
        }
    },
    (error) => { // The return of a block try / catch
        console.error('ERROR')
    });

yii2AjaxRequest('#withdraw-form', configsTwo,
    (data) => {
        console.log('dt', data.data);
        if (data.data.error) {
            alert("Withdraw gagal, " + data.data.error.errorCode);
        } else {
            alert("Withdraw Berhasil");
            // window.location.replace('/dashboard');
        }
    },
    (error) => { // The return of a block try / catch
        console.error('ERROR')
    });

function trxPdf() {
    $("#trx-table").tableHTMLExport({
        type: 'pdf',
        orientation: 'p',
        filename: 'Digitalpwer Transaction.pdf'
    });
}

function trxExcel(){
    $("#trx-table").tableHTMLExport({
        type: 'csv',
        filename: 'Digitalpwer Transaction.csv'
    });
}
