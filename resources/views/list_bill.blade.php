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

        <!-- Filter and Sort Form -->
        <form action="{{ route('listBill') }}" method="GET" class="mb-3">
            <div class="form-group">
                <label for="building_type">Building Type:</label>
                <select name="building_type" id="building_type" class="form-control">
                    <option value="">All</option>
                    <option value="Residential" {{ request('building_type') == 'Residential' ? 'selected' : '' }}>
                        Residential</option>
                    <option value="Commercial" {{ request('building_type') == 'Commercial' ? 'selected' : '' }}>
                        Commercial</option>
                </select>
            </div>

            <div class="form-group">
                <label for="state_id">State:</label>
                <select name="state_id" id="state_id" class="form-control">
                    <option value="">All States</option>
                    @foreach ($states as $state)
                        <option value="{{ $state->id }}" {{ request('state_id') == $state->id ? 'selected' : '' }}>
                            {{ $state->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="sort_by">Sort By:</label>
                <select name="sort_by" id="sort_by" class="form-control">
                    <option value="building_type" {{ request('sort_by') == 'building_type' ? 'selected' : '' }}>Building
                        Type</option>
                    <option value="usability" {{ request('sort_by') == 'usability' ? 'selected' : '' }}>Usability
                    </option>
                </select>
            </div>

            <div class="form-group">
                <label for="order">Order:</label>
                <select name="order" id="order" class="form-control">
                    <option value="asc" {{ request('order') == 'asc' ? 'selected' : '' }}>Ascending</option>
                    <option value="desc" {{ request('order') == 'desc' ? 'selected' : '' }}>Descending</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Search</button>
            <a href="{{ route('listBill') }}" class="btn btn-secondary">Reset</a>
        </form>

        <a href="{{ route('form', ['action' => 'create']) }}" class="btn btn-success mb-3">Add New Bill</a>
        
        <!-- Bill List Table -->
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
                        <td colspan="7">No bills found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>


    </div>
@endsection
