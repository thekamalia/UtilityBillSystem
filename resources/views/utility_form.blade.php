<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Bill Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Custom Styles for Form Design */
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }

        .form-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            max-width: 700px;
            margin: 50px auto;
        }

        .form-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #343a40;
        }

        .form-group label {
            font-weight: bold;
            color: #343a40;
        }

        .form-group input,
        .form-group select {
            border-radius: 5px;
            border: 1px solid #ced4da;
            padding: 10px;
            width: 100%;
            margin-top: 5px;
        }

        .form-group select {
            height: 40px;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .form-group button[type="submit"] {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            width: 100%;
        }

        .form-group button[type="submit"]:hover {
            background-color: #218838;
        }

        .form-group .error-message {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 5px;
        }

        .radio-group {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .radio-group label {
            margin-left: 5px;
            font-weight: normal;
        }

        .form-group label:first-of-type {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
        }
    </style>
</head>

<body>

    <div class="form-container">
        <h2>{{ $action == 'create' ? 'Add New Bill' : 'Edit Bill' }}</h2>

        <form action="{{ route('billform', ['action' => $action, 'bill' => isset($bill) ? $bill : null]) }}"
            method="post" id="bill">
            @csrf

            <div class="form-group">
                <label for="customer_name">Customer Name</label>
                <input type="text" id="customer_name" name="customer_name" class="form-control"
                    value="{{ old('customer_name', $bill ? $bill->customer_name : '') }}" required>
                @error('customer_name')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="building_type">Building Type</label>
                <div class="radio-group">
                    <div>
                        <input type="radio" id="building_type_commercial" name="building_type" value="Commercial"
                            {{ old('building_type', $bill ? $bill->building_type : '') == 'Commercial' ? 'checked' : '' }}>
                        <label for="building_type_commercial">Commercial</label>
                    </div>

                    <div>
                        <input type="radio" id="building_type_residential" name="building_type" value="Residential"
                            {{ old('building_type', $bill ? $bill->building_type : '') == 'Residential' ? 'checked' : '' }}>
                        <label for="building_type_residential">Residential</label>
                    </div>
                </div>
                @error('building_type')
                    <div class="error-message text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="month">Month</label>
                <select id="month" name="month" class="form-control">
                    <option value="January"
                        {{ old('month', $bill ? $bill->month : '') == 'January' ? 'selected' : '' }}>January</option>
                    <option value="February"
                        {{ old('month', $bill ? $bill->month : '') == 'February' ? 'selected' : '' }}>February</option>
                    <option value="March" {{ old('month', $bill ? $bill->month : '') == 'March' ? 'selected' : '' }}>
                        March</option>
                    <option value="April" {{ old('month', $bill ? $bill->month : '') == 'April' ? 'selected' : '' }}>
                        April</option>
                    <option value="May" {{ old('month', $bill ? $bill->month : '') == 'May' ? 'selected' : '' }}>May
                    </option>
                    <option value="June" {{ old('month', $bill ? $bill->month : '') == 'June' ? 'selected' : '' }}>
                        June</option>
                    <option value="July" {{ old('month', $bill ? $bill->month : '') == 'July' ? 'selected' : '' }}>
                        July</option>
                    <option value="August" {{ old('month', $bill ? $bill->month : '') == 'August' ? 'selected' : '' }}>
                        August</option>
                    <option value="September"
                        {{ old('month', $bill ? $bill->month : '') == 'September' ? 'selected' : '' }}>September
                    </option>
                    <option value="October"
                        {{ old('month', $bill ? $bill->month : '') == 'October' ? 'selected' : '' }}>October</option>
                    <option value="November"
                        {{ old('month', $bill ? $bill->month : '') == 'November' ? 'selected' : '' }}>November</option>
                    <option value="December"
                        {{ old('month', $bill ? $bill->month : '') == 'December' ? 'selected' : '' }}>December</option>
                </select>
                @error('month')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="usability">Usability (kWh)</label>
                <input type="number" id="usability" name="usability" class="form-control"
                    value="{{ old('usability', $bill ? $bill->usability : '') }}" required>
                @error('usability')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="state_id">State</label>
                <select id="state_id" name="state_id" class="form-control">
                    @foreach ($states as $state)
                        <option value="{{ $state->id }}"
                            {{ old('state_id', $bill ? $bill->state_id : '') == $state->id ? 'selected' : '' }}>
                            {{ $state->name }}
                        </option>
                    @endforeach
                </select>
                @error('state_id')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group m-2">
                <button type="submit" class="btn btn-primary">
                    {{ $action == 'create' ? 'Add Bill' : 'Update Bill' }}
                </button>
            </div>

        </form>
    </div>

</body>

</html>
