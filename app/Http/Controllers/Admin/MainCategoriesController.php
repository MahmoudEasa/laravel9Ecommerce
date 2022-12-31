<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MainCategoryRequest;
use App\Models\MainCategory;
use Illuminate\Http\Request;

class MainCategoriesController extends Controller
{
    public function index()
    {
        $default_lang = get_default_lang();
        $categories = MainCategory::where('translation_lang', $default_lang)->selection()->get();
        return view('admin.maincategories.index', [
            'categories' => $categories,
        ]);
    }

    public function create()
    {
        return view('admin.maincategories.create');
    }

    public function store(MainCategoryRequest $request)
    {
        $main_categories = collect($request->category);
        $default_category = $main_categories->filter(function ($value, $key) {
            return strtolower($value['abbr']) == strtolower(get_default_lang());
        });

        $filePath = $this->savePhoto($request, 'maincategories');
        $data = $this->handleData($default_category[0], $filePath);
        $default_category_id = 0;

        try{
            $default_category_id = MainCategory::insertGetId($data);
            $categories = $main_categories->filter(function ($value, $key) {
                return strtolower($value['abbr']) != strtolower(get_default_lang());
            });

            if(isset($categories) && $categories->count() > 0){
                $categories_arr = [];
                foreach(array_values($categories->all()) as $category){
                    $data = $this->handleData($category, $filePath, $default_category_id);
                    array_push($categories_arr, $data);
                }
                MainCategory::insert($categories_arr);
            }


            return redirect()->route('admin.maincategories.create')->with(['success'=>'تم إضافة القسم بنجاح']);
        }catch(\Exception $ex){
            return redirect()->route('admin.maincategories.create')->with(['error'=>'هناك خطأ ما يرجى المحاولة مجدداً']);
        }
    }
    public function edit($id)
    {
        $language = MainCategory::selection()->find($id);

        if($language){
            return view('admin.maincategories.edit', [
                'language' => $language,
            ]);
        }else {
            return redirect()->back()->with(['error'=>'هذا العنصر غير موجود']);
        }
    }
    public function update(MainCategoryRequest $request, $id)
    {
        // $data = $this->handleData($request);
        // $status = $this->deleteAndUpdate($id, "update", $data);

        // if($status) {
        //     return redirect()->route('admin.maincategories')->with(['success'=>'تم التحديث بنجاح']);
        // }else {
        //     return redirect()->back()->with(['error'=>'هذا العنصر غير موجود']);
        // }
    }
    public function delete($id)
    {
        $status = $this->deleteAndUpdate($id, "delete");
        if($status) {
            return redirect()->back()->with(['success'=>'تم الحذف بنجاح']);
        }else {
            return redirect()->back()->with(['error'=>'هذا العنصر غير موجود']);
        }
    }

    protected function deleteAndUpdate($id, $req, $data = false){
        $language = MainCategory::find($id);

        if($language){
            try{
                if($req == 'delete') {
                    $language->delete();
                }else {
                    $language->update($data);
                }
                return 1;
            }catch(\Exception $ex){
                return redirect()->back()->with(['error'=>'هناك خطأ ما يرجى المحاولة مجدداً']);
            }
        }else {
            return 0;
        }
    }

    protected function handleData($request, $photo, $translation_of = 0)
    {
        if(!isset($request['active'])){
            $request['active'] = 0;
        }

        $data = [
            'photo' => $photo,
            'translation_of' => $translation_of,
            'translation_lang' => $request['abbr'],
            'name' => $request['name'],
            'slug' => $request['name'],
            'active' => $request['active'],
        ];

        return $data;
    }

    protected function savePhoto($request, $folder){
        $filePath = '';
        if($request->has('photo')) {
            $filePath = uploadImage($folder, $request->photo);
        }

        return $filePath;
    }
}
