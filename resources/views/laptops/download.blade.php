<table>
    <thead>
        <tr>
            <th rowspan="2">Members</th>
            <th rowspan="2">Office PC brought home? (Y/N)</th>
            <th rowspan="1" colspan="2">PEZA</th>
            <th rowspan="2">VPN Access? (Y/N)</th>
            <th rowspan="2">Tag Number</th>
            <th rowspan="2">Laptop Make</th>
            <th rowspan="2">Model</th>
            <th rowspan="2">CPU</th>
            <th rowspan="2">Clock Speed (GHz)</th>
            <th rowspan="2">RAM (GB)</th>
            <th rowspan="2">Remarks</th>
            <th rowspan="2">Last Updated</th>
        </tr>
        <tr>
            <th rowspan="1">Form Number</th>
            <th rowspan="1">Permit Number</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($detail as $item)
            <tr>
                <td>{{ !empty($item['owner']) ? $item['owner'] . (!empty($item['owner_suffix']) ? " " . $item['owner_suffix'] : '') : ($item['status_num'] ? '※No Assignee' : '※Laptop is inactive') }}</td>
                <td>{{ !empty($item['linkage_id']) ? $item['brought_home_flag'] : '' }}</td>
                <td>{{ $item['peza_form_number'] }}</td>
                <td>{{ $item['peza_permit_number'] }}</td>
                <td>{{ !empty($item['linkage_id']) ? $item['vpn_access'] : '' }}</td>
                <td>{{ $item['tag_number'] }}</td>
                <td>{{ $item['laptop_make'] }}</td>
                <td>{{ $item['laptop_model'] }}</td>
                <td>{{ $item['laptop_cpu'] }}</td>
                <td>{{ $item['laptop_clock_speed'] }}</td>
                <td>{{ $item['laptop_ram'] }}</td>
                <td>{{ !empty($item['remarks']) ? $item['remarks'] : '' }}</td>
                <td>{{ !empty($item['last_update']) ? date('Y-m-d', strtotime($item['last_update'])) : '' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
