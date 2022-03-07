<div class="hidden overflow-x-hidden overflow-y-auto fixed inset-0 z-50 outline-none focus:outline-none justify-center items-center" id="discount-modal-id">
    <div class="relative w-auto my-6 mx-auto max-w-sm">
        <div class="border-0 rounded-lg shadow-lg relative flex flex-col w-full bg-white outline-none focus:outline-none">
            <div class="flex items-start justify-between p-5 border-b border-solid border-blueGray-200 rounded-t">
                <h3 class="text-3xl font-semibold">
                    Discounts
                </h3>
                <button class="p-1 ml-auto bg-transparent border-0 text-black float-right text-3xl leading-none font-semibold outline-none focus:outline-none" onclick="discountModal('discount-modal-id')">
                    <span class="bg-transparent text-black h-6 w-6 text-2xl block outline-none focus:outline-none">
                        ×
                    </span>
                </button>
            </div>
            <div class="relative p-6 flex-auto">
                <div class="flex justify-center">
                    <div class="mb-3 xl:w-96">
                        <select class="form-select appearance-none block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding bg-no-repeat border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none" aria-label="Default select example" onchange="discountApply()" id="discount-selection">
                            <option value="">--Select discount--</option>
                            <?php for ($i=0;$i<sizeof($discounts);$i++) {?>
                                <option value="<?= $discounts[$i]['id']?>"
                                        <?php
                                            if ($i==0) {
                                                echo "selected";
                                            }
                                        ?> id="discount-option-<?= $discounts[$i]['id']?>">
                                    <?php
                                        if ($discounts[$i]['type'] == 1) {
                                            echo $discounts[$i]['name']."(".$discounts[$i]['digit']."%)";
                                        }
                                        echo $discounts[$i]['name']."($".$discounts[$i]['digit'].")";
                                    ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>