const cart = [];
let subTotal = 0;
let tax = 0;

function addToCart(id) {
    const product = {
        "id": id,
        "name": document.getElementById('name-' + id).innerHTML,
        "img": document.getElementById('image-' + id).src,
        "price": document.getElementById('price-' + id).innerHTML,
        "tax": document.getElementById('tax-' + id).innerHTML,
        "quantity": 1
    };
    if (existsInArray(id)) {
        var index = cart.findIndex((obj => obj.id == id));
        cart[index].quantity += 1;
        var price = document.getElementById('price-' + id).innerHTML;
        price = parseInt(price.slice(1));
        price *= cart[index].quantity;
        cart[index].price = "$" + price;
    } else {
        cart.push(product);
    }
    displayCart();
}

function existsInArray(id) {
    return cart.some(function (element) {
        return element.id === id;
    });
}

function displayCart() {
    subTotal = 0;
    tax = 0;

    document.getElementById('hidden-form').innerHTML = "";
    document.getElementById('container').innerHTML = "";

    let id = 1;
    for (let i = 0; i < cart.length; i++) {

        var divMain = document.createElement('div');
        divMain.setAttribute('class', 'flex flex-row justify-between items-center mb-4');
        divMain.setAttribute('id', 'div-main-' + id);
        document.getElementById('container').appendChild(divMain);

        var divMainSubFirst = document.createElement('div');
        divMainSubFirst.setAttribute('class', 'flex flex-row items-center w-2/5');
        divMainSubFirst.setAttribute('id', 'div-sub-first-' + id);
        document.getElementById('div-main-' + id).appendChild(divMainSubFirst);

        var productId = cart[i].id;

        var inputId = document.createElement('input');
        inputId.setAttribute('type', 'hidden');
        inputId.setAttribute('name','productId[]');
        inputId.value = productId;
        document.getElementById('hidden-form').appendChild(inputId);



        var productImg = cart[i].img;
        var productName = cart[i].name;
        var productPrice = cart[i].price;

        var inputPrice = document.createElement('input');
        inputPrice.setAttribute('type', 'hidden');
        inputPrice.setAttribute('name','productPrice[]');
        inputPrice.value = productPrice;
        document.getElementById('hidden-form').appendChild(inputPrice);




        var productQuantity = cart[i].quantity;
        var inputQuantity = document.createElement('input');
        inputQuantity.setAttribute('type', 'hidden');
        inputQuantity.setAttribute('name','productQuantity[]');
        inputQuantity.value = productQuantity;
        document.getElementById('hidden-form').appendChild(inputQuantity);

        subTotal += parseInt((cart[i].price).slice(1));
        tax += parseInt((cart[i].tax).slice(0, -1));



        var imageTag = document.createElement('img');
        imageTag.src = productImg;
        imageTag.setAttribute('class', 'w-10 h-10 object-cover rounded-md');
        imageTag.setAttribute('alt', productName);
        document.getElementById('div-sub-first-' + id).appendChild(imageTag);

        var spanTag = document.createElement('span');
        spanTag.setAttribute('class', 'ml-4 font-semibold text-sm');
        spanTag.innerHTML = productName;
        document.getElementById('div-sub-first-' + id).appendChild(spanTag);

        var divMainSubSecond = document.createElement('div');
        divMainSubSecond.setAttribute('class', 'w-32 flex justify-between');
        divMainSubSecond.setAttribute('id', 'div-sub-second-' + id);
        document.getElementById('div-main-' + id).appendChild(divMainSubSecond);

        var decreaseButton = document.createElement('button');
        decreaseButton.setAttribute('class', 'px-3 py-1 rounded-md bg-gray-300');
        decreaseButton.setAttribute('onclick', 'changeQuantity(' + productId + ',' + id + ',' + '"decrease"' + ')');
        decreaseButton.innerHTML = "-";
        document.getElementById('div-sub-second-' + id).appendChild(decreaseButton);

        var inputTag = document.createElement('input');
        inputTag.setAttribute('class', 'mx-2 border text-center w-8');
        inputTag.setAttribute('id', 'input-id-' + id);
        inputTag.setAttribute('onchange', 'inputQuantity(' + productId + ',' + id + ')');
        inputTag.setAttribute('value', productQuantity);
        document.getElementById('div-sub-second-' + id).appendChild(inputTag);

        var increaseButton = document.createElement('button');
        increaseButton.setAttribute('class', 'px-3 py-1 rounded-md bg-gray-300');
        increaseButton.setAttribute('onclick', 'changeQuantity(' + productId + ',' + id + ',' + '"increase"' + ')');
        increaseButton.innerHTML = "+";
        document.getElementById('div-sub-second-' + id).appendChild(increaseButton);


        var divMainSubThird = document.createElement('div');
        divMainSubThird.setAttribute('class', 'font-semibold text-lg w-16 text-center');
        divMainSubThird.setAttribute('id', 'div-sub-third-' + id);
        divMainSubThird.innerHTML = productPrice;
        document.getElementById('div-main-' + id).appendChild(divMainSubThird);


        var deleteIcon = document.createElement('i');
        deleteIcon.setAttribute('class', 'material-icons');
        deleteIcon.setAttribute('onclick', 'removeCart(' + productId + ')');
        deleteIcon.innerHTML = "delete";
        document.getElementById('div-main-' + id).appendChild(deleteIcon);

        document.getElementById('subtotal').innerHTML = "$" + subTotal;
        document.getElementById('sales-tax').innerHTML = "Sales Tax(" + tax + "%)";

        var discountPercentage = document.getElementById("discount-percentage").innerHTML.trim();
        var discountPrice = subTotal * (discountPercentage / 100);
        document.getElementById("discount-price").innerHTML = "$" + discountPrice;
        var taxPrice = subTotal * (tax / 100);
        document.getElementById("sales-tax-price").innerHTML = "$" + (taxPrice).toFixed(2);
        var total = (subTotal - (subTotal * (discountPercentage / 100))) + (subTotal * (tax / 100));
        document.getElementById("total").innerHTML = "$" + total.toFixed(2);

        var inputTax = document.createElement('input');
        inputTax.setAttribute('type', 'hidden');
        inputTax.setAttribute('name','productTax[]');
        inputTax.value = cart[i].tax;
        document.getElementById('hidden-form').appendChild(inputTax);

        var inputTaxAmount = document.createElement('input');
        inputTaxAmount.setAttribute('type', 'hidden');
        inputTaxAmount.setAttribute('name','productTaxAmount[]');
        inputTaxAmount.value = taxPrice;
        document.getElementById('hidden-form').appendChild(inputTaxAmount);

        var inputDiscount = document.createElement('input');
        inputDiscount.setAttribute('type', 'hidden');
        inputDiscount.setAttribute('name','discountOfProduct');
        inputDiscount.value = "$" + discountPrice;
        document.getElementById('hidden-form').appendChild(inputDiscount);

        var inputSubtotal = document.createElement('input');
        inputSubtotal.setAttribute('type', 'hidden');
        inputSubtotal.setAttribute('name','subtotalOfProduct');
        inputSubtotal.value = "$" + subTotal;
        document.getElementById('hidden-form').appendChild(inputSubtotal);

        var inputTotalTaxPrice = document.createElement('input');
        inputTotalTaxPrice.setAttribute('type', 'hidden');
        inputTotalTaxPrice.setAttribute('name','totalTaxPrice');
        inputTotalTaxPrice.value = "$" + (taxPrice).toFixed(2);
        document.getElementById('hidden-form').appendChild(inputTotalTaxPrice);

        var inputTotal = document.createElement('input');
        inputTotal.setAttribute('type', 'hidden');
        inputTotal.setAttribute('name','totalOfProduct');
        inputTotal.value = "$" + total.toFixed(2);
        document.getElementById('hidden-form').appendChild(inputTotal);
        id++;
    }
}

function inputQuantity(productId, id) {
    var indexOfProduct = cart.findIndex((obj => obj.id == productId));
    if (isNaN(document.getElementById('input-id-' + id).value)) {
        alert("Enter Only Number");
        displayCart();
    } else {
        cart[indexOfProduct].quantity = document.getElementById('input-id-' + id).value;
        price = parseInt((document.getElementById('price-' + id).innerHTML).slice(1)) * cart[indexOfProduct].quantity;
        cart[indexOfProduct].price = '$' + price;
        displayCart();
    }
}

function changeQuantity(productId, id, checked) {
    var indexOfProduct = cart.findIndex((obj => obj.id == productId));
    cart[indexOfProduct].quantity = isNaN(cart[indexOfProduct].quantity) ? 0 : cart[indexOfProduct].quantity;
    if (checked == "increase") {
        cart[indexOfProduct].quantity += 1;
        price = parseInt((document.getElementById('price-' + id).innerHTML).slice(1)) * cart[indexOfProduct].quantity;
        cart[indexOfProduct].price = '$' + price;
    }
    if (checked == "decrease") {
        cart[indexOfProduct].quantity = isNaN(cart[indexOfProduct].quantity) ? 0 : cart[indexOfProduct].quantity;
        if (cart[indexOfProduct].quantity > 1) {
            cart[indexOfProduct].quantity -= 1;
            price = parseInt((document.getElementById('price-' + id).innerHTML).slice(1)) * cart[indexOfProduct].quantity;
            cart[indexOfProduct].price = '$' + price;
        } else {
            removeCart(productId);
        }
    }
    displayCart();
}

function removeCart(productId) {
    var indexOfProduct = cart.findIndex((obj => obj.id == productId));
    if (indexOfProduct > -1) {
        cart.splice(indexOfProduct, 1);
    }
    document.getElementById('subtotal').innerHTML = "$" + 0;
    document.getElementById('sales-tax').innerHTML = "Sales tax()";
    document.getElementById('discount-price').innerHTML = "$" + 0;
    document.getElementById('total').innerHTML = "$" + 0;
    displayCart();
}

