<?php

namespace App\Logic;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileLogic
{
    private $prefix = 'storage/';
    private $public = 'public';
    private $imagePrefix = '/upload/image';

    public function storeUserImage(int $userId, Request $request, $name)
    {
        $user = User::find($userId);
        $new_image_path = Storage::disk($this->public)
            ->put($this->imagePrefix, $request->file($name), $this->public);

        if ($user->image_path != null) {
            $old_image_path = str_replace($this->prefix, "", $user->image_path);
            Storage::disk($this->public)->delete($old_image_path);
        }
        $user->image_path = $this->prefix . $new_image_path;
        $user->save();
    }
}
