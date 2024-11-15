<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\State;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\Validator;

class UtilityBillingController extends Controller
{
    use ResponseTrait;

    public function listBill(Request $request)
    {
        $query = Bill::with('state');

        if ($request->filled('building_type')) {
            $query->where('building_type', $request->building_type);
        }

        if ($request->filled('state_id')) {
            $query->where('state_id', $request->state_id);
        }

        if ($request->filled('sort_by') && $request->filled('order')) {
            $query->orderBy($request->sort_by, $request->order);
        }

        $bills = $query->get();
        $states = State::all();

        return view('list_bill', compact('bills', 'states'));
    }


    public function form($action, ?Bill $bill)
    {

        if ($action == 'create') {
            $bill = null;
        }

        $states = State::all();

        return view('utility_form', compact('states', 'bill', 'action'));
    }


    public function billform(Request $request, $action, ?Bill $bill)
    {
        if ($action == 'edit') {
            $bill = Bill::findOrFail($bill->id);
        } elseif ($action == 'create') {
            $bill = new Bill;
        }

        $validator = Validator::make($request->all(), [
            'customer_name' => 'required|string|max:255',
            'state_id' => 'nullable|exists:states,id',
            'month' => 'required|string|max:50',
            'building_type' => 'required|string|max:50',
            'usability' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return $this->responseError($validator->errors()->first(), $validator->errors());
        }

        $validatedData = $validator->validated();
        $usability = $validatedData['usability'];
        $buildingType = $validatedData['building_type'];
        $billAmount = 0;

        if ($buildingType == 'Residential') {
            if ($usability <= 200) {
                $billAmount = $usability * 0.45;
            } else {
                $billAmount = (200 * 0.45) + (($usability - 200) * 0.97);
            }
        } elseif ($buildingType == 'Commercial') {
            if ($usability <= 200) {
                $billAmount = $usability * 0.89;
            } else {
                $billAmount = (200 * 0.89) + (($usability - 200) * 1.13);
            }
        }

        $bill->customer_name = $validatedData['customer_name'];
        $bill->state_id = $validatedData['state_id'];
        $bill->month = $validatedData['month'];
        $bill->building_type = $validatedData['building_type'];
        $bill->usability = $validatedData['usability'];
        $bill->bill = $billAmount;

        $bill->save();

        if ($action == 'create') {
            return redirect()->route('listBill')->with('success', 'Bill record created successfully!');
        } else {
            return redirect()->route('listBill')->with('success', 'Bill record updated successfully!');
        }
    }


    public function delete(Bill $bill)
    {
        $bill->delete();

        return redirect()->route('listBill')->with('success', 'Bill record deleted successfully!');
    }

    public function reportCalculateBill(Request $request)
    {
        // Get the input values for month and building type
        $month = $request->input('month');
        $buildingType = $request->input('building_type');

        // Fetch bills that match the selected month and building type
        $bills = Bill::where('month', $month)
            ->where('building_type', $buildingType)
            ->get();

        // Define tariff rates
        $tariffs = [
            'Residential' => [
                ['threshold' => 200, 'rate' => 0.45],
                ['threshold' => PHP_INT_MAX, 'rate' => 0.97], // Applies to remaining usage
            ],
            'Commercial' => [
                ['threshold' => 200, 'rate' => 0.89],
                ['threshold' => PHP_INT_MAX, 'rate' => 1.13],
            ],
        ];

        // Calculate bill for each usage entry
        foreach ($bills as $bill) {
            $totalBill = 0;
            $remainingUsage = $bill->usability;

            // Apply the rates based on thresholds
            foreach ($tariffs[$buildingType] as $tariff) {
                $applicableUsage = min($remainingUsage, $tariff['threshold']);
                $totalBill += $applicableUsage * $tariff['rate'];
                $remainingUsage -= $applicableUsage;

                if ($remainingUsage <= 0) {
                    break;
                }
            }

            // Update the bill amount in the database
            $bill->bill = $totalBill;
            $bill->save();
        }

        return view('report', compact('bills'));
    }
}
