<div class="category1 d-none">
    <div class="form-group">
        <label for="discountType">Type Of Discount</label>
        <select id="discountType" class="form-control" name="type">
            <option value="">--Select Type--</option>
            <option value="1"
            <?php
                if (isset($_SESSION['type'])) {
                    if ($_SESSION['type'] == "1") {
                        echo 'selected="selected"';
                        unset($_SESSION['type']);
                    }
                }
            ?>
            >%</option>
            <option value="2"
            <?php
                if (isset($_SESSION['type'])) {
                    if ($_SESSION['type'] == "2") {
                        echo 'selected="selected"';
                        unset($_SESSION['type']);
                    }
                }
            ?>
            >$</option>
        </select>
        <label class="text-danger">
            <?php
                if (isset($_SESSION['type_alert'])) {
                    echo $_SESSION['type_alert'];
                    unset($_SESSION['type_alert']);
                }
            ?>
        </label>
    </div>
    <h6><u><b>Minimum Spends</b></u></h6>
    <div class="form-group">
        <div>
            <button type="button"
                class="input-group-text bg-primary text-white"
                style="margin-left: 350px;"
                onclick="addMinimumSpendRow()"
            >
                Add new
            </button>
        </div>
        <div class="minimum-spend-row-container1">
            <!-- Here added Minimum Spends Row using javascript -->
        </div>
    </div>
</div>
<template id="minimum-spend-template2">
    <div class="input-group">
        <div class="input-group-append">
            <label for="minimum-amount">Minium Spend Amount</label>
            <input type="number"
                class="form-control minimum-spend-amount"
                placeholder="Minium Spend Amount"
                name="minimum_spend_amount[]"
                 step = "0.01"
            >
            <label class="text-danger minimum-spend-amount-error"></label>
        </div>
        <div class="input-group-append">
            <label>Discount digit</label>
            <input type="number"
                class="form-control digit"
                placeholder="Discount digit"
                name="digit[]"
                step = "0.01"
            >
            <label class="text-danger digit-error"></label>
        </div>
        <div class="input-group-append remove-minimum-spend1">
            <!-- Remove button create from javascript -->
        </div>
    </div>
</template>
<template class="remove-minimum-spend-row-1">
    <i class="fa fa-trash-o"></i>
</template>