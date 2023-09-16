<?php

namespace App\Http\Controllers\Staff;

use App\Models\Country;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $data = Country::all();
        return view('staff.country.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('staff.country.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'title' => 'required',
        ]);
        //Data save to Database 
        $data = new Country();
        $data->title = $request->title;

        // PHOTO for country
        if ($request->hasFile('photo')) {
            $data->photo = $request->file('photo')->store('countryPhoto', 'public');
        }

        $data->save();
        //Data Saved
        return redirect()->route('staff.country.index')->with('success', 'Country Added Successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $data = Country::find($id);
        return view('staff.country.show', ['data' => $data]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $data = Country::find($id);
        return view('staff.country.edit', ['data' => $data]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $data = Country::find($id);
        $request->validate([
            'title' => 'required',
        ]);
        //Data save to Database 
        $data->title = $request->title;

        // PHOTO for country
        if ($request->hasFile('photo')) {
            $data->photo = $request->file('photo')->store('countryPhoto', 'public');
        } else {
            $formFields['photo'] = $request->prev_photo;
        }
        $data->save();
        //Data Saved
        return redirect()->route('staff.country.index')->with('success', 'Country Updated Successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $data = Country::find($id);
        $data->delete();
        return redirect()->route('staff.country.index')->with('danger', 'Country has been deleted Successfully!');
    }
}
