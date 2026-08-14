<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\RestaurantTable;
use App\Models\Customer;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index()
    {
        $reservations = Reservation::with(['customer', 'table'])->orderBy('reservation_time')->get();
        $tables = RestaurantTable::where('status', '!=', 'maintenance')->get();
        $customers = Customer::where('is_active', true)->get();

        return view('reservations.index', compact('reservations', 'tables', 'customers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'table_id' => 'required|exists:restaurant_tables,id',
            'reservation_time' => 'required|date',
            'guest_count' => 'required|integer|min:1',
            'status' => 'required|in:pending,confirmed,cancelled,completed',
            'notes' => 'nullable|string',
        ]);

        $reservation = Reservation::create($request->all());

        // If reservation is confirmed, mark table as reserved
        if ($request->status == 'confirmed') {
            $reservation->table()->update(['status' => 'reserved']);
        }

        return back()->with('success', 'Reservation created!');
    }

    public function update(Request $request, Reservation $reservation)
    {
        $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'table_id' => 'required|exists:restaurant_tables,id',
            'reservation_time' => 'required|date',
            'guest_count' => 'required|integer|min:1',
            'status' => 'required|in:pending,confirmed,cancelled,completed',
            'notes' => 'nullable|string',
        ]);

        $oldTable = $reservation->table_id;
        $oldStatus = $reservation->status;

        $reservation->update($request->all());

        // Update table statuses
        if ($oldTable != $request->table_id || $oldStatus != $request->status) {
            if ($oldTable && $oldStatus == 'confirmed') {
                // Check if old table has any other active reservations
                $otherReservations = Reservation::where('table_id', $oldTable)
                    ->where('id', '!=', $reservation->id)
                    ->where('status', 'confirmed')
                    ->count();
                if ($otherReservations == 0) {
                    RestaurantTable::find($oldTable)->update(['status' => 'available']);
                }
            }
            if ($request->status == 'confirmed') {
                $reservation->table()->update(['status' => 'reserved']);
            }
        }

        return back()->with('success', 'Reservation updated!');
    }

    public function destroy(Reservation $reservation)
    {
        $table = $reservation->table;
        $reservation->delete();

        if ($table) {
            $otherReservations = Reservation::where('table_id', $table->id)
                ->where('status', 'confirmed')
                ->count();
            if ($otherReservations == 0) {
                $table->update(['status' => 'available']);
            }
        }

        return back()->with('success', 'Reservation deleted!');
    }
}
