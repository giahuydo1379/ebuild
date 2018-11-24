<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class UploaderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function upload(Request $request)
    {
        /**
         * Path to the 'public' folder
         */
        $path = public_path().'/uploads/tmp/';

        // tao thu muc
        if (! is_dir ( $path )) {
            mkdir ( $path, 0777, true );
            if( chmod($path, 0777) ) {
                // more code
                chmod($path, 0755);
            }
        }

        $config = array('upload_dir' => $path);

        // Make new upload handler instance
        $upload = new \App\Helpers\UploadHandler($config);

        exit(1);
    }
}
