<?php

namespace App\Http\Controllers;

use App\models\Business;
use App\models\BusinessPartner;
use App\models\Businessmen;
use App\models\Designation;
use App\models\Groups;
use App\models\Hirarcy;
use App\models\Invoice;
use App\models\MembershipExpiry;
use Chumper\Zipper\Facades\Zipper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BusinessmenController extends Controller
{


    public function index()
    {       
            $load = isset($_REQUEST['ini']);
            $per_page = isset($_REQUEST['per_page']);
            $category = isset($_REQUEST['category']);
            $q = isset($_REQUEST['q']);

            if ($load) {
              return $this->loadInitialData($_REQUEST['per_page']); 
            }elseif ($q) {
              return $this->loadSearchData($per_page, $_REQUEST['category'], $_REQUEST['q']); 
            }else{
              
            }

    }

    /*
        Helper Functions For Search
  
    */

        private function loadInitialData($per_page)
        {
            return $businessmen = Businessmen::with('renewal')
                               ->orderBy('id', 'DESC')
                               ->paginate(($per_page) ? $_REQUEST['per_page'] : 20);
        }

        private function loadSearchData($per_page, $category, $q)
        {

          if ($category != 'all') {
            return $businessmen = Businessmen::with('renewal')
                               ->where($category , 'like', $q.'%')
                               ->orderBy('id', 'DESC')
                               ->paginate(($per_page) ? $_REQUEST['per_page'] : 20);
          }else{
            return $businessmen = Businessmen::with('renewal')
                               ->where('bmr_number', 'like', $q.'%')
                               ->orWhere('cnic', 'like', $q.'%')
                               ->orWhere('membership', 'like', $q.'%')
                               ->orWhere('overseas_cnic', 'like', $q.'%')
                               ->orWhere('overseas_country', 'like', $q.'%')
                               ->orWhere('nationality', 'like', $q.'%')
                               ->orWhere('email', 'like', $q.'%')
                               ->orWhere('mobile_number', 'like', $q.'%')
                               ->orWhere('mobile_number2', 'like', $q.'%')
                               ->orWhere('phone_number', 'like', $q.'%')
                               ->orWhere('ntn', 'like', $q.'%')
                               ->orWhere('executive_class', 'like', $q.'%')
                               ->orWhere('province', 'like', $q.'%')
                               ->orWhere('name', 'like', $q.'%')
                               ->orWhere('city', 'like', $q.'%')
                               ->orWhere('passport_number', 'like', $q.'%')
                               ->orderBy('id', 'DESC')
                               ->paginate(($per_page) ? $_REQUEST['per_page'] : 20);            
          }

        }

    /*
        Add Additional Images

    */

    public function addHirarcy(Request $request, $id)
    {
        if ($request->hasFile('file')) {

              $file = $request->file('file');

              $path = Storage::putFile('public' , $file);

              $fileName = Storage::url($path);


              Hirarcy::create([

                      'businessmen_id' => $id,
                      'file' => $fileName

                ]);


        }
        
    }

    public function fetchHirarcy($id)
    {
        $data = Hirarcy::where('businessmen_id', $id)->get();
        $files = $data->pluck('file');
        return $files->all();
        
    }



    private function generateRegistration($value=null)
    {
        $prevID = '';
        $newYear = '';

        if (isset($value->bmr_number)) {
            $prevID = explode('-', $value->bmr_number);

            $oldyear = $prevID[2];

            if ($oldyear == date('Y')) {
                $newYear = $oldyear;
            }else{
                $newYear = date('Y');
            }

            $number = $prevID[1];
            $newnumber = $number + 1;


              return 'BM-'.str_pad($newnumber, strlen($number), '0', STR_PAD_LEFT) .'-'. $newYear;


        }else{
            return 'BM-001-'.date('Y');
        }
    }

	public function add(Request $request)
	{
        $credentials = $request->input('params');

        $rules = [

            'membership'                    => 'required',
            'ntn'                           => 'required|min:9|max:9',
            'name'                          => 'required|min:3',
            'gender'                        => 'required',
            'father_name'                   => 'sometimes',
            'phone_number'                  => 'sometimes|min:11|max:14',
            'mobile_number'                 => 'required|min:11|max:11',
            'mobile_number2'                => 'sometimes|min:11|max:11',
            'emergency_number'              => 'required|min:11|max:11',
            'relation'                      => 'required',
            'email'                         => 'required|email|unique:businessmen',
            'address'                       => 'required',
            'postal'                        => 'required',
            'nationality'                   => 'required',
            'cnic'                          => 'sometimes|unique:businessmen',
            'overseas_cnic'                 => 'sometimes|unique:businessmen',
            'overseas_country'              => 'sometimes',
            'dob'                           => 'required',
            'province'                      => 'required',
            'city'                          => 'required',
            'passport_number'               => 'required|unique:businessmen',
            'issue_date'                    => 'required',
            'expiry_date'                   => 'required',
            'since'                         => 'sometimes|min:4|max:4',
            'image'                         => 'required',

        ];

        $validator = Validator::make($credentials, $rules);

        if(!$validator->fails()) {

          $bmLast = DB::table('businessmen')->orderBy('id', 'desc')->first();
          $BMR = $this->generateRegistration($bmLast);


            $cnic = (isset($credentials['cnic'])) ? $credentials['cnic'] : '';
            $since = (isset($credentials['since'])) ? $credentials['since'] : '';
            $overseas = (isset($credentials['overseas_cnic'])) ? $credentials['overseas_cnic'] : '';
            $executive = (isset($credentials['executiveclass'])) ? $credentials['executiveclass'] : '';
            $overseasCountry = (isset($credentials['overseas_country'])) ? $credentials['overseas_country'] : '';
            $phone = (isset($credentials['phone'])) ? $credentials['phone'] : '';
            $mobile2 = (isset($credentials['mobile_number2'])) ? $credentials['mobile_number2'] : '';

            $saved = Businessmen::create([

            'membership'                    => $credentials['membership'],
            'executive_class'               => $executive,
            'ntn'                           => $credentials['ntn'],
            'name'                          => $credentials['name'],
            'gender'                        => $credentials['gender'],
            'father_name'                   => $credentials['father_name'],
            'phone_number'                  => $phone,
            'mobile_number'                 => $credentials['mobile_number'],
            'mobile_number2'                => $mobile2,
            'emergency_number'              => $credentials['emergency_number'],
            'relation'                      => $credentials['relation'],
            'email'                         => $credentials['email'],
            'address'                       => $credentials['address'],
            'postal'                        => $credentials['postal'],
            'nationality'                   => $credentials['nationality'],
            'cnic'                          => $cnic,
            'overseas_cnic'                 => $overseas,
            'overseas_country'              => $overseasCountry,
            'dob'                           => $credentials['dob'],
            'province'                      => $credentials['province'],
            'city'                          => $credentials['city'],
            'passport_number'               => $credentials['passport_number'],
            'issue_date'                    => $credentials['issue_date'],
            'expiry_date'                   => $credentials['expiry_date'],
            'since'                         => $since,
            'image'                         => $credentials['image'],
            'date'                          => date('Y-m-d'),
            'bmr_number'                    => $BMR,
            'status'                        => 0,
            ]);

            if ($saved) {
                return 1;
            }

        }else{
          return $validator->messages();
        }
	}

  public function edit(Request $request, $id)
  {
        $credentials = $request->input('params');

        $rules = [

            'membership'                    => 'required',
            'executive_class'               => 'required_if:membership,Executive',
            'ntn'                           => 'required|min:9|max:9|unique:businessmen,ntn,'.$id,
            'name'                          => 'required|min:3',
            'gender'                        => 'required',
            'father_name'                   => 'sometimes',
            'phone_number'                  => 'sometimes|min:11|max:14',
            'mobile_number'                 => 'required|min:11|max:11',
            'mobile_number2'                => 'sometimes|min:11|max:11',
            'emergency_number'              => 'required|min:11|max:11',
            'relation'                      => 'required',
            'email'                         => 'required|email|unique:businessmen,email,'.$id,
            'address'                       => 'required',
            'postal'                        => 'required',
            'nationality'                   => 'required',
            'cnic'                          => 'required_if:nationality,Single|unique:businessmen,cnic,'.$id,
            'overseas_cnic'                 => 'required_if:nationality,Dual|unique:businessmen,overseas_cnic,'.$id,
            'overseas_country'              => 'required_if:nationality,Dual',
            'dob'                           => 'required',
            'province'                      => 'required',
            'city'                          => 'required',
            'passport_number'               => 'required|unique:businessmen,passport_number,'.$id,
            'issue_date'                    => 'required',
            'expiry_date'                   => 'required',
            'since'                         => 'sometimes|min:0|max:4',

        ];

        $validator = Validator::make($credentials, $rules);

        if(!$validator->fails()) {


              $cnic = (isset($credentials['cnic'])) ? $credentials['cnic'] : '';
              $since = (isset($credentials['since'])) ? $credentials['since'] : '';
              $overseas = (isset($credentials['overseas_cnic'])) ? $credentials['overseas_cnic'] : '';
              $executive = (isset($credentials['executiveclass'])) ? $credentials['executiveclass'] : '';
              $overseasCountry = (isset($credentials['overseas_country'])) ? $credentials['overseas_country'] : '';
              $mobile2 = (isset($credentials['mobile_number2'])) ? $credentials['mobile_number2'] : '';

              $dbData = Businessmen::find($id);

              $dbData->membership         = $credentials['membership'];
              $dbData->executive_class    = $executive;
              $dbData->ntn                = $credentials['ntn'];
              $dbData->name               = $credentials['name'];
              $dbData->gender             = $credentials['gender'];
              $dbData->father_name        = $credentials['father_name'];
              $dbData->phone_number       = $credentials['phone_number'];
              $dbData->mobile_number      = $credentials['mobile_number'];
              $dbData->mobile_number2     = $mobile2;
              $dbData->emergency_number   = $credentials['emergency_number'];
              $dbData->relation           = $credentials['relation'];
              $dbData->email              = $credentials['email'];
              $dbData->address            = $credentials['address'];
              $dbData->postal             = $credentials['postal'];
              $dbData->nationality        = $credentials['nationality'];
              $dbData->cnic               = $cnic;
              $dbData->overseas_cnic      = $overseas;
              $dbData->overseas_country   = $overseasCountry;
              $dbData->dob                = $credentials['dob'];
              $dbData->province           = $credentials['province'];
              $dbData->city               = $credentials['city'];
              $dbData->passport_number    = $credentials['passport_number'];
              $dbData->issue_date         = $credentials['issue_date'];
              $dbData->expiry_date        = $credentials['expiry_date'];
              $dbData->since              = $since;
              $dbData->image              = (isset($credentials['file'])) ? $credentials['file'] : $dbData->image;

              $dbData->save();

        }else{
         return response()->json($validator->messages(), 400);
        }


  }

	public function checkUnique($value=null, $field=null)
    {
        $unique = Businessmen::where($field, $value)->first();

        if ($unique) {
            echo 1;
        }else{
            echo 0;
        }
    }
  public function checkUniqueMobile($value=null)
    {
        $unique1 = Businessmen::where('mobile_number', $value)->first();
        $unique2 = Businessmen::where('mobile_number2', $value)->first();

        if ($unique1 == true || $unique2 == true) {
            echo 1;
        }else{
            echo 0;
        }
    }
  public function customData()
  {
      $collection = Businessmen::where('status', 1)->select('id', 'bmr_number', 'name', 'image')->get();
      
      $res = [];
      foreach ($collection  as $key) {
        array_push($res, ['id' => $key->id, 'bmr_number' => $key->bmr_number]);
      }
      return $res;

  }
  public function checkCnic($value=null)
  {
        $unique1 = Businessmen::where('cnic', $value)->first();
        $unique2 = Businessmen::where('overseas_cnic', $value)->first();

        if ($unique1 == true || $unique2 == true) {
            echo 1;
        }else{
            echo 0;
        }
    }

  public function countHirarcy($id)
  {
      return count(Hirarcy::where('businessmen_id', $id)->get());
  }
  public function downloadDocs($id)
  {
      $docs = Hirarcy::where('businessmen_id', $id)->get();
      $member = Businessmen::find($id);

        $files = [];

        foreach ($docs as $k) {
            array_push($files, glob(public_path($k->file)));
        }

        Zipper::make(public_path('downloads/' . $member->bmr_number . '.zip'))->add($files)->close();

        return response()->download(public_path('downloads/' . $member->bmr_number . '.zip'))->deleteFileAfterSend(true);

  }

  public function status($id)
  {
      $businessmen = Businessmen::findOrFail($id);

      if ($businessmen->status) {
          $businessmen->status = 0;
      }else{
          $businessmen->status = 1;
      }

      $businessmen->save();


  }

  public function fetchRelatedBusiness($id=null) //Businessmen ID
  {
      $i = BusinessPartner::where('businessmen_id', $id)->get();
      $ids = [];

      if (count($i) != 0) {

      foreach ($i as $k) {
          array_push($ids, $k->business_id);
      }

      $business = Business::with(['types', 'groups'])->whereIn('id', $ids)->get();

      $modifiedData = $this->bindDesignation($business, $id);


        return $modifiedData;
      }else{
        return $ids;
      }
  }

    public function bindDesignation($data, $id)
    {


        foreach ($data as $k => $v) {

            $designation = Designation::where(['businessmen_id' => $id, 'business_id' => $v->id])->get();
                if (isset($designation[0]['designation'])) {
                $data[$k]->designation = $designation[0]['designation'];
            }else{
                $data[$k]->designation = null;
            }
        }

            return $data;

    }



  public function asyncSearch()
  {

    sleep(1);
      $q = $_REQUEST['q'];

      if ($q) {
            $data = Businessmen::where('name', 'like', $q . '%')->select('id', 'bmr_number', 'name', 'image')
                                                   ->get();
            return $data;
      }
  }

  public function asyncSearchAdd()
  {

    sleep(1);
      $q = $_REQUEST['q'];

      if ($q) {
            $data = Businessmen::where('name', 'like', $q . '%')->select('id', 'bmr_number', 'name', 'image')
                                                   ->get();
            return $data;
      }
  }

  /*
      Business Groups
  */

  public function fetchGroups()
  {
      return Groups::orderBy('title', 'ASC')->get();
  }

  public function historyByBusinessmen($id)
  {
      $i = [];
      $invoicesId = MembershipExpiry::where('businessmen_id', $id)->get();
      
      foreach ($invoicesId as $k) {
          array_push($i, $k->invoice_id);
      }

      $invoices = Invoice::find($i);
      return $invoices;


  }

  public function printCertificate(Request $request, Businessmen $id, $certificate)
  {
      return $request;
  }



}
