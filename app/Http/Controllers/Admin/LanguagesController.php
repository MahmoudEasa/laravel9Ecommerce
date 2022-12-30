<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use Illuminate\Http\Request;

class LanguagesController extends Controller
{
    public function index()
    {
        $languages = Language::select()->paginate(PAGINATION_COUNT);
        return view('admin.languages.index', [
            'languages' => $languages,
        ]);
    }

    public function create()
    {
        return view('admin.languages.create');
    }

    public function store()
    {
        //
    }
    public function edit($id)
    {
        $language = Language::find($id);

        if($language){
            return view('admin.languages.edit', [
                'language' => $language,
            ]);
        }else {
            return redirect()->back();
        }
    }
    public function update($id)
    {
        //
    }
    public function delete($id)
    {
        $language = Language::find($id);

        if($language){
            $language->delete();
        }else {
            return redirect()->back();
        }
    }
}