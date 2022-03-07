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
                <table class="table-auto">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Apply/Applied</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $count=0; for ($i = 0; $i < sizeof($discounts); $i++) { ?>
                            <tr>
                                <?php $count++; ?>
                                <td id="discount-id-<?= $count ?>"><?= $discounts[$i]['id']; ?></td>
                                <td><?= $discounts[$i]['name']; ?></td>
                                <td id="discount-<?= $count ?>">
                                    <?php
                                        if ($discounts[$i]['type'] == "1") {
                                            echo $discounts[$i]['digit']."%";
                                        } else {
                                            echo "$".$discounts[$i]['digit'];
                                        }
                                    ?>
                                </td>
                                <td>
                                    <?php if ($count == 1) { ?>
                                        <button class="bg-green-500 text-white font-bold py-2 px-4 rounded-full" onclick="discountApply(<?= $count ?>,<?= $discounts[$i]['id'] ?>)" id="discount-button-<?= $count ?>" disabled>
                                            Applied
                                        </button>
                                    <?php } else { ?>
                                        <button class="bg-red-500 text-white font-bold py-2 px-4 rounded-full" onclick="discountApply(<?= $count ?>,<?= $discounts[$i]['id'] ?>)" id="discount-button-<?= $count ?>">
                                            Apply
                                        </button>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php }?>
                    </tbody>
                    </table>
            </div>
        </div>
    </div>
</div>