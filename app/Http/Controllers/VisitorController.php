<?php

namespace App\Http\Controllers;

use App\Http\Requests\VisitorRequest;
use App\Models\Department;
use App\Models\Visitor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class VisitorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $perPage = $request->integer('per_page', 20);
        $search = $request->string('search')->trim()->toString();

        $perPage = in_array($perPage, [5, 10, 20, 50, 100]) ? $perPage : 20;

        $visitors = Visitor::with(['department', 'document'])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('full_name', 'ilike', "%{$search}%")
                        ->orWhere('phone', 'ilike', "%{$search}%")
                        ->orWhere('position', 'ilike', "%{$search}%")
                        ->orWhereHas('department', fn($d) => $d->where('name', 'ilike', "%{$search}%")
                        );
                });
            })
            ->latest('entry_datetime')
            ->paginate($perPage)
            ->withQueryString();

        return Inertia::render('Visitor/Index', [
            'visitors' => $visitors,
            'filters' => [
                'per_page' => $perPage,
                'search' => $search,
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Visitor/Create', [
            'departments' => Department::select('id', 'name')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(VisitorRequest $request)
    {
        DB::transaction(function () use ($request) {
            $data = $request->validated();

            $visitor = Visitor::create(
                $request->only([
                    'full_name',
                    'birth_date',
                    'position',
                    'phone',
                    'entry_datetime',
                    'exit_datetime',
                    'remarks',
                ]) + [
                    'department_id' => $data['department'],
                    'created_by' => auth()->id(),
                ]
            );

            $visitor->syncDocument(
                $this->buildDocumentData($data)
            );
        });

        return Redirect::route('visitorsIndex');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Visitor $visitor)
    {
        return Inertia::render('Visitor/Edit', [
            'visitor' => $visitor->load(['department', 'document']),
            'departments' => Department::all(['id', 'name']),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(VisitorRequest $request, Visitor $visitor)
    {
        DB::transaction(function () use ($request, $visitor) {
            $data = $request->validated();

            $visitor->update(
                $request->only([
                    'full_name',
                    'birth_date',
                    'position',
                    'phone',
                    'entry_datetime',
                    'exit_datetime',
                    'remarks',
                ]) + [
                    'department_id' => $data['department'],
                    'updated_by' => auth()->id(),
                ]
            );


            $visitor->syncDocument(
                $this->buildDocumentData($data)
            );
        });

        return Redirect::route('visitorsIndex');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Visitor $visitor)
    {
        DB::transaction(function () use ($visitor) {
            if ($visitor->document) {
                $visitor->document()->update([
                    'deleted_by' => auth()->id(),
                ]);
                $visitor->document->delete();
            }
            $visitor->update([
                'deleted_by' => auth()->id(),
            ]);
            $visitor->delete();
        });

        return Redirect::route('visitorsIndex');
    }

    private function buildDocumentData(array $data): array
    {
        return match ($data['document_type']) {
            'passport' => [
                'type' => 'passport',
                'passport_series' => $data['passport_series'] ?? null,
                'passport_number' => $data['passport_number'] ?? null,
                'passport_issue_date' => $data['passport_issue_date'] ?? null,
                'passport_issued_by' => $data['passport_issued_by'] ?? null,
                'passport_department_code' => $data['passport_department_code'] ?? null,
            ],
            'license' => [
                'type' => 'license',
                'license_series_number' => $data['license_series_number'] ?? null,
                'license_issue_date' => $data['license_issue_date'] ?? null,
                'license_region' => $data['license_region'] ?? null,
                'license_issued_by' => $data['license_issued_by'] ?? null,
            ],
            'other' => [
                'type' => 'other',
                'other_document_name' => $data['other_document_name'] ?? null,
                'other_series_number' => $data['other_series_number'] ?? null,
                'other_series_number_original' => $data['other_series_number'] ?? null,
                'other_issue_date' => $data['other_issue_date'] ?? null,
                'other_issued_by' => $data['other_issued_by'] ?? null,
            ],
        };
    }
}
