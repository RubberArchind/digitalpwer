var logoutModal = new bootstrap.Modal(document.getElementById("logoutModal"), {
  keyboard: false,
});
if (document.getElementById("topupModal")) {
  var topupModal = new bootstrap.Modal(document.getElementById("topupModal"), {
    keyboard: false,
  });
}
if (document.getElementById("depositModal")) {
  var depositModal = new bootstrap.Modal(document.getElementById("depositModal"), {
    keyboard: false,
  });
  $("#btnDeposit").on("click", function () {
    depositModal.toggle();
  });
}
var copyToast = new bootstrap.Toast(document.getElementById("copyToast"));

$("#btnLogout").on("click", function () {
  console.log("LOGGING OUT");
  logoutModal.toggle();
});
$("#btnLogoutNo").on("click", function () {
  logoutModal.hide();
});
$("#btnLogoutYes").on("click", function () {
  $.post("/auth/logout", function (data) {
    console.log("LOGGED OUT...");
  });
});
$("#copyToastBtn").on("click", function () {
  copyToast.show();
});

function toggleTopup(pcode, amount) {
  topupModal.toggle();
  $("#pcode").val(pcode);
  $("#amount").val(amount);
}

$("#btnTopupNo").on("click", function () {
  topupModal.toggle();
});

function copyRef() {
  navigator.clipboard.writeText(refCode.value);
}

const configsTwo = { messageLoading: "Loading", resetForm: false };
if (document.getElementById("topupModal")) {
  yii2AjaxRequest(
    "#topup-form",
    configsTwo,
    (data) => {
      console.log("dt", data);
      topupModal.toggle();
      if (data.data.error) {
        if (data.data.error["pnumber"]) {
          alert("Topup Gagal, Nomor tidak boleh kosong");
        } else {
          alert("Topup Gagal, " + data.data.error);
        }
      } else if (data.data.data.status != 2) {
        alert("Topup berhasil diproses...");
      } else {
        if ((data.data.data.rc = 16)) {
          alert("Nomor yang anda masukkan tidak cocok");
        } else {
          alert("Topup Gagal, Coba beberapa saat lagi");
        }
      }
      location.reload();
      // }
    },
    (error) => {
      console.error("ERROR");
    }
  );
}
yii2AjaxRequest(
  "#deposit-form",
  configsTwo,
  (data) => {
    console.log("success", data);
    if (data.data.token) {
      snap.pay(data.data.token, {
        onSuccess: function (result) {
          console.log("success", result);
          if (
            result.fraud_status == "accept" &&
            (result.transaction_status == "success" ||
              result.transaction_status == "capture" ||
              result.transaction_status == "settlement")
          ) {
            depositModal.toggle();
            alert(
              "Status: " +
              ("Success")
            );
          }
        },
        // Optional
        onPending: function (result) {
          console.log("pending", result);
        },
        // Optional
        onError: function (result) {
          console.log("error", result);
        },
      });
    } else if (
      data.data.login == false ||
      data.data.errors.password[0] == "Incorrect username or password."
    ) {
      alert("Deposit gagal");
    } else {
      alert("Deposit gagal, ERR");
    }
  },
  (error) => {
    // The return of a block try / catch
    console.error("ERROR");
  }
);

yii2AjaxRequest(
  "#withdraw-form",
  configsTwo,
  (data) => {
    // console.log("dt", data.data);
    if (data.data.error) {
      alert("Withdraw gagal, " + data.data.error.errorCode);
    } else {
      alert("Permintaan withdraw anda berhasil diproses, dana akan masuk kurang lebih 1x24 Jam, jika melebihi 1x24 jam silahkan hubungi CS");
      location.reload();
    }
  },
  (error) => {
    console.error("ERROR");
  }
);

function trxPdf() {
  $("#trx-table").tableHTMLExport({
    type: "pdf",
    orientation: "p",
    filename: "Digitalpwer Transaction.pdf",
  });
}

function trxExcel() {
  $("#trx-table").tableHTMLExport({
    type: "csv",
    filename: "Digitalpwer Transaction.csv",
  });
}

function claimReward() {
  alert(
    "Claim Reward Berhasil, Silahkan Hubungi CS kami untuk info lebih lanjut"
  );
}

function onWorking() {
  alert("Fitur sedang dalam perbaikan");
}
