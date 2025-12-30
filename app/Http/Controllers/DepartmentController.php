<?php

namespace App\Http\Controllers;

use App\Http\Requests\DepartmentRequest;
use App\Models\Department;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $perPage = $request->integer('per_page', 20);
        $search = $request->string('search')->trim()->toString();

        $perPage = in_array($perPage, [5, 10, 20, 50, 100]) ? $perPage : 20;

        $departments = Department::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where('name', 'ilike', "%{$search}%");
            })
            ->paginate($perPage)
            ->withQueryString();

        return Inertia::render('Department/Index', [
            'departments' => $departments,
            'filters' => [
                'per_page' => $perPage,
                'search' => $search,
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DepartmentRequest $request): RedirectResponse
    {
        try {
            $data = $request->validated();
            Department::create($data);

            return Redirect::route('departmentsIndex');
        } catch (\Throwable $th) {
            return Redirect::back()->withErrors(['error' => 'Ошибка при добавлении справочника. Попробуйте еще раз.']);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DepartmentRequest $request, Department $department): RedirectResponse
    {
        try {
            $data = $request->validated();
            $department->update($data);
            return Redirect::route('departmentsIndex');
        } catch (\Throwable $th) {
            return Redirect::back()->withErrors(['error' => 'Ошибка при изменении справочника. Попробуйте еще раз.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department): RedirectResponse
    {
        if ($department->visitors()->exists()) {
            return Redirect::back()->withErrors(['error' => 'Ошибка при удалении справочника. Попробуйте еще раз.']);
        }

        $department->update([
            'deleted_by' => auth()->id(),
        ]);
        $department->delete();

        return Redirect::route('departmentsIndex');
    }
}
