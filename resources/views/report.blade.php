@extends('layout.app')

@section('style')
    <style>
        /* Custom Styles for Better Design */
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }

        .bill-list-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin: 20px auto;
            max-width: 1200px;
        }

        .bill-list-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #343a40;
        }

        .alert {
            margin-bottom: 20px;
            padding: 10px;
            border-radius: 5px;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }

        .table th {
            background-color: #007bff;
            color: white;
            text-align: center;
        }

        .table td {
            text-align: center;
        }

        .btn {
            border-radius: 5px;
        }

        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-sm {
            padding: 5px 10px;
        }

        .btn:hover {
            opacity: 0.9;
        }

        form button[type="submit"] {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }

        form button[type="submit"]:hover {
            background-color: #c82333;
        }

        @media (max-width: 768px) {
            .bill-list-container {
                padding: 15px;
                margin: 10px;
            }

            .table th,
            .table td {
                font-size: 12px;
            }
        }
    </style>
@endsection

@section('content')
    

    <div class="container mt-5">

        <form action="{{ route('calculateBill') }}" method="GET">
            <label for="month">MONTH</label>
            <select name="month" id="month">
                <option value="">Please Select</option>
                <option value="January">January</option>
                <option value="February">February</option>
                <option value="Mac">Mac</option>
                <option value="April">April</option>
                <option value="May">May</option>
                <option value="June">June</option>
                <option value="July">July</option>
                <option value="August">August</option>
                <option value="September">September</option>
                <option value="October">October</option>
                <option value="November">November</option>
                <option value="December">December</option>
                <!-- Add options for other months -->
            </select>
    
            <label for="building_type">BUILDING TYPE</label>
            <select name="building_type" id="building_type">
                <option value="">Please Select</option>
                <option value="Residential">Residential</option>
                <option value="Commercial">Commercial</option>
            </select>
    
            <button type="submit">Search</button>
        </form>
        
        <h2>Bill Report for {{ $month }} - {{ $buildingType }}</h2>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Customer Name</th>
                    <th>Building Type</th>
                    <th>Month</th>
                    <th>Usability (kWh)</th>
                    <th>Total Bill (RM)</th>
                    <th>Breakdown</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($reportData as $data)
                    <tr>
                        <td>{{ $data['customer_name'] }}</td>
                        <td>{{ ucfirst($data['building_type']) }}</td>
                        <td>{{ $data['month'] }}</td>
                        <td>{{ $data['usability'] }}</td>
                        <td>RM{{ number_format($data['total_bill'], 2) }}</td>
                        <td>
                            <ul>
                                @foreach ($data['breakdown'] as $breakdown)
                                    <li>
                                        {{ $breakdown['category'] }}: {{ $breakdown['usage'] }} kWh x
                                        RM{{ number_format($breakdown['rate'], 2) }}/kWh =
                                        RM{{ number_format($breakdown['cost'], 2) }}
                                    </li>
                                @endforeach
                            </ul>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
