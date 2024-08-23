<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExcelImportController extends Controller
{
    public function import_user()
    {
        $path = storage_path('app/public/users.xlsx'); // Path to your Excel file

        Excel::load($path, function($reader) {
            $results = $reader->get();

            $results->each(function($row) {
                User::create([
                    'name' => $row->name, // Assuming 'name' is the header title in Excel
                    'email' => $row->email,
                    'password' => bcrypt($row->password),
                ]);
            });
        });


        return redirect()->back()->with('success', 'Users imported successfully.');
    }
}
