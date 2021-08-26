<?php

namespace App\Http\Controllers\Extra;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ModalController extends Controller
{
    private $data;

    public function __construct()
    {
        $this->data = array();
    }

    public function getModal(Request $request)
    {
        $modal_name = $request->route()->uri();
        //dd($modal_name);
        return view('extra.modal.'.$modal_name, $this->data);
    }
}
