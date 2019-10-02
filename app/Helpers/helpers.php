<?php
use App\Helpers\Settings\Setting;

function set_active($path, $active = 'active'){
    return call_user_func_array('Request::is', (array)$path) ? $active : '';
}

function is_url($path){
    return call_user_func_array('Request::is', (array)$path);
}

function clean_slug($string){
    $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
    return \Illuminate\Support\Str::lower(preg_replace('/[^A-Za-z0-9\-]/', '', $string));
}

function get_setting($key){
    return Setting::getSetting($key);
}

function tree($parents, $children, $parentField = 'parent', $childrenField){
    $dataArr = [];
    $i = 0;
    foreach ($parents as $item){
      $dataArr[$i] = $item->toArray();
      $find = $children->where($parentField, $item->{$childrenField});
      $dataArr[$i]['children'] = [];
      if ($find->count()) {
        $dataArr[$i]['children'] = tree($find, $children);
      }
      $i++;
    }
    return $dataArr;
}