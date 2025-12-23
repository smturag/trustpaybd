<?php
$userata = $userdata;
$userlevel = $userata->user_level;
$bal = 'bal' . $userlevel;
$reqcost = 'cost' . $userlevel;
$lastbal = $request_data->$bal;
$cost = $request_data->$reqcost;
?>

		
		<div class="modal-content">
            <div class="modal-header">


            <h5 class="modal-title" id="largemodal1">{{ $request_data->sender }} - {{ $request_data->mobile }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
		
        <div class="modal-body">


            <div class="row">
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="mb-0 card-title">{{ translate('request_details') }}</h3>
                        </div>

                        <table class="table  table-hover table-striped table-bordered">
                            <tr>
                                <td>{{ translate('request_with') }}</td>
                                <td><strong>{{ $request_data->request_type }}</strong></td>
                            </tr>
                            
                            <tr>
                                <td>{{ translate('company') }}</td>
                                <td><strong>{{ $request_data->telco }}</strong></td>
                            </tr>
                            <tr>
                                <td>{{ translate('number') }}</td>
                                <td><strong>{{ $request_data->number }}</strong></td>
                            </tr>
                            <tr>
                                <td>{{ translate('amount') }}:</td>
                                <td><strong>{{ $request_data->amount }}</strong></td>
                            </tr>
                            <tr>
                                <td>{{ translate('type') }}:</td>
                                <td><strong>{{ $request_data->type }}</strong></td>
                            </tr>
                            <tr>
                                <td>{{ translate('request_trxid') }}:</td>
                                <td><strong>{{ $request_data->trxid }}</strong></td>
                            </tr>
                            <tr>
                                <td>{{ translate('pre_balance') }}</td>
                                <td><strong>{{ $lastbal+$cost }}</strong></td>
                            </tr>
                            <tr>
                                <td>{{ translate('admin_cost') }}</td>
                                <td><strong>{{ $request_data->cost }}</strong></td>
                            </tr>
                            <tr>
                                <td>{{ translate('cost') }}</td>
                                <td><strong>{{ $cost }}</strong></td>
                            </tr>
                            <tr>
                                <td>{{ translate('last_balance') }}</td>
                                <td><strong>{{ $lastbal }}</strong></td>
                            </tr>


                            <tr>
                                <td>{{ translate('request_time') }}</td>
                                <td><strong><?php echo date('l jS \of F Y h:i:s A', strtotime($request_data->created_at)); ?></strong></td>
                            </tr>
                            <tr>
                                <td>{{ translate('processing_time') }}</td>
                                <td><strong><?php echo date('l jS \of F Y h:i:s A', strtotime($request_data->process_date)); ?></strong></td>
                            </tr>

                           
                            <tr>
                                <td>{{ translate('transaction_ID') }}</td>
                                <td><strong>{{ $request_data->transactionid }} </strong></td>
                            </tr>
                            <tr>
                                <td>{{ translate('result') }}</td>
                                <td><strong>{{ $request_data->result }} </strong></td>
                            </tr>
                            <tr>
                                <td>{{ translate('op_msg') }}</td>
                                <td><strong>{{ $request_data->op_msg }} </strong></td>
                            </tr>
                          
                        </table>

                    </div>
                </div>


                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="mb-0 card-title">{{ translate('sender_information') }}</h3>
                        </div>

                        <table class="table  table-hover table-striped table-bordered">
                            <tr>
                                <td>{{ translate('username') }}:</td>
                                <td><strong>{{ $userdata->username }}</strong></td>
                            </tr>
                            <tr>
                                <td>{{ translate('full_name') }}:</td>
                                <td><strong>{{ $userdata->fullname }}</strong></td>
                            </tr>
                            <tr>
                                <td>{{ translate('email') }}:</td>
                                <td><strong>{{ $userdata->email }}</strong></td>
                            </tr>
                            <tr>
                                <td>{{ translate('mobile') }}:</td>
                                <td><strong>{{ $userdata->mobile }}</strong></td>
                            </tr>
                            <tr>
                                <td>{{ translate('balance') }}:</td>
                                <td><strong>{{ $userdata->balance }}</strong></td>
                            </tr>
                            <tr>
                                <td>{{ translate('daily_limit') }}:</td>
                                <td><strong>{{ $userdata->dlimit }}</strong></td>
                            </tr>
                            <tr>
                                <td>{{ translate('credit_balance') }}:</td>
                                <td><strong>{{ $userdata->credit_balance }}</strong></td>
                            </tr>
                            <tr>
                                <td>{{ translate('password_expire') }} </td>
                                <td><strong><?php echo date('d M y g:i A', strtotime($userdata->pass_expire)); echo " (" . dateDiff($userdata->pass_expire) . " days)"; ?>
                                    </strong></td>
                            </tr>
                            <tr>
                                <td>{{ translate('PIN_expire') }}</td>
                                <td><strong><?php echo date('d M y g:i A', strtotime($userdata->pin_expire)); echo " (" . dateDiff($userdata->pin_expire) . " days)"; ?></strong></td>
                            </tr>
                            </tr>
                            <tr>
                                <td>{{ translate('created') }}</td>
                                <td><strong><?php echo date('d M y g:i A', strtotime($userdata->created_at)); ?></strong></td>
                            </tr>
                            <tr>
                                <td>{{ translate('last_login') }}</td>
                                <td><strong><?php echo date('d M y g:i A', strtotime($userdata->last_login)); ?></strong></td>
                            </tr>
                        </table>

                    </div>
                </div>

            </div>
            
        </div>
        <div class="modal-footer">
           <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
    </div>
