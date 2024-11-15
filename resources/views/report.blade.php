
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
    <form action="{{ route('calculateBill') }}" method="GET">
        <label for="month">MONTH</label>
        <select name="month" id="month">
            <option value="">Please Select</option>
            <option value="January">January</option>
            <option value="February">February</option>
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

    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Customer Name</th>
                <th>Building Type</th>
                <th>Month</th>
                <th>Usability (kWh)</th>
                <th>Bill Amount (RM)</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($bills as $index => $bill)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $bill->customer_name }}</td>
                    <td>{{ ucfirst($bill->building_type) }}</td>
                    <td>{{ $bill->month }}</td>
                    <td>{{ $bill->usability }}</td>
                    <td>RM{{ number_format($bill->bill, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">No bills found for the selected criteria.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
