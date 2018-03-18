<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function image(Request $request)
    {
        $path = $request->file('file')->store('image');
        $this->rs['data']['src'] = asset('storage/'.$path);
        $this->rs['data']['name'] = explode('/',$path)[1];
        return $this->json($this->rs);
    }

    public function file(Request $request)
    {
        $file = $request->file('file');
        $origin_name = $file->getClientOriginalName();
        $path = $file->storeAs('file',time().'-'.$origin_name);
        $this->rs['data']['src'] = asset('storage/'.$path);
        $this->rs['data']['name'] = explode('/',$path)[1];
        return $this->json($this->rs);
    }
}
