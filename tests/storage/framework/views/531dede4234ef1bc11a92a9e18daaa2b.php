<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('mrc_content'); ?>
    <?php $__env->startPush('css'); ?>

        <style>
            .token-text {
                background-color: #e4e4e4;
                padding: 13px 10px;
            }
        </style>

    <?php $__env->stopPush(); ?>

    <div class="card radius-10">
        <div class="items" data-index="1">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div>
                        <h5 class="mb-2">Public Key</h5>
                    </div>
                </div>
                <div class="item-content">
                    <div class="mb-3">

                        <div class="d-flex ">
                            <input type="text" class="form-control" id="api_input" placeholder="Name" data-name="name"
                                   name="test[1][name]" value="<?php echo e($merchantToken->api_key); ?>" readonly>

                            <button id="copyButton" class="btn btn-success remove-btn px-4 copy_button ml-2">
                                Copy
                            </button>
                        </div>

                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <div>
                        <h5 class="mb-2">Secret Key</h5>
                    </div>
                </div>
                <div class="item-content">
                    <div class="mb-3">

                        <div class="d-flex ">
                            <input type="text" class="form-control" id="api_input1" placeholder="Name" data-name="name"
                                   name="test[1][name]" value="<?php echo e($merchantToken->secret_key); ?>" readonly>

                            <button id="copyButton1" class="btn btn-success remove-btn px-4 copy_button ml-2">
                                Copy
                            </button>
                        </div>

                    </div>
                </div>

                <div class="repeater-remove-btn">
                    <?php if($merchantToken): ?>
                        <button class="btn btn-success remove-btn px-4 confirmation">

                            <a class="button-link" href="<?php echo e(route('merchant.developer.api-key-generate')); ?>">Regenerate
                                now</a>
                        </button>
                    <?php endif; ?>

                    <?php if(!$merchantToken): ?>
                        <button class="btn btn-success remove-btn px-4 confirmation">

                            <a href="<?php echo e(route('merchant.developer.api-key-generate')); ?>" class="text-white">Generate
                                now</a>
                        </button>
                    <?php endif; ?>


                </div>

            </div>


            <div></div>
        </div>

        <!--<div class="row row-cols-1 row-cols-lg-12 row-cols-xl-4">-->
        <!--    <div class="col-12">-->
        <!--        <div class="card radius-10">-->
        <!--            <div class="card-body">-->
        <!--                <div class="d-flex align-items-center">-->
        <!--                    <div>-->
        <!--                        <p class="mb-0 text-secondary">Api Key</p>-->
        <!--                        <?php if($merchantToken): ?>-->
        <!--                            <p class="my-1 token-text"><?php echo e($merchantToken->token); ?> <span><span-->
        <!--                                        class="ini"></span></span></p>-->
        <!--                            <p class="my-1 token-text confirmation"><a href="<?php echo e(route('merchant.developer.api-key-generate')); ?>">Regenerate now</a></p>-->
        <!--                            <?php else: ?>-->
        <!--                            <p class="my-1 token-text confirmation">No api key generated <a href="<?php echo e(route('merchant.developer.api-key-generate')); ?>">Generate now</a></p>-->

        <!--                        <?php endif; ?>-->
        <!--                    </div>-->

        <!--                </div>-->

        <!--                <div class="d-flex align-items-center mt-3">-->
        <!--                    <div>-->
        <!--                        <p class="mb-0 text-secondary">Developer Guidelines</p>-->

        <!--                        <p class="my-1 ">Lorem ipsum dolor sit amet, consectetur adipisicing elit.-->
        <!--                            Accusantium alias consequuntur error ex expedita id ipsa ipsam magnam magni-->
        <!--                            minus nemo nisi officia omnis perspiciatis praesentium quam, quasi voluptatem.-->
        <!--                            Accusantium ad corporis dolor, dolores, ea maiores minima nam nemo quis repellat-->
        <!--                            sed similique voluptates. Alias atque blanditiis distinctio, enim et explicabo-->
        <!--                            fugit ipsam iste maxime nostrum obcaecati placeat rem suscipit! Accusamus-->
        <!--                            accusantium aliquam aperiam, aspernatur atque beatae blanditiis commodi-->
        <!--                            consequatur consequuntur distinctio dolor dolore doloremque ea est et eum ex-->
        <!--                            fugit magni minima minus modi mollitia necessitatibus neque nulla obcaecati-->
        <!--                            optio perspiciatis possimus quae quis rerum saepe sapiente sequi, soluta tempora-->
        <!--                            ullam unde voluptatem. Ad dolor dolorem earum eligendi esse est hic illo magni-->
        <!--                            molestias officiis optio saepe sequi, sit ullam velit, veritatis, voluptatem?-->
        <!--                            Animi odio quaerat reiciendis. Aut officiis quibusdam sint voluptas. Laboriosam-->
        <!--                            mollitia repellat voluptas! Alias beatae, corporis cum cupiditate delectus-->
        <!--                            deleniti deserunt dignissimos doloribus eligendi facere illum incidunt-->
        <!--                            inventore, ipsam, labore laborum magnam molestias nihil placeat temporibus-->
        <!--                            tenetur. Aspernatur laboriosam nihil non porro sequi. Asperiores commodi dolor-->
        <!--                            earum eos error est inventore itaque porro quibusdam voluptatum! Consectetur-->
        <!--                            distinctio doloremque dolorum facere incidunt iure maiores minima porro-->
        <!--                            provident repellat reprehenderit soluta, tempora, tenetur! Et maxime rerum-->
        <!--                            sapiente voluptatem!</p>-->

        <!--                    </div>-->

        <!--                </div>-->

        <!--            </div>-->
        <!--        </div>-->
        <!--    </div>-->


        <!--</div>-->

    </div>
    <style>
        .button-link {
            display: inline-block;

            color: #fff; /* Set text color to white */
            text-decoration: none; /* Remove underline */
        }

        .button-link:hover {
            text-decoration: none; /* Remove underline on hover */
            color: #fff;
        }
    </style>

<?php $__env->stopSection(); ?>


<?php $__env->startPush('js'); ?>
    <script type="text/javascript">
        var elems = document.getElementsByClassName('confirmation');
        var confirmIt = function (e) {
            if (!confirm('Are you sure?')) e.preventDefault();
        };
        for (var i = 0, l = elems.length; i < l; i++) {
            elems[i].addEventListener('click', confirmIt, false);
        }


        document.getElementById("copyButton").addEventListener("click", function () {
            var textInput = document.getElementById("api_input");
            textInput.select();
            textInput.setSelectionRange(0, 99999);
            document.execCommand("copy");
            textInput.blur();
            alert("API copied!");
        });


        document.getElementById("copyButton1").addEventListener("click", function () {
            var textInput = document.getElementById("api_input1");
            textInput.select();
            textInput.setSelectionRange(0, 99999);
            document.execCommand("copy");
            textInput.blur();
            alert("API copied!");
        });


    </script>

<?php $__env->stopPush(); ?>

<?php echo $__env->make('merchant.mrc_app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\Rahim Vai\Gateway\payment-gateway\resources\views/merchant/developer.blade.php ENDPATH**/ ?>