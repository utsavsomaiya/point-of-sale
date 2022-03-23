<template id="minimum-spend-template">
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
            <label>Discount digit</label>
            <input type="number"
                class="form-control digit"
                placeholder="Discount digit"
                name="digit[]"
            >
            <label class="text-danger digit-error"></label>
        </div>
        <div class="input-group-append remove-minimum-spend">
            <!-- Remove button create from javascript -->
        </div>
    </div>
</template>