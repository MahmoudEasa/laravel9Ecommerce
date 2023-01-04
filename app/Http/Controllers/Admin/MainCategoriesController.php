<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MainCategoryRequest;
use App\Models\MainCategory;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
        try{
            $main_categories = collect($request->category);
            $default_category = $main_categories->filter(function ($value, $key) {
                return strtolower($value['abbr']) == strtolower(get_default_lang());
            });
            $filePath = $this->savePhoto($request, 'maincategories');
            $data = $this->handleData($default_category[0], $filePath);
            DB::beginTransaction();
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
            DB::commit();
            return redirect()->route('admin.maincategories.create')->with(['success'=>'تم إضافة القسم بنجاح']);
        }catch(\Exception $ex){
            DB::rollBack();
            return redirect()->route('admin.maincategories.create')->with(['error'=>'هناك خطأ ما يرجى المحاولة مجدداً']);
        }
    }
    public function edit($id)
    {
        $mainCategory = MainCategory::with('categories')->selection()->find($id);
        if($mainCategory){
            return view('admin.maincategories.edit', [
                'mainCategory' => $mainCategory,
            ]);
        }else {
            return redirect()->back()->with(['error'=>'هذا العنصر غير موجود']);
        }
    }
    public function update(MainCategoryRequest $request, $id)
    {
        $category = MainCategory::selection()->find($id);
        if($category){
            try{
                $filePath = explode('assets/', $category->photo)[1];
                $deletePhotoPath = $filePath;
                $requestHasPhoto = false;

                if($request->photo){
                    $requestHasPhoto = true;
                    $filePath = $this->savePhoto($request, 'maincategories');
                }

                $data = $this->handleData($request, $filePath, $category->translation_of);
                $category->update($data);

                if ($requestHasPhoto) {
                    if (file_exists('assets/' . $deletePhotoPath))
                        unlink('assets/' . $deletePhotoPath);
                }
                return redirect()->back()->with(['success'=>'تم تحديث القسم بنجاح']);
            }catch(\Exception $ex){
                return redirect()->back()->with(['error'=>'هناك خطأ ما يرجى المحاولة مجدداً']);
            }
        }else {
            return redirect()->back()->with(['error'=>'هذا العنصر غير موجود']);
        }
    }
    public function delete($id)
    {
        $deleted = $this->deleteAndStatus($id, 'delete');
        if($deleted == "Done") {
            return redirect()->back()->with(['success'=>'تم الحذف بنجاح']);
        }else if($deleted == "No Delete") {
            return redirect()->back()->with(['error'=>'يوجد تجار لا يمكن حذف هذا القسم']);
        }else if($deleted == "Not Found") {
            return redirect()->back()->with(['error'=>'هذا العنصر غير موجود']);
        }
    }

    public function status($id)
    {
        $updated = $this->deleteAndStatus($id, 'status');
        if($updated == "Done") {
            return redirect()->back()->with(['success'=>'تم التحديث بنجاح']);
        }else if($updated == "Not Found"){
            return redirect()->back()->with(['error'=>'هذا العنصر غير موجود']);
        }
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
        $category = MainCategory::selection()->find($id);
        $otherLangs = MainCategory::where('translation_of', $id)->selection()->get();

        if($category) {
            $vendors = $category->vendors()->get();
            try{
                if($req == 'status') {
                    $category->update([
                        'active' => !$category->active,
                    ]);
                }else {
                    //  Delete From Database
                    if(isset($vendors) && $vendors->count() > 0){
                        return 'No Delete';
                    }
                    $filePath = explode('assets/', $category->photo)[1];
                    $category->delete();

                    if(file_exists('assets/'.$filePath))
                        unlink('assets/'.$filePath);
                }
            }catch(\Exception $ex){
                DB::rollBack();
                return redirect()->back()->with(['error'=>'هناك خطأ ما يرجى المحاولة مجدداً']);
            }
            return "Done";
        }else {
            return "Not Found";
        }
    }

    protected function savePhoto($request, $folder){
        $filePath = 'images/maincategories/default.png';
        if($request->has('photo')) {
            $filePath = uploadImage($folder, $request->photo);
        }

        return $filePath;
    }
}