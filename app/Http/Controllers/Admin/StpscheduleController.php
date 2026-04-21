<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;  
use Illuminate\Http\Request;
use App\Models\Memberstpschedule;
use App\Models\ManageReport;

class StpscheduleController extends Controller
{

    public function searchMember(Request $request)
    {
        $q = trim($request->get('q', ''));

        if (strlen($q) < 1) {
            return response()->json([]);
        }

        $members = ManageReport::where(function ($query) use ($q) {
                $query->where('memberID', 'LIKE', "%{$q}%")
                      ->orWhere('name',     'LIKE', "%{$q}%")
                      ->orWhere('phone',    'LIKE', "%{$q}%");
            })
            ->select('memberID', 'name', 'phone')
            ->orderBy('name')
            ->limit(20)
            ->get();

        return response()->json($members);
    }

    // ─── Index ──────────────────────────────────────────────────────────────────
    public function index()
    {
        $schedules = Memberstpschedule::orderBy('id', 'desc')->get();

        $memberIds   = $schedules->pluck('member_id')->unique()->filter()->values();
        $memberInfos = ManageReport::whereIn('memberID', $memberIds)
                        ->select('memberID', 'name', 'phone')
                        ->get()
                        ->keyBy('memberID');

        return view('admin.stpschedule', compact('schedules', 'memberInfos'));
    }

    // ─── Store ──────────────────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'member_id'      => 'required',
            'start_date'     => 'required|date',
            'end_date'       => 'required|date|after_or_equal:start_date',
            'running_hrs'    => 'required',
            'per_hrs_amount' => 'required|numeric|min:0',
            'per_day_amount' => 'required|numeric|min:0',
            'total_amount'   => 'required|numeric|min:0',
            'status'         => 'required|integer',
        ]);

        $addedById = null;
        if (session('type') == 'Admin') {
            $addedById = session('admin_id');
        } elseif (session('type') == 'Member') {
            $memberRecord = ManageReport::where('memberID', session('member_memberID'))->first();
            $addedById    = $memberRecord ? $memberRecord->member_id : null;
        }

        Memberstpschedule::create([
            'member_id'      => $request->member_id,
            'added_by_id'    => $addedById,
            'start_date'     => $request->start_date,
            'end_date'       => $request->end_date,
            'running_hrs'    => $request->running_hrs,
            'per_hrs_amount' => $request->per_hrs_amount,
            'per_day_amount' => $request->per_day_amount,
            'total_amount'   => $request->total_amount,
            'status'         => $request->status,
            'created_at'     => now(),
        ]);

        return redirect()->route('stpschedule.index')
                         ->with('success', 'Schedule created successfully.');
    }

    // ─── Update ─────────────────────────────────────────────────────────────────
    public function update(Request $request, $id)
    {
        $request->validate([
            'member_id'      => 'required',
            'start_date'     => 'required|date',
            'end_date'       => 'required|date|after_or_equal:start_date',
            'running_hrs'    => 'required',
            'per_hrs_amount' => 'required|numeric|min:0',
            'per_day_amount' => 'required|numeric|min:0',
            'total_amount'   => 'required|numeric|min:0',
            'status'         => 'required|integer',
        ]);

        $schedule = Memberstpschedule::findOrFail($id);
        $schedule->update([
            'member_id'      => $request->member_id,
            'start_date'     => $request->start_date,
            'end_date'       => $request->end_date,
            'running_hrs'    => $request->running_hrs,
            'per_hrs_amount' => $request->per_hrs_amount,
            'per_day_amount' => $request->per_day_amount,
            'total_amount'   => $request->total_amount,
            'status'         => $request->status,
        ]);

        return redirect()->route('stpschedule.index')
                         ->with('success', 'Schedule updated successfully.');
    }

    // ─── Delete ─────────────────────────────────────────────────────────────────
    public function delete($id)
    {
        Memberstpschedule::findOrFail($id)->delete();
        return redirect()->route('stpschedule.index')
                         ->with('success', 'Schedule deleted successfully.');
    }

    // ─── Toggle Status ───────────────────────────────────────────────────────────
    public function toggleStatus(Request $request, $id)
    {
        $schedule         = Memberstpschedule::findOrFail($id);
        $schedule->status = $schedule->status == 1 ? 0 : 1;
        $schedule->save();

        return redirect()->route('stpschedule.index')
                         ->with('success', 'Status updated successfully.');
    }

    // ─── Bulk Delete ─────────────────────────────────────────────────────────────
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array',
            'ids.*' => 'integer|exists:member_stp_schedules,id',
        ]);

        Memberstpschedule::whereIn('id', $request->ids)->delete();

        return redirect()->route('stpschedule.index')
                         ->with('success', 'Selected schedules deleted successfully.');
    }
}
