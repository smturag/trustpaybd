<?php
    use Carbon\Carbon;
?>

<table id="modem_table" class="table table-hover mb-0 text-center align-middle table-bordered">
    <thead>
        <tr>
            <th scope="col" class="text-center">#</th>
            <th scope="col">Date</th>
            <th scope="col">Agent</th>
            <th scope="col">Deviceid</th>
            <th scope="col">Operator</th>
            <th scope="col">Sim</th>
            <th scope="col">Modem Details</th>
            <th scope="col">Operating Status</th>
            <th scope="col">Operator Service</th>
            <th scope="col">Status</th>
            <th scope="col">Action</th>

        </tr>
    </thead>
    <tbody>
        <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php

            $time = time();
            $time_check = $time - env('TRANSACTION_INPUT_TIME');
            if ($row->up_time >= $time_check) {
                $mdmsts = 'Online';
            } else {
                $mdmsts = 'Offline';
            }
            ?>
            <tr id="<?php echo e($row->id); ?>">
                <td class="text-center"><?php echo e($row->id); ?></td>
                <!--<td><?php echo e(bdtime($row->created_at)); ?></td>-->
                <td>
                    <?php echo e(date('d F Y', strtotime($row->created_at))); ?><br>
                    <?php echo e(date('h:i:s A', strtotime($row->created_at))); ?>

                </td>

                <td><?php echo e($row->member_code); ?></td>
                <td><?php echo e($row->deviceid); ?></td>
                <td><?php echo e($row->operator); ?></td>
                <td><?php echo e($row->sim_number); ?></td>
                <td style="width: 150px; word-wrap: break-word; white-space: normal;">
                    <?php echo e($row->modem_details); ?>

                </td>

                <td>

                    <?php
                        $status_name = 'Dual Off';

                        if ($row->operating_status == 1) {
                            $status_name = 'Only Cash In';
                        } elseif ($row->operating_status == 2) {
                            $status_name = 'Only Cash Out';
                        } elseif ($row->operating_status == 3) {
                            $status_name = 'Cash In + Cash Out';
                        }
                    ?>

                    <div class="dropdown">
                        <button class="btn btn-success dropdown-toggle" type="button" data-bs-toggle="dropdown"
                            aria-expanded="false"><?php echo e($status_name); ?></button>
                        <ul class="dropdown-menu">
                            <?php if($row->operating_status != 0): ?>
                                <li><a class="dropdown-item" href="#"
                                        onclick="changeOperatingStatus(<?php echo e($row->id); ?>, 0)">Dual Off</a>
                                </li>
                            <?php endif; ?>
                            <?php if($row->operating_status != 1): ?>
                                <li><a class="dropdown-item" href="#"
                                        onclick="changeOperatingStatus(<?php echo e($row->id); ?>, 1)">Only Cash In</a>
                                </li>
                            <?php endif; ?>
                            <?php if($row->operating_status != 2): ?>
                                <li><a class="dropdown-item" href="#"
                                        onclick="changeOperatingStatus(<?php echo e($row->id); ?>, 2)">Only Cash Out</a>
                                </li>
                            <?php endif; ?>
                            <?php if($row->operating_status != 3): ?>
                                <li><a class="dropdown-item" href="#"
                                        onclick="changeOperatingStatus(<?php echo e($row->id); ?>, 3)">Dual On</a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>

                </td>

                <?php
                    // $operator_service_status = 'Dual On';
                    $payment_methods_array = explode(',', $row->operator);

                    if($row->operator_service == 'on'){
                        $meke_operator_service = 'Dual On';
                    }else if($row->operator_service == 'off'){
                        $meke_operator_service =  'Dual Off';
                    }else{
                        foreach ($payment_methods_array as $operator) {
                            if ($row->operator_service == $operator) {
                                $meke_operator_service = $operator; // Set to the matched operator
                                break; // Exit loop when a match is found
                            }
                        }
                    }

                ?>
                <td>
                    <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"><?php echo e($meke_operator_service); ?></button>
                        <ul class="dropdown-menu">
                            <?php if($row->operator_service != 'on'): ?>
                            <li><a class="dropdown-item" onclick="return confirm('Are you sure want change status? ')" href="<?php echo e(route('admin.modem_operating_service_status',['modem_id'=>$row->id, 'status'=>'on'])); ?>">Dual On</a>
                            </li>
                            <?php endif; ?>

                            <?php if($row->operator_service != 'off'): ?>
                            <li><a class="dropdown-item" onclick="return confirm('Are you sure want change status? ')" href="<?php echo e(route('admin.modem_operating_service_status',['modem_id'=>$row->id, 'status'=>'off'])); ?>">Dual Off</a>
                            </li>
                            <?php endif; ?>

                            

                            <?php $__currentLoopData = $payment_methods_array; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                             <?php if($row->operator_service != $item): ?>
                             <li><a class="dropdown-item" onclick="return confirm('Are you sure want change status? ')" href="<?php echo e(route('admin.modem_operating_service_status',['modem_id'=>$row->id, 'status'=>$item])); ?>"><?php echo e($item); ?></a>
                             </li>
                             <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


                        </ul>
                    </div>
                </td>
                
                <td class="text-center"><?php echo e($mdmsts); ?></td>
                <td>
                    <div class="btn-group">
                        <button type="button" class="btn btn-outline-primary">Make Action</button>
                        <button type="button" class="btn btn-outline-primary dropdown-toggle dropdown-toggle-split"
                            data-bs-toggle="dropdown" aria-expanded="false"> <span class="visually-hidden">Toggle
                                Dropdown</span>
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <button class="btn btn-sm btn-outline-danger" id="<?php echo e($row->id); ?>"
                                    onClick="band_record(this.id, '<?php echo e($row->deviceid); ?>')"><i
                                        class="lni lni-cross-circle" aria-hidden="true"></i>Band</button>
                            </li>
                            <li>
                                <button class="btn btn-sm btn-outline-info" id="<?php echo e($row->id); ?>"
                                    onClick="logout_record(this.id, '<?php echo e($row->deviceid); ?>')"><i
                                        class="lni lni-cross-circle" aria-hidden="true"></i>Log Out</button>
                            </li>
                            <li>
                                <button class="btn btn-sm btn-danger delete" id="<?php echo e($row->id); ?>"
                                    onClick="delete_record(this.id, '<?php echo e($row->deviceid); ?>')"><i
                                        class="lni lni-cross-circle" aria-hidden="true"></i>Delete</button>
                            </li>
                            <li>
                                <a href="#" class="openPopup btn btn-sm btn-success" id="<?php echo e($row->id); ?>"
                                    data-bs-toggle="modal" data-bs-target="#myModal"><i class="bx bx-user"
                                        aria-hidden="true"></i>Merchant Set</a>
                            </li>

                        </ul>
                    </div>
                    <!--</td>-->
                    <!--	 <td class="text-center">-->
                    <!--<a class="btn btn-sm btn-outline-primary" href="<?php echo e(route('user_edit', ['id' => $row->id])); ?>"><i class="bx bx-edit" aria-hidden="true"></i>Edit</a>-->
                    <!--              <button class="btn btn-sm btn-outline-danger" id="<?php echo e($row->id); ?>" onClick="band_record(this.id, '<?php echo e($row->deviceid); ?>')"><i class="lni lni-cross-circle" aria-hidden="true"></i>Band</button>-->
                    <!--              <button class="btn btn-sm btn-outline-info" id="<?php echo e($row->id); ?>" onClick="logout_record(this.id, '<?php echo e($row->deviceid); ?>')"><i class="lni lni-cross-circle" aria-hidden="true"></i>Log Out</button>-->
                    <!--              <button class="btn btn-sm btn-danger delete" id="<?php echo e($row->id); ?>" onClick="delete_record(this.id, '<?php echo e($row->deviceid); ?>')"><i class="lni lni-cross-circle" aria-hidden="true"></i>Delete</button>-->
                    <!--		 <a href="#" class="openPopup btn btn-sm btn-success" id="<?php echo e($row->id); ?>" data-bs-toggle="modal" data-bs-target="#myModal"><i class="bx bx-user" aria-hidden="true"></i>Merchant Set</a>-->
                    <!--          </td>-->
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <?php if($data->isEmpty()): ?>
            <tr>
                <td colspan="15" style="text-align: center;padding: 50px;font-size: 18px;">No Record Found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>


<?php echo $data->links('common.pagination-custom'); ?>

<?php /**PATH F:\Rahim Vai\Gateway\payment-gateway\resources\views/admin/admin_modem/modem_table_content.blade.php ENDPATH**/ ?>