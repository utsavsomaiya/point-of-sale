function discountStatusChanged(id, status) {
    if (status == 1) {
        status = status + 1;
    } else {
        status = status - 1;
    }
    $.ajax({
        url: "edit_discount.php",
        data: {
            id: id,
            status: status
        },
        type: "POST"
    });
}

var flag;
function insertTemplate() {
    var discountCategory = document.querySelector('.category');
    discountCategory.onchange = function () {
        categoryTemplate(null)
    };
}

function categoryTemplate(checkError) {
    if (discountCategory.value == "") {
        document.querySelector('.category1').classList.add('d-none');
        document.querySelector('.category2').classList.add('d-none');
    }
    if (discountCategory.value == "1")
    {
        document.querySelector('.category1').classList.remove('d-none');
        document.querySelector('.category2').classList.add('d-none');
        flag = 1;
    }
    if (discountCategory.value == "2")
    {
        document.querySelector('.category2').classList.remove('d-none');
        document.querySelector('.category1').classList.add('d-none');
        flag = 2;
    }
    if (checkError == null)
        renderMinimumSpendTemplate();
    else
        sessionRenderMinimumSpendTemplate();
};

const minimumSpendContainer = [0];

var discountDigit = document.getElementsByClassName('digit');
var minimumSpendAmount = document.getElementsByClassName('minimum-spend-amount');
var discountProduct = document.getElementsByClassName('product');

function renderMinimumSpendTemplate() {
    if(flag == 1)
        document.getElementsByClassName('minimum-spend-row-container1')[0].innerHTML = '';
    if (flag == 2)
        document.getElementsByClassName('minimum-spend-row-container2')[0].innerHTML = '';
    for (const key in minimumSpendContainer) {
        if (flag == 1) {
            minimumSpendRowContainer = document.getElementsByClassName('minimum-spend-row-container1')[0];
            minimumSpendRowTemplate = document.getElementById('minimum-spend-template2').innerHTML;
            minimumSpendRowContainer.innerHTML += minimumSpendRowTemplate;
            if (key === "0") {
                document.querySelector('.remove-minimum-spend1').setAttribute('class', 'd-none');
            }
        }
        if (flag == 2) {
            minimumSpendRowContainer = document.getElementsByClassName('minimum-spend-row-container2')[0];
            minimumSpendRowTemplate = document.getElementById('minimum-spend-template1').innerHTML;
            minimumSpendRowContainer.innerHTML += minimumSpendRowTemplate;
            if (key === "0") {
                document.querySelector('.remove-minimum-spend2').setAttribute('class', 'd-none');
            }
        }
    }
    if (flag == 1) {
        removeObject = document.getElementsByClassName('remove-minimum-spend1');
        removeTemplate = document.getElementsByClassName('remove-minimum-spend-row-1')[0];
        [...removeObject].forEach((remove, i) => {
            remove.innerHTML += removeTemplate.innerHTML;
            document.querySelectorAll('.fa')[i].onclick = function () {
                removeMinimumSpendRow(i, null);
            }
        });
    }
    if (flag == 2) {
        removeObject = document.getElementsByClassName('remove-minimum-spend2');
        removeTemplate = document.getElementsByClassName('remove-minimum-spend-row-2')[0];
        [...removeObject].forEach((remove, i) => {
            remove.innerHTML += removeTemplate.innerHTML;
            document.querySelectorAll('.fa')[i].onclick = function () {
                removeMinimumSpendRow(i, null);
            }
        });
    }
}
var selectedOptions = [];
function addMinimumSpendRow() {
    if (flag == 1) {
        minimumSpendContainer.push(minimumSpendContainer[minimumSpendContainer.length - 1] + 1);
        renderMinimumSpendTemplate();
    }
    if (flag == 2) {
        for (i = 0; i < discountProduct.length; i++) {
            if (discountProduct.length <= ((discountProduct[i].options.length)-2)) {
                minimumSpendContainer.push(minimumSpendContainer[minimumSpendContainer.length - 1] + 1);
                renderMinimumSpendTemplate();
                break;
            }
        }
    }
}

function removeMinimumSpendRow(index,flag) {
    if ((index+1) > -1) {
        minimumSpendContainer.splice((index+1), 1);
        if (flag == "error")
            sessionRenderMinimumSpendTemplate();
        else
            renderMinimumSpendTemplate();
    }
}


function sessionRenderMinimumSpendTemplate() {
    if (flag == 1)
        document.getElementsByClassName('minimum-spend-row-container1')[0].innerHTML = '';
    if (flag == 2)
        document.getElementsByClassName('minimum-spend-row-container2')[0].innerHTML = "";
    for (const key in minimumSpendContainer) {
        if (flag == 1) {
            minimumSpendRowContainer = document.getElementsByClassName('minimum-spend-row-container1')[0];
            minimumSpendRowTemplate = document.getElementById('minimum-spend-template2').innerHTML;
            minimumSpendRowContainer.innerHTML += minimumSpendRowTemplate;
            if (key === "0") {
                document.querySelector('.remove-minimum-spend1').setAttribute('class', 'd-none');
            }
        }
        if (flag == 2){
            minimumSpendRowContainer = document.getElementsByClassName('minimum-spend-row-container2')[0];
            minimumSpendRowTemplate = document.getElementById('minimum-spend-template1').innerHTML;
            minimumSpendRowContainer.innerHTML += minimumSpendRowTemplate;
            if (key === "0") {
                document.querySelector('.remove-minimum-spend2').setAttribute('class', 'd-none');
            }
        }
    }
    if (flag == 1) {
        removeObject = document.getElementsByClassName('remove-minimum-spend1');
        removeTemplate = document.getElementsByClassName('remove-minimum-spend-row-1')[0];
        [...removeObject].forEach((remove, i) => {
            remove.innerHTML += removeTemplate.innerHTML;
            document.querySelectorAll('.fa')[i].onclick = function () {
                removeMinimumSpendRow(i, null);
            }
        });
        for (i = 0; i < discountDigit.length; i++) {
            if (errorDiscountDigit.length > 0) {
                discountDigit[i].value = errorDiscountDigit[i];
            }
            if (digitAlert[i] != undefined && digitAlert[i] != '[object Object]') {
                document.getElementsByClassName('digit-error')[i].innerHTML = digitAlert[i];
            }
            if (errorMinimumSpendAmount.length > 0) {
                minimumSpendAmount[i].value = errorMinimumSpendAmount[i];
            }
            if (minimumSpendAlert[i] != undefined) {
                document.getElementsByClassName('minimum-spend-amount-error')[i].innerHTML = minimumSpendAlert[i];
            }
        }
    }
    if (flag == 2)
    {
        removeObject = document.getElementsByClassName('remove-minimum-spend2');
        removeTemplate = document.getElementsByClassName('remove-minimum-spend-row-2')[0];
        [...removeObject].forEach((remove, i) => {
            remove.innerHTML += removeTemplate.innerHTML;
            document.querySelectorAll('.fa')[i].onclick = function () {
                removeMinimumSpendRow(i, null);
            }
        });
        for (i = 0; i < discountProduct.length; i++) {
            if (errorDiscountProduct.length > 0) {
                for (j = 0; j < discountProduct[i].options.length; j++){
                    if (discountProduct[i].options[j].value == errorDiscountProduct[i]) {
                        discountProduct[i].options[j].selected = true;
                    }
                }
            }
            if (productAlert.length > 0) {
                document.getElementsByClassName('product-error')[i].innerHTML = productAlert[i];
            }
            if (errorMinimumSpendAmount.length > 0) {
                minimumSpendAmount[i].value = errorMinimumSpendAmount[i];
            }
            if (minimumSpendAlert[i] != undefined) {
                document.getElementsByClassName('minimum-spend-amount-error')[i].innerHTML = minimumSpendAlert[i];
            }
        }
    }
}

function sessionAddMinimumSpendRow() {
    if (flag == 1) {
        minimumSpendContainer.push(minimumSpendContainer[minimumSpendContainer.length - 1] + 1);
        sessionRenderMinimumSpendTemplate();
    }
    if (flag == 2) {
        for (i = 0; i < discountProduct.length; i++) {
            if (discountProduct.length <= ((discountProduct[0].options.length)-1)) {
                minimumSpendContainer.push(minimumSpendContainer[minimumSpendContainer.length - 1] + 1);
                sessionRenderMinimumSpendTemplate();
                break;
            }
        }
    }
}

const editMiniumSpendContainer = [];

function editTemplate() {
    var discountCategory = document.querySelector('.edit-category');
    discountCategory.onchange = function () {
        editCategoryTemplate();
    }
}

function editCategoryTemplate() {
    if (discountCategory.value == "1")
    {
        document.querySelector('.category1').classList.remove('d-none');
        document.querySelector('.category2').classList.add('d-none');
        flag = 1;
    }
    if (discountCategory.value == "2")
    {
        document.querySelector('.category2').classList.remove('d-none');
        document.querySelector('.category1').classList.add('d-none');
        flag = 2;
    }
    availableEditPageOption();
};

function availableEditPageOption() {
    if (flag == 1 && errorDiscountDigit.length > 0) {
        for (i = 0; i < (errorDiscountDigit.length); i++) {
            editMiniumSpendContainer.push(i);
        }
    }
    else if (flag == 2 && errorDiscountProduct.length > 0) {
        for (i = 0; i < (errorDiscountProduct.length); i++) {
            editMiniumSpendContainer.push(i);
        }
    }
    else {
        for (i = 0; i < (totalDiscounts); i++) {
            editMiniumSpendContainer.push(i);
        }
    }
    editPageFetchTemplate();
}

function editPageFetchTemplate() {
    if (flag == 1)
        document.getElementsByClassName('minimum-spend-row-container1')[0].innerHTML = '';
    if (flag == 2)
        document.getElementsByClassName('minimum-spend-row-container2')[0].innerHTML = '';
    for (const key in editMiniumSpendContainer) {
        if (flag == 1) {
            minimumSpendRowContainer = document.getElementsByClassName('minimum-spend-row-container1')[0];
            minimumSpendRowTemplate = document.getElementById('minimum-spend-template2').innerHTML;
            minimumSpendRowContainer.innerHTML += minimumSpendRowTemplate;
            if (key === "0") {
                document.querySelector('.remove-minimum-spend1').setAttribute('class', 'd-none');
            }
        }
        if (flag == 2) {
            minimumSpendRowContainer = document.getElementsByClassName('minimum-spend-row-container2')[0];
            minimumSpendRowTemplate = document.getElementById('minimum-spend-template1').innerHTML;
            minimumSpendRowContainer.innerHTML += minimumSpendRowTemplate;
            if (key === "0") {
                document.querySelector('.remove-minimum-spend2').setAttribute('class', 'd-none');
            }
        }
    }
    if (flag == 1) {
        removeObject = document.getElementsByClassName('remove-minimum-spend1');
        removeTemplate = document.getElementsByClassName('remove-minimum-spend-row-1')[0];
        [...removeObject].forEach((remove, i) => {
            remove.innerHTML += removeTemplate.innerHTML;
            document.querySelectorAll('.fa')[i].onclick = function () {
                editProductRemoveMinimumSpendRow(i);
            }
        });
        var discountTypeSelections = document.getElementById('discountType');
        for (i = 0; i < discountTypeSelections.options.length; i++){
            if (discountTypeSelections.options[i].value == discountType) {
                discountTypeSelections.options[i].selected = true;
            }
        }
        for (i = 0; i < discountDigits.length; i++){
            discountDigit[i].value = discountDigits[i];
            minimumSpendAmount[i].value = minimumSpendAmounts[i];
        }
        if (errorDiscountDigit.length > 0) {
            for (i = 0; i < discountDigit.length; i++) {
                if (errorDiscountDigit.length > 0) {
                    discountDigit[i].value = errorDiscountDigit[i];
                }
                if (errorMinimumSpendAmount.length > 0) {
                    minimumSpendAmount[i].value = errorMinimumSpendAmount[i];
                }
                if (minimumSpendAlert[i] != undefined) {
                    document.getElementsByClassName('minimum-spend-amount-error')[i].innerHTML = minimumSpendAlert[i];
                }
                if (digitAlert[i] != undefined && digitAlert[i] != '[object Object]') {
                    document.getElementsByClassName('digit-error')[i].innerHTML = digitAlert[i];
                }
            }
        }
    }
    if (flag == 2) {
        removeObject = document.getElementsByClassName('remove-minimum-spend2');
        removeTemplate = document.getElementsByClassName('remove-minimum-spend-row-2')[0];
        [...removeObject].forEach((remove, i) => {
            remove.innerHTML += removeTemplate.innerHTML;
            document.querySelectorAll('.fa')[i].onclick = function () {
                editProductRemoveMinimumSpendRow(i);
            }
        });
        for (i = 0; i < discountProducts.length; i++) {
            discountProduct[i].value = discountProducts[i];
            minimumSpendAmount[i].value = minimumSpendAmounts[i];
        }
        if (errorDiscountProduct.length > 0) {
            for (i = 0; i < discountProduct.length; i++) {
                if (errorDiscountProduct.length > 0) {
                    discountProduct[i].value = errorDiscountProduct[i];
                }
                if (errorMinimumSpendAmount.length > 0) {
                    minimumSpendAmount[i].value = errorMinimumSpendAmount[i];
                }
                if (minimumSpendAlert[i] != undefined) {
                    document.getElementsByClassName('minimum-spend-amount-error')[i].innerHTML = minimumSpendAlert[i];
                }
                if (productAlert[i] != undefined && productAlert[i] != '[object Object]') {
                    document.getElementsByClassName('product-error')[i].innerHTML = productAlert[i];
                }
            }
        }
    }
}

function editDigitRemoveMinimumSpendRow(index) {
    if (index + 1 > -1) {
        discountDigits.splice(index + 1, 1);
        minimumSpendAmounts.splice(index + 1, 1);
        editMiniumSpendContainer.splice(index + 1, 1);
        editPageFetchTemplate();
    }
}

function editProductRemoveMinimumSpendRow(index) {
    if (index + 1 > -1) {
        discountProducts.splice(index + 1, 1);
        minimumSpendAmounts.splice(index + 1, 1);
        editMiniumSpendContainer.splice(index + 1, 1);
        editPageFetchTemplate();
    }
}

function editPageAddMinimumRow() {
    if (flag == 1) {
        editMiniumSpendContainer.push(editMiniumSpendContainer[editMiniumSpendContainer.length - 1] + 1);
        editPageFetchTemplate();
    }
    if (flag == 2) {
        for (i = 0; i < discountProduct.length; i++) {
            if (discountProduct.length <= ((discountProduct[i].options.length)-2)) {
                editMiniumSpendContainer.push(editMiniumSpendContainer[editMiniumSpendContainer.length - 1] + 1);
                editPageFetchTemplate();
                break;
            }
        }
    }
}