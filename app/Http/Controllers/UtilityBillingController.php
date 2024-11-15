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
        $month = $request->input('month');
        $buildingType = $request->input('building_type');

        $bills = Bill::where('month', $month)
            ->where('building_type', $buildingType)
            ->get();

        $tariffs = [
            'Residential' => [
                ['category' => 'For the first 200 kWh (1 - 200 kWh)', 'threshold' => 200, 'rate' => 0.45],
                ['category' => 'For the next kWh (200 kWh onwards)', 'threshold' => PHP_INT_MAX, 'rate' => 0.97],
            ],
            'Commercial' => [
                ['category' => 'For the first 200 kWh (1 - 200 kWh)', 'threshold' => 200, 'rate' => 0.89],
                ['category' => 'For the next kWh (300 kWh onwards)', 'threshold' => PHP_INT_MAX, 'rate' => 1.13],
            ],
        ];

        $reportData = [];

        foreach ($bills as $bill) {
            $totalBill = 0;
            $remainingUsage = $bill->usability;
            $billBreakdown = []; // To store each step of the calculation

            foreach ($tariffs[$buildingType] as $tariff) {
                $applicableUsage = min($remainingUsage, $tariff['threshold']);
                $cost = $applicableUsage * $tariff['rate'];
                $totalBill += $cost;

                $billBreakdown[] = [
                    'category' => $tariff['category'],
                    'usage' => $applicableUsage,
                    'rate' => $tariff['rate'],
                    'cost' => $cost,
                ];

                $remainingUsage -= $applicableUsage;

                if ($remainingUsage <= 0) {
                    break;
                }
            }

            $bill->bill = $totalBill;
            $bill->save();

            $reportData[] = [
                'customer_name' => $bill->customer_name,
                'building_type' => $bill->building_type,
                'month' => $bill->month,
                'usability' => $bill->usability,
                'total_bill' => $totalBill,
                'breakdown' => $billBreakdown,
            ];
        }

        return view('report', compact('reportData', 'month', 'buildingType'));
    }
}
