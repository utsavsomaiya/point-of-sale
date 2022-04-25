<div class="category2 d-none">
    <h6><u><b>Minimum Spends</b></u></h6>
    <div class="form-group">
        <div>
            <button type="button"
                class="input-group-text bg-primary text-white"
                style="margin-left: 350px;"
                onclick="editPageAddMinimumRow()"
            >
                Add new
            </button>
        </div>
        <div class="minimum-spend-row-container2">
            <!-- Here added Minimum Spends Row using javascript -->
        </div>
    </div>
</div>
<template id="minimum-spend-template1">
    <div class="input-group">
        <div class="input-group-append">
            <label for="minimum-amount">Minium Spend Amount</label>
            <input type="number"
                class="form-control minimum-spend-amount"
                placeholder="Minium Spend Amount"
                name="minimum_spend_amount[]"
            >
            <label class="text-danger minimum-spend-amount-error"></label>
        </div>
        <div class="input-group-append">
            <label>Discount Product</label>
            <select class="form-control product" name="products[]">
                <option value = "">--Select Products--</option>
                <?php if (sizeof($products) > 0) { ?>
                    <?php foreach ($products as $product) { ?>
                        <option value = "<?= $product['name'] ?>" ><?= $product['name'] ?></option>
                    <?php } ?>
                <?php } ?>
            </select>
            <label class="text-danger product-error"></label>
        </div>
        <div class="input-group-append remove-minimum-spend2">
            <!-- Remove button create from javascript -->
        </div>
    </div>
</template>
<template class="remove-minimum-spend-row-2">
    <i class="fa fa-trash-o"></i>
</template>