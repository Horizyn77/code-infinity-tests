const submitResultMsg = document.querySelector("#submit-result-msg");

if (submitResultMsg.innerText) {
    setTimeout(() => {
        submitResultMsg.innerText = "";
    }, 4000)
}

$('.datepicker').datepicker({
    format: 'dd/mm/yyyy',
    autoclose: true
});

function clearInputs() {
    const inputs = document.querySelectorAll("input");

    inputs.forEach(input => {
        input.value = "";
    });
}