<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AssetRequest;
use App\Models\Department;
use App\Models\Asset;
use App\Exports\AssetRequestExport;
use App\Exports\AssignedAssetExport;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AssetRequestController extends Controller
{
    public function requests()
    {
        $departments = Department::where('status', 'active')->orderBy("name","asc")->get();
        return view('pages.assets.request', compact('departments'));
    }
    public function requestslist(Request $request)
    { //dd("awdw");
        $query = AssetRequest::with('department');

        if ($request->filled('department_id')) {

            $query->where(
                'department_id',
                $request->department_id
            );
        }

        if ($request->filled('request_status')) {

            $query->where(
                'request_status',
                $request->request_status
            );
        }
        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->status
            );
        }

        return datatables()
            ->of($query)

            ->addIndexColumn()

            ->addColumn('department', function ($row) {

                return $row->department->name ?? '-';
            })

            ->editColumn('joining_date', function ($row) {

                return Carbon::parse(
                    $row->joining_date
                )->format('d-m-Y');
            })

            ->editColumn('created_at', function ($row) {

                return Carbon::parse(
                    $row->created_at
                )->format('d-m-Y');
            })

            ->addColumn('request_status', function ($row) {

                if ($row->request_status == 'Done') {

                    return '<span class="badge bg-success">
                Completed
                </span>';
                }

                return '<span class="badge bg-warning text-dark">
                        On Progress
                    </span>';
            })

            ->addColumn('status', function ($row) {

                if ($row->status == 'active') {

                    return '<span class="badge bg-success">
                Active
                        </span>';
                }

                return '<span class="badge bg-danger">
                        Deleted
                    </span>';
            })

            ->addColumn('action', function ($row) {
                if ($row->status == 'active') {
                    return '
            <button
                    class="btn btn-danger btn-sm deleteAsset"
                    data-id="' . $row->id . '">
                    Delete
                </button>
            ';
                }
            })

            ->filterColumn('department', function ($query, $keyword) {

                $query->whereHas('department', function ($q) use ($keyword) {

                    $q->where(
                        'name',
                        'like',
                        "%{$keyword}%"
                    );
                });
            })
            ->filterColumn('request_status', function ($query, $keyword) {

                $query->where(
                    'request_status',
                    'like',
                    "%{$keyword}%"
                );
            })
            ->filterColumn('joining_date', function ($query, $keyword) {

                $query->whereRaw(
                    "DATE_FORMAT(joining_date, '%d-%m-%Y') LIKE ?",
                    ["%{$keyword}%"]
                );
            })
            ->filterColumn('created_at', function ($query, $keyword) {

                $query->whereRaw(
                    "DATE_FORMAT(created_at, '%d-%m-%Y') LIKE ?",
                    ["%{$keyword}%"]
                );
            })

            ->rawColumns([
                'request_status',
                'status',
                'action'
            ])

            ->make(true);
    }
    public function delete(Request $request)
    {
        $asset = AssetRequest::findOrFail($request->id);

        $asset->update([
            'status' => 'inactive'
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Asset request deleted successfully.'
        ]);
    }
    public function exportAssetRequests(Request $request)
    {
        $query = AssetRequest::with('department');

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->filled('request_status')) {
            $query->where('request_status', $request->request_status);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $data = $query->latest()->get();

        return Excel::download(
            new AssetRequestExport($data),
            'asset_requests.xlsx'
        );
    }
    public function store(Request $request)
    {
        $request->validate([
            'department_id' => 'required|exists:departments,id',
            'joining_date'  => 'required|date',
            'laptop_count'  => 'required|integer|min:1',
        ]);

        AssetRequest::create([

            'department_id'  => $request->department_id,

            'joining_date'   => $request->joining_date,

            'laptop_count'   => $request->laptop_count,

            'request_status' => 'Onprogress',

            'status'         => 'active',
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Asset request submitted successfully.'
        ]);
    }
    public function assigned()
    {
        $departments = Department::where('status', 'active')->orderBy("name","asc")->get();
        return view('pages.assets.assigned', compact('departments'));
    }
    public function assignedlist(Request $request)
    {
        $data = Asset::with('employee.department');
        if ($request->filled('department_id')) {
            $data->whereHas('employee', function ($q) use ($request) {
                $q->where('department_id', $request->department_id);
            });
        }

        return DataTables::of($data)

            ->addIndexColumn()

            ->addColumn('employee_code', function ($row) {

                return $row->employee->emp_id ?? '';
            })

            ->addColumn('employee_name', function ($row) {

                return $row->employee->name ?? '';
            })

            ->editColumn('created_at', function ($row) {

                return $row->created_at
                    ? Carbon::parse(
                        $row->created_at
                    )->format('d-m-Y')
                    : '';
            })

            ->addColumn('action', function ($row) {
                if ($row->status == 'active') {
                    return '
                    <button
                        class="btn btn-warning btn-sm editBtn"
                        data-id="' . $row->id . '">
                        Edit
                    </button>
                    <button
                        class="btn btn-danger btn-sm returnBtn"
                        data-id="' . $row->id . '">
                        Return
                    </button>
                    <button
                        class="btn btn-success btn-sm transferBtn"
                        data-id="' . $row->id . '">
                        Transfer
                    </button>
                ';
                }
            })
            ->filterColumn('created_at', function ($query, $keyword) {

                $query->whereRaw(
                    "DATE_FORMAT(created_at, '%d-%m-%Y') LIKE ?",
                    ["%{$keyword}%"]
                );
            })
            ->filterColumn('employee_name', function ($query, $keyword) {
                $query->whereHas('employee', function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%");
                });
            })

            // Employee ID Search
            ->filterColumn('employee_id', function ($query, $keyword) {
                $query->whereHas('employee', function ($q) use ($keyword) {
                    $q->where('emp_id', 'like', "%{$keyword}%");
                });
            })
            ->rawColumns(['action'])

            ->make(true);
    }
    public function assetDetails($id)
    {
        return Asset::with('employee')
            ->findOrFail($id);
    }
    public function assign(Request $request)
    {
        $request->validate([
            'employee_id'   => 'required',
            'laptop_brand'  => 'required',
            'asset_no'      => 'required'
        ]);

        $duplicate = Asset::where('status', 'active')
            ->when($request->id, function ($q) use ($request) {
                $q->where('id', '!=', $request->id);
            })
            ->where(function ($q) use ($request) {

                if ($request->asset_no) {
                    $q->orWhere('asset_no', $request->asset_no);
                }

                if ($request->serial_no) {
                    $q->orWhere('serial_no', $request->serial_no);
                }

                if ($request->mouse_code) {
                    $q->orWhere('mouse_code', $request->mouse_code);
                }
            })
            ->first();

        if ($duplicate) {

            return response()->json([
                'status' => false,
                'message' => 'Asset already active and assigned to another employee.'
            ]);
        }

        $data = [
            'employee_id'   => $request->employee_id,
            'laptop_brand'  => $request->laptop_brand,
            'asset_no'      => $request->asset_no,
            'vendor'        => $request->vendor,
            'mouse_code'    => $request->mouse_code,
            'serial_no'     => $request->serial_no,
            'ram'           => $request->ram,
            'sys_config'    => $request->sys_config,
            'os_version'    => $request->os_version,
            'status'        => 'active',
            'transfer_at'   => null,
        ];

        if ($request->id) {

            Asset::where('id', $request->id)
                ->update($data);

            $message = 'Asset updated successfully';
        } else {

            Asset::create($data);

            $message = 'Asset assigned successfully';
        }

        return response()->json([
            'status' => true,
            'message' => $message
        ]);
    }
    public function returnAsset(Request $request)
    {
        $asset = Asset::findOrFail($request->id);

        $asset->status = 'inactive';
        $asset->save();

        return response()->json([
            'status' => true,
            'message' => 'Asset returned successfully'
        ]);
    }

    public function export(Request $request)
    {
        $query = Asset::with('employee.department');

        if ($request->department_id) {

            $query->whereHas('employee', function ($q)
            use ($request) {

                $q->where(
                    'department_id',
                    $request->department_id
                );
            });
        }

        $assets = $query->get();

        return Excel::download(
            new AssignedAssetExport($assets),
            'assigned-assets.xlsx'
        );
    }

    public function transferAsset(Request $request)
    {
        DB::beginTransaction();

        try {

            $asset = Asset::findOrFail($request->asset_id);

            // Mark current assignment inactive
            $asset->status = 'inactive';
            $asset->transfer_at = Carbon::now();
            $asset->save();

            // Create new assignment
            $newAsset = $asset->replicate();

            $newAsset->employee_id = $request->employee_id;
            $newAsset->status = 'active';
            $newAsset->transfer_at = null;

            $newAsset->created_at = now();
            $newAsset->updated_at = now();

            $newAsset->save();

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Asset transferred successfully'
            ]);
        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
