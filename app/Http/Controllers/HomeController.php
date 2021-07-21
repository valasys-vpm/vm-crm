<?php

namespace App\Http\Controllers;

use App\Country;
use App\Jobs\SendMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class HomeController extends Controller
{

    private $data;

    public function __construct()
    {
        //$this->middleware('auth');
        $this->data = array();
    }

    public function index()
    {
        return view('home');
    }

    public function accessDenied()
    {
        return view('pages.access_denied');
    }

    public function insertcountries()
    {
        echo 'This is test method';die;
        $path = asset('public/world.json');
        $countries = file_get_contents($path);
        $countryList = json_decode($countries, true);
        $data = $countryList['Sheet1'];
        $region = DB::table('regions')->get();
        $countryData = array();
        foreach ($data as $i => $country) {
            //dd($country);
            $region_id = $region->where('abbreviation', $country['Region'])->first()->id;
            $countryData[$i]['region_id'] = $region_id;
            $countryData[$i]['name'] = $country['Country'];
        }
        Country::insert($countryData);
        echo 'success';die;
        dd($countryData);
    }

    public function test(){
        $this->data = [
            'email' => 'sagar@valasys.com',
            'subject' => 'Test Email',
            'content' => 'Hi, Welcome User'
        ];
        dispatch(new SendMail($this->data));
    }

    public function testEmail(){
        $details = array('email' => 'sagar@valasys.com');
        Mail::send('email', $details, function ($email) use ($details){
            $email->to('sagar@valasys.com')->subject('Test Email-'.time());
        });
    }
}
