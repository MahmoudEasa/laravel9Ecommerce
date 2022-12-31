<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\LanguageRequest;
use App\Models\Language;
use Illuminate\Http\Request;

class LanguagesController extends Controller
{
    public function index()
    {
        $languages = Language::selection()->paginate(PAGINATION_COUNT);
        return view('admin.languages.index', [
            'languages' => $languages,
        ]);
    }

    public function create()
    {
        return view('admin.languages.create');
    }

    public function store(LanguageRequest $request)
    {
        try{
            $data = $this->handleData($request);
            Language::create($data);
            return redirect()->route('admin.languages.create')->with(['success'=>'تم إضافة اللغة بنجاح']);
        }catch(\Exception $ex){
            return redirect()->route('admin.languages.create')->with(['error'=>'هناك خطأ ما يرجى المحاولة مجدداً']);
        }
    }
    public function edit($id)
    {
        $language = Language::selection()->find($id);

        if($language){
            return view('admin.languages.edit', [
                'language' => $language,
            ]);
        }else {
            return redirect()->back()->with(['error'=>'هذا العنصر غير موجود']);
        }
    }
    public function update(LanguageRequest $request, $id)
    {
        $data = $this->handleData($request);
        $status = $this->deleteAndUpdate($id, "update", $data);

        if($status) {
            return redirect()->route('admin.languages')->with(['success'=>'تم التحديث بنجاح']);
        }else {
            return redirect()->back()->with(['error'=>'هذا العنصر غير موجود']);
        }
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
        $language = Language::find($id);

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

    protected function handleData($request)
    {
        if(!$request->active){
            $request->request->add(['active'=>0]);
        }

        return $request->all();
    }
}
