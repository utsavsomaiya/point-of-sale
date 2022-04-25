<div class="hidden overflow-x-hidden overflow-y-auto fixed inset-0 z-50 outline-none focus:outline-none justify-center items-center" id="discount-modal-id">
    <div class="relative w-auto my-6 mx-auto max-w-sm">
        <div class="border-0 rounded-lg shadow-lg relative flex flex-col w-full bg-white outline-none focus:outline-none">
            <div class="flex items-start justify-between p-5 border-b border-solid border-blueGray-200 rounded-t">
                <h3 class="text-3xl font-semibold">
                    Discounts
                </h3>
                <button class="p-1 ml-auto bg-transparent border-0 text-black float-right text-3xl leading-none font-semibold outline-none focus:outline-none" onclick="displayApplicableDiscountsModal('discount-modal-id')">
                    <span class="bg-transparent text-black h-6 w-6 text-2xl block outline-none focus:outline-none">
                        ×
                    </span>
                </button>
            </div>
            <div class="relative p-6 flex-auto">
                <table class="table-fixed">
                    <thead>
                        <tr>
                            <th class="pr-5">Id</th>
                            <th class="pr-5">Name</th>
                            <th class="pr-5 w-5" colspan="2">Minimum Spend Amount</th>
                            <th class="pr-5">Price</th>
                        </tr>
                    </thead>
                    <tbody id="discounts-table">
                        <?php $count=0; for ($i = 0; $i < sizeof($discounts); $i++) { ?>
                            <?php $count++; ?>
                            <tr id="discounts-<?= $count ?>" style="display: none;">
                                <label hidden id="discount-tier-id-<?= $count ?>">
                                    <?= $discounts[$i]['tier_id']; ?>
                                </label>
                                <td id="discount-id-<?= $count ?>"><?= $discounts[$i]['id']; ?></td>
                                <td class="pr-3"><?= $discounts[$i]['name']; ?></td>
                                <td class="w-0.5">$</td>
                                <td id="minimum-spend-amount-<?= $count ?>">
                                    <?= $discounts[$i]['minimum_spend_amount']; ?>
                                </td>
                                <td class="flex pt-2">
                                    <?php if ($discounts[$i]['type'] == "1") { ?>
                                        <div id="discount-<?= $count ?>">
                                            <?= $discounts[$i]['discount_digit'] ?>
                                        </div>
                                        <div id="discount-type-<?= $count ?>">
                                            %
                                        </div>
                                    <?php } else { ?>
                                        <div id="discount-type-<?= $count ?>">
                                            $
                                        </div>
                                        <div id="discount-<?= $count ?>">
                                            <?= $discounts[$i]['discount_digit'] ?>
                                        </div>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>