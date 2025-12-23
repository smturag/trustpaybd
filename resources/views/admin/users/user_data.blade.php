<table class="table table-bordered table-hover mb-0 table-striped">
    <thead>
        <tr>
            <th scope="col" class="text-center">#</th>
            <th scope="col" class="text-center">Member Code</th>
            <th scope="col">Full Name</th>
            <th scope="col">Email</th>
            <th scope="col">Phone</th>
            <th scope="col">Type</th>
            <th scope="col">Auto Req Activated</th>
            <th scope="col">Parent Code</th>
            <th scope="col">Balance</th>
            <th scope="col" class="text-center">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $row)
            <?php $total += $row->balance; ?>
            <tr id="{{ $row->id }}">
                <td class="text-center">{{ $row->id }}</td>
                <td class="text-center">{{ $row->member_code }}</td>
                <td>{{ $row->fullname }}</td>
                <td>{{ $row->email }}</td>
                <td class="text-center">{{ $row->mobile }}</td>
                <td class="text-center text-capitalize">{{ $row->user_type }}</td>
                <td class="text-center text-capitalize">
                    @if ($row->user_type == 'agent')
                        <a href="{{ route('agent_active', ['agent_id' => $row->id]) }}"
                            class="{{ $row->auto_active_agent == 0 ? 'btn btn-primary' : 'btn btn-danger' }}">
                            @if ($row->auto_active_agent == 0)
                                Deactive
                            @else
                                Active
                            @endif
                        </a>
                    @endif

                </td>
                <td class="text-center">
                    <?php $member_code = \DB::table('users')->where('create_by', $row->id)->value('member_code'); ?>
                    @if ($member_code)
                        <button class="btn btn-sm btn-outline-info" id="{{ $row->id }}"
                            onClick="view_parent_detail(this.id, '{{ $row->fullname }}')">{{ $member_code }}</button>
                    @else
                        -
                    @endif
                </td>

                @php

                @endphp
                <td class="text-center">
                    {{-- {{ $getBalance['mainBalance'] }} --}}

                    @if ($row->user_type == 'agent')
                        <a href="javascript:void(0)" data-id="{{ $row->id }}" data-name="{{ $row->fullname }}"
                            data-balance="{{ $row->balance }}" data-route="{{ route('agent_add_balance', $row->id) }}"
                            data-bs-toggle="modal" data-bs-target="#editModal"
                            class="btn btn-sm btn-primary text-white editBtn btn-sm me-2"><i
                                class="bx bx-money"></i>{{ $row->balance }}</a>
                    @elseif($row->user_type == 'partner')
                        <a href="javascript:void(0)" data-id="{{ $row->id }}" data-name="{{ $row->fullname }}"
                            data-balance="{{ $row->available_balance }}" data-route="{{ route('agent_add_balance', $row->id) }}"
                            data-bs-toggle="modal" data-bs-target="#editModal"
                            class="btn btn-sm btn-primary text-white editBtn btn-sm me-2"><i
                                class="bx bx-money"></i>{{ $row->available_balance }}</a> <br>

                        <a href="javascript:void(0)"
                            class="btn btn-sm btn-secondary text-white editBtn btn-sm me-2 mt-2"><i
                                class="bx bx-money"></i>{{ $row->balance }}</a>


                    @endif


                </td>
                <td class="text-center">
                    <a class="btn btn-sm btn-outline-primary"
                        href="{{ route('user_edit', ['id' => $row->id]) }}">Edit</a>
                    <a href="{{ route('userCharge', $row->id) }}" class="btn btn-outline-primary btn-sm">Rate</a>
                    {{-- <button class="btn btn-sm btn-outline-danger delete" id="{{ $row->id }}" onClick="delete_record(this.id, '{{ $row->fullname }}')">Delete</button> --}}
                </td>
            </tr>
        @endforeach


        @if ($data->isEmpty())
            <tr>
                <td colspan="15" style="text-align: center;padding: 50px;font-size: 18px;">No Record Found.</td>
            </tr>
        @else
            <tr>
                <th colspan="7">Total</th>
                <th><?php echo number_format($total, 2); ?></th>
                <th>-</th>
            </tr>
        @endif
    </tbody>
</table>



{!! $data->links('common.pagination-custom') !!}
