<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\VendorRequest;
use App\Models\MainCategory;
use App\Models\Vendor;
use App\Notifications\VendorCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class vendorsController extends Controller
{
    public function index()
    {
        $vendors = Vendor::with('category')->selection()->paginate(PAGINATION_COUNT);
        return view('admin.vendors.index', [
            'vendors' => $vendors,
        ]);
    }

    public function create()
    {
        $default_lang = get_default_lang();
        $categories = MainCategory::where('translation_lang', $default_lang)->active()->selection()->get();
        return view('admin.vendors.create', [
            'categories' => $categories,
        ]);
    }

    public function store(VendorRequest $request)
    {
        try{
            $filePath = $this->savePhoto($request, 'vendors');
            $data = $this->handleData($request, $filePath);
            $vendor = Vendor::create($data);

            // Notification::send($vendor, new VendorCreated($vendor));

            return redirect()->route('admin.vendors.create')->with(['success'=>'تم إضافة المتجر بنجاح']);
        }catch(\Exception $ex){
            // return $ex;
            return redirect()->route('admin.vendors.create')->with(['error'=>'هناك خطأ ما يرجى المحاولة مجدداً']);
        }
    }

    public function edit($id)
    {
        $vendor = Vendor::with('category')->selection()->find($id);
        $default_lang = get_default_lang();
        $categories = MainCategory::where('translation_lang', $default_lang)->active()->selection()->get();
        if($vendor){
            return view('admin.vendors.edit', [
                'vendor' => $vendor,
                'categories' => $categories,
            ]);
        }else {
            return redirect()->back()->with(['error'=>'هذا المتجر غير موجود']);
        }
    }
    public function update(VendorRequest $request, $id)
    {
        $vendor = Vendor::selection()->find($id);
        if($vendor){
            try{
                $filePath = "";
                $deletePhotoPath = explode('assets/', $vendor->logo)[1];
                $requestHasPhoto = false;

                if($request->logo){
                    $requestHasPhoto = true;
                    $filePath = $this->savePhoto($request, 'vendors');
                }

                $data = $this->handleData($request, $filePath);
                $vendor->update($data);

                if ($requestHasPhoto) {
                    if (file_exists('assets/' . $deletePhotoPath))
                        unlink('assets/' . $deletePhotoPath);
                }
                return redirect()->back()->with(['success'=>'تم تحديث المتجر بنجاح']);
            }catch(\Exception $ex){
                // return $ex;
                return redirect()->back()->with(['error'=>'هناك خطأ ما يرجى المحاولة مجدداً']);
            }
        }else {
            return redirect()->back()->with(['error'=>'هذا المتجر غير موجود']);
        }
    }
    public function delete($id)
    {
        $deleted = $this->deleteAndStatus($id, 'delete');
        if($deleted) {
            return redirect()->back()->with(['success'=>'تم الحذف بنجاح']);
        }else {
            return redirect()->back()->with(['error'=>'هذا المتجر غير موجود']);
        }
    }

    public function status($id)
    {
        $updated = $this->deleteAndStatus($id, 'status');
        if($updated) {
            return redirect()->back()->with(['success'=>'تم التحديث بنجاح']);
        }else {
            return redirect()->back()->with(['error'=>'هذا المتجر غير موجود']);
        }
    }

    protected function handleData($request, $photo)
    {
        $active = 0;
        if(isset($request->active)){
            $active = 1;
        }

        $data = [
            'name' => $request->name,
            'mobile' => $request->mobile,
            'address' => $request->address,
            'email' => $request->email,
            'category_id' => $request->category_id,
            'active' => $active,
        ];

        if($request->password){
            $data['password'] = $request->password;
        }

        if($photo){
            $data['logo'] = $photo;
        }

        return $data;
    }

    protected function deleteAndStatus($id, $req) {
        $vendor = Vendor::selection()->find($id);
        if($vendor) {
            $filePath = explode('assets/', $vendor->logo)[1];
            try{
                if($req == 'status') {
                    $vendor->update([
                        'active' => !$vendor->active,
                    ]);
                }else {
                    $vendor->delete();

                    if(file_exists('assets/'.$filePath))
                        unlink('assets/'.$filePath);
                }
            }catch(\Exception $ex){
                return redirect()->back()->with(['error'=>'هناك خطأ ما يرجى المحاولة مجدداً']);
            }
            return 1;
        }else {
            return 0;
        }
    }

    protected function savePhoto($request, $folder){
        $filePath = 'images/vendors/default.png';
        if($request->has('logo')) {
            $filePath = uploadImage($folder, $request->logo);
        }

        return $filePath;
    }
}