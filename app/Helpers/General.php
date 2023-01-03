<?php
// composer dump-autoload

use App\Models\Language;
use App\Models\MainCategory;
use Illuminate\Support\Facades\Config;

function get_languages() {
    return Language::selection()->active()->get();
}

function get_default_lang() {
    return Config::get('app.locale');;
}

function uploadImage($folder, $image)
{
    $image->store('/', $folder);
    $filename = $image->hashName();
    $path = 'images/' . $folder . '/' . $filename;
    return $path;
}

function get_categories() {
    return MainCategory::where('translation_lang', get_default_lang())->get();
}
