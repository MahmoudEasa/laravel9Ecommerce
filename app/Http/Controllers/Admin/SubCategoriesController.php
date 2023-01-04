<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SubCategoriesController extends Controller
{
    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }
    public function edit($id)
    {
        //
    }
    public function update(Request $request, $id)
    {
        //
    }
    public function delete($id)
    {
        //
    }

    public function status($id)
    {
        //
    }

    protected function handleData($request, $photo, $translation_of = 0)
    {
        $active = 0;
        if(isset($request['active']) || isset($request->category[0]['active'])){
            $active = 1;
        }

        $data = [
            'photo' => $photo,
            'translation_of' => $translation_of,
            'translation_lang' => $request['abbr'] ?? $request->category[0]['abbr'],
            'name' => $request['name'] ?? $request->category[0]['name'],
            'slug' => $request['name'] ?? $request->category[0]['name'],
            'active' => $active,
        ];

        return $data;
    }

    protected function deleteAndStatus($id, $req) {
        //
    }

    protected function savePhoto($request, $folder){
        $filePath = 'images/maincategories/default.png';
        if($request->has('photo')) {
            $filePath = uploadImage($folder, $request->photo);
        }

        return $filePath;
    }
}