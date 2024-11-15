<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Bill List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
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
</head>

<body>

    <div class="bill-list-container">
        <h2>Bill List</h2>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <a href="{{ route('form', ['action' => 'create']) }}" class="btn btn-success mb-3">Add New Bill</a>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Customer Name</th>
                    <th>Building Type</th>
                    <th>Month</th>
                    <th>Usability (kWh)</th>
                    <th>Bill Amount</th>
                    <th>Actions</th>
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
                        <td>
                            <a href="{{ route('form', ['action' => 'edit', 'bill' => $bill->id]) }}"
                                class="btn btn-primary btn-sm">Edit</a>
                            <form action="{{ route('delete', $bill->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('POST')
                                <button type="submit"
                                    onclick="return confirm('Are you sure you want to delete this bill?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">No bills found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</body>

</html>
