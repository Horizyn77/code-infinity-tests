const button = document.querySelector("button");
const spinner = document.querySelector(".spinner");
const p = document.querySelector("p");
const generateButton = document.querySelector(".generate-page button");

const spinnerTimeout = setTimeout(() => {
    spinner.classList.remove("display")
}, 30000)

function startSpinner() {
    spinnerTimeout;
    spinner.classList.add("display");
    button.innerText = "Importing..."
    button.disabled = true;
}

function stopSpinner() {

}

async function importIntoDB() {

    try {
        startSpinner()
        const res = await fetch("/test_2/import_data.php");

        if (res.ok) {
            stopSpinner();
            p.innerText = "The data has been imported successfully";
            clearTimeout(spinnerTimeout)
            spinner.classList.remove("display")
            button.innerText = "Import into db";
            button.disabled = false;
        } else {
            throw new Error("Failed to import data")
        }
    } catch (error) {
        console.log("Error:", error.message)
    }

}

function generateRecordsSpinner() {
    spinnerTimeout;
    spinner.classList.add("display");
    generateButton.innerText = "Generating..."
    generateButton.disabled = true;

    setTimeout(() => {
        clearTimeout(spinnerTimeout)
        spinner.classList.remove("display")
        button.innerText = "GENERATE";
        button.disabled = false;
    }, 9000)
}