    {{-- Table Below --}}
    <table class="table table-bordered mt-4" id="crm_dashboard_table">
        <thead>
            <tr>
                <th>Course</th>
                <th>Leads</th>
                <th>Students</th>
                <th>Revenue</th>
                <th>Conversion %</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($dashboardData as $row)
                        @php 
            $row['conversion'] = $row['leads'] == 0 ? 100 : round($row["students"] / $row['leads'] * 100, 2)
            @endphp
                <tr>
                    <td>{{ $row['course_name'] }}</td>
                    <td>{{ $row['leads'] }}</td>
                    <td>{{ $row['students'] }}</td>
                    <td>{{ show_payment($row['revenue'], 2) }}</td>
                    <td>
                        <div class="progress" style="height: 20px;">
                            <div class="progress-bar progress-bar-striped 
                                @if($row['conversion'] >= 75) bg-success
                                @elseif($row['conversion'] >= 50) bg-info
                                @elseif($row['conversion'] >= 25) bg-warning
                                @else bg-danger
                                @endif"
                                role="progressbar" 
                                style="width: {{ $row['conversion'] }}%" 
                                aria-valuenow="{{ $row['conversion'] }}" 
                                aria-valuemin="0" aria-valuemax="100">
                                {{ $row['conversion'] }}%
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <script>
        // Initialize datatable
        new simpleDatatables.DataTable("#crm_dashboard_table", {
            searchable: true,
            perPage: 10
        });
    </script>
