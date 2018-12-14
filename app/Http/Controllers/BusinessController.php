<?php

namespace App\Http\Controllers;

use App\models\Business;
use App\models\BusinessHirarcy;
use App\models\BusinessPartner;
use App\models\Businessmen;
use App\models\Designation;
use App\models\EmailsFoundation;
use App\models\GroupHirarcy;
use App\models\Groups;
use App\models\Type;
use Chumper\Zipper\Facades\Zipper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BusinessController extends Controller
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

                if ($_REQUEST['category'] == 'group') {
                    return $this->loadSearchDataGroup($per_page, $_REQUEST['category'], $_REQUEST['q']);
                }else{
                    return $this->loadSearchData($per_page, $_REQUEST['category'], $_REQUEST['q']);   
                }

            }else{
              
            }

    }

    /*
        Helper Functions For Search
  
    */

        // public function bindDesignation($data)
        // {

        //         $designation = Designation::where(['businessmen_id' => $n->id, 'business_id' => $id])->get();
        //             if (isset($designation[0]['designation'])) {
        //             $data[$k]->designation = $designation[0]['designation'];
        //         }else{
        //             $data[$k]->designation = null;
        //         }

        //         return $data;

        // }

        private function loadInitialData($per_page)
        {
            return $businessmen = Business::with(['emails', 'groups', 'types'])->orderBy('id', 'DESC')
                               ->paginate(($per_page) ? $_REQUEST['per_page'] : 15);

        }

        private function loadSearchDataGroup($per_page, $group, $q){

            $group = Groups::where('code' , 'like', $q . '%')
                            ->orWhere('title' , 'like', $q . '%')
                            ->get();

            $ids = [];

            foreach ($group as $v) {
                array_push($ids, $v->title);
            }


            $groupHirarcy = GroupHirarcy::whereIn('groups' , $ids)->get();

            $bIds = [];
            foreach ($groupHirarcy as $v) {
                array_push($bIds, $v->business_id);
            }

            return $businessmen = Business::with(['emails', 'groups', 'types'])
                                ->whereIn('id', $bIds)
                                ->orderBy('id', 'DESC')
                                ->paginate(($per_page) ? $_REQUEST['per_page'] : 15);         

        }

        private function loadSearchData($per_page, $category, $q)
        {

          if ($category != 'all') {
            return $businessmen = Business::with(['emails', 'groups', 'types'])
                               ->where($category , 'like', $q . '%')
                               ->orderBy('id', 'DESC')
                               ->paginate(($per_page) ? $_REQUEST['per_page'] : 20);
          }else{
            return $businessmen = Business::with(['emails', 'groups', 'types'])
                               ->where('br_number', 'like', $q . '%')
                               ->orWhere('name', 'like', $q . '%')
                               ->orWhere('gst', 'like', $q . '%')
                               ->orWhere('aop', 'like', $q . '%')
                               ->orderBy('id', 'DESC')
                               ->paginate(($per_page) ? $_REQUEST['per_page'] : 20);            
          }

        }


	public function add(Request $request)
	{

        $credentials = $request->input('params');

        $rules = [

            'businessmen_id' => 'required',
            'name' 			 => 'required',
            'email' 		 => 'sometimes|email',
            'email2' 		 => 'sometimes|email',
            'address'        => 'required',
            'type' 	 	     => 'required',
            'office' 		 => 'required',
            'website' 		 => 'sometimes|url',
            'product'        => 'required',
            'designation' 	 => 'required|min:2',
            'group' 		 => 'required',
            'gst' 		 	 => 'sometimes|min:17|max:17|unique:business',
            'category' 		 => 'required',
            'criteria' 		 => 'sometimes|required',
            'aop' 		 	 => 'sometimes|required|unique:business',

        ];

        $validator = Validator::make($credentials, $rules);

        if(!$validator->fails()) {

			$bmLast = Business::orderBy('id', 'desc')->first();
			$BM = $this->generateRegistration($bmLast);

        	$website = (isset($credentials['website'])) ? $credentials['website'] : null;
        	$email = (isset($credentials['email'])) ? $credentials['email'] : null;
        	$email2 = (isset($credentials['email2'])) ? $credentials['email2'] : null;
        	$gst = (isset($credentials['gst'])) ? $credentials['gst'] : null;
        	$criteria = (isset($credentials['criteria'])) ? $credentials['criteria'] : null;
        	$aop = (isset($credentials['aop'])) ? $credentials['aop'] : null;

        	$business = Business::create([

        		'br_number' 		=> $BM,
        		'name' 				=> $credentials['name'],
        		'address' 			=> $credentials['address'],
        		'office' 			=> $credentials['office'],
        		'website' 			=> $website,
        		'product' 			=> $credentials['product'],
        		'gst' 				=> $gst,
        		'category' 			=> $credentials['category'],
        		'criteria' 			=> $criteria,
        		'aop' 				=> $aop,
        		'status' 			=> 1
        	]);


        	if ($business->id) {


                    // Add Business Email
        			if ($email != null) {
        				EmailsFoundation::create([
        					'business_id' 	 => $business->id,
        					'businessmen_id' => $credentials['businessmen_id'],
        					'is_business' 	 => TRUE,
        					'email'			 => $email
        				]);
        			}

                    // Add Business Email
        			if ($email2 != null) {
        				EmailsFoundation::create([
        					'business_id' 	 => $business->id,
        					'businessmen_id' => $credentials['businessmen_id'],
        					'is_business' 	 => TRUE,
        					'email'			 => $email2
        				]);
        			}

                    // Add Businessmen as a partner related to a Business
        			BusinessPartner::create([
        				'businessmen_id' => $credentials['businessmen_id'],
        				'business_id'	 => $business->id
        				]);

                    // Add Designation of a Businessmen related to a Business
                    Designation::create([
                        'businessmen_id' => $credentials['businessmen_id'],
                        'business_id'    => $business->id,
                        'designation'    => $credentials['designation']                      
                    ]);

                // Add Type of a Businessmen related to a Business

                    foreach ($credentials['type'] as $t => $ty) {
                        
                        Type::create([
                            'business_id'    => $business->id,
                            'type'    => $ty
                        ]);
                    }


                    // Add Groups of a Business
        			foreach ($credentials['group'] as $k => $v) {

	        			GroupHirarcy::create([
	        				'business_id'	 => $business->id,
	        				'groups'		 => $v
	        				]);

        			}

                    // Add Docs
        			if (isset($credentials['images'])) {
        				$this->saveDocs($credentials['images'], $credentials['businessmen_id']);
        			}

        	}

        }else{

            return response()->json(['error' => $validator->messages()], 422);

        }


	}

    private function generateRegistration($value=null)
    {
        $prevID = '';
        $newYear = '';

        if (isset($value->br_number)) {
            $prevID = explode('-', $value->br_number);

            $oldyear = $prevID[2];

            if ($oldyear == date('Y')) {
                $newYear = $oldyear;
            }else{
                $newYear = date('Y');
            }

            $number = $prevID[1];
            $newnumber = $number + 1;


              return 'BR-'.str_pad($newnumber, strlen($number), '0', STR_PAD_LEFT) .'-'. $newYear;


        }else{
            return 'BR-001-'.date('Y');
        }
    }

    private function saveDocs($docs, $business)
    {
        	if ($docs) {
        		
        		for ($i=0; $i < count($docs)-1 ; $i++) { 
        				
        				BusinessHirarcy::create([
        					'business_id' => $business,
        					'docs'		  => $docs[$i]
        				]);
        		}

        	}
    }

    public function editBusiness(Request $request, $id)
    {
    	$credentials = $request->input('params');

        $rules = [

            'name' 			 => 'required',
            'address' 	 	 => 'required',
            'office' 		 => 'required',
            'website' 		 => 'sometimes',
            'product' 		 => 'required',
            'group' 		 => 'required',
            'gst' 		 	 => 'sometimes|required|unique:business,gst,'.$id,
            'category' 		 => 'required',
            'criteria' 		 => 'required_if:category,==,Firm',
            'aop' 		 	 => 'required_if:category,==,Firm|unique:business,aop,'.$id,
        ];


        $validator = Validator::make($credentials, $rules);

        if(!$validator->fails()) {

			$business = Business::find($id);

        	$website = (isset($credentials['website'])) ? $credentials['website'] : $business['website'];
        	$gst = (isset($credentials['gst'])) ? $credentials['gst'] : $business['gst'];
        	$criteria = (isset($credentials['criteria'])) ? $credentials['criteria'] : $business['criteria'];
        	$aop = (isset($credentials['aop'])) ? $credentials['aop'] : $business['aop'];


			$business->name 		= $credentials['name'];
			$business->address 		= $credentials['address'];
			$business->office 		= $credentials['office'];
			$business->website 		= $website;
			$business->product 		= $credentials['product'];
			$business->gst 			= $gst;
			$business->category 	= $credentials['category'];


			if ($credentials['category'] === 'Individual') {
					$business->criteria 	= null;
					$business->aop 			= null;
			}


			if ($credentials['category'] === 'Company') {
					$business->criteria 	= null;
					$business->aop 			= $aop;
			}


			if ($credentials['category'] === 'Firm') {
					$business->criteria 	= $criteria;
					$business->aop 			= $aop;	
			}


			$business->save();


            if (isset($credentials['type'])) {
                
                if ($type = Type::where('business_id', $id)->get()) {
                            foreach ($type as $k) {
                                $k->delete();
                            }
                }

                    foreach ($credentials['type'] as $t) {
                        
                            Type::create([
                                    'business_id'    => $id,
                                    'type'         => $t
                                    ]);                  
                    }

            }


			if ($group = GroupHirarcy::where('business_id', $id)->get()) {
						foreach ($group as $k) {
							$k->delete();
						}
			}

			        foreach ($credentials['group'] as $k => $v) {

	        			GroupHirarcy::create([
	        				'business_id'	 => $id,
	        				'groups'		 => $v
	        				]);

        			}


			if (isset($credentials['docs'])) {
				
        			foreach ($credentials['docs'] as $k) {

	        			BusinessHirarcy::create([
	        				'business_id'	 => $id,
	        				'docs'		 => $k
	        				]);

        			}

			}


        }else{
        	return response()->json(['success' => false, 'error' => $validator->messages()], 422);
        }

    }

	public function checkUnique($value=null, $field=null)
    {
        $unique = Business::where($field, $value)->first();

        if ($unique) {
            echo 1;
        }else{
            echo 0;
        }
    }

	public function checkUniqueEmails($value=null)
    {
        $unique = EmailsFoundation::where('email', $value)->first();

        if ($unique) {
            echo 1;
        }else{
            echo 0;
        }
    }

	public function editCheckUnique($value=null, $field=null, $id=null)
    {
    	$data = Business::find($id);

    	if ($data->$field == $value) {
    		echo 1;
    	}else{
    		echo 0;
    	}

    }

	public function uniqueGroup($value=null, $field=null)
    {
        $unique = Groups::where($field, $value)->first();

        if ($unique) {
            echo 1;
        }else{
            echo 0;
        }
    }

    public function addGroup(Request $request)
    {

   		$credentials = $request->input('params');

        $rules = [

            'code'  => 'required|unique:business_groups',
            'title' => 'required|unique:business_groups',

        ];

        $validator = Validator::make($credentials, $rules);

        if(!$validator->fails()) {

        	$business = Groups::create([
        		'code' 		=> $credentials['code'],
        		'title' 	=> $credentials['title'],
        	]);

        	return Groups::orderBy('title', 'ASC')->get();

        }else{

            return response()->json(['error' => $validator->messages()], 422);

        }
    }

    public function editGroup($id)
    {

        $rules = [

            'code'  => 'required|unique:business_groups,code,'.$id,
            'title' => 'required|unique:business_groups,title,'.$id,

        ];

        $validator = Validator::make([ 'code' => $_REQUEST['code'], 'title' => $_REQUEST['title']], $rules);

        if(!$validator->fails()) {

        	$group = Groups::find($id);

        	$group->code = $_REQUEST['code'];
        	$group->title = $_REQUEST['title'];
        	$group->save();

        	return Groups::orderBy('title', 'ASC')->get();

        }else{

            return response()->json(['error' => $validator->messages()], 422);

        }
    }

    public function removeGroup($id)
    {
    	$group = Groups::find($id);
    	$group->delete();

    	return Groups::orderBy('title', 'ASC')->get();
    }

    public function partners($id)
    {
        $partnerList = BusinessPartner::where('business_id', $id)->get();

    	$list = [];

    	foreach ($partnerList as $k) {

    		    array_push($list, $k->businessmen_id);

    	}

    	$business = Businessmen::with('businessemails')->whereIn('id', $list)->get();


        // Remove emails not matched with given business id = $id & Add Designation to collection
        foreach ($business as $k => $n) {

                    // Add Designation
                    $designation = Designation::where(['businessmen_id' => $n->id, 'business_id' => $id])->get();
                        if (isset($designation[0]['designation'])) {
                        $business[$k]->designation = $designation[0]['designation'];
                    }else{
                        $business[$k]->designation = null;
                    }
                    // Add Designation End


            foreach ($n->businessemails as $v => $i) {
                    if ($i->business_id != $id) {
                         unset($business[$k]->businessemails[$v]);
                    }
            }

        }

        return $business;




    }


    // public function partners($id)
    // {
    // 	$partnerList = BusinessPartner::where('business_id', $id)->get();

    // 	$list = [];

    // 	foreach ($partnerList as $k) {
    // 		array_push($list, $k->businessmen_id);
    // 	}

    // 	$newdata = [];

    // 	array_push($newdata, Businessmen::whereIn('id', $list)->get());
    // 	array_push($newdata, EmailsFoundation::whereIn('businessmen_id', $list)->get());
    // 	// return Businessmen::whereIn('id', $list)->get();

    // 	return $newdata;

    // }

    public function addNewpartner(Request $request)
    {
   		$credentials = $request->input('params');

        $rules = [

            'business_id'  		=> 'required',
            'businessmen_id'    => 'required',
            'designation' 	    => 'required',
            'email' 			=> 'sometimes|required|email|unique:business_groups',
            'email' 			=> 'sometimes|required|email|unique:business_groups',

        ];

        $validator = Validator::make($credentials, $rules);

        if(!$validator->fails()) {

        	$email1 = (isset($credentials['email1'])) ? $credentials['email1'] : null;
        	$email2 = (isset($credentials['email2'])) ? $credentials['email2'] : null;

        	$checkDB = BusinessPartner::where([
        		'business_id' 	 => $credentials['business_id'],
        		'businessmen_id' => $credentials['businessmen_id'],
        		])->first();


        	if ($checkDB) {
        		 return response()->json(['error' => 'Businessmen already partner with selected Business'], 422);
        	}else{

	        	$business = BusinessPartner::create([
            		'business_id' 	 => $credentials['business_id'],
            		'businessmen_id' => $credentials['businessmen_id'],
	        	]);

                Designation::create([
                    'business_id'    => $credentials['business_id'],
                    'businessmen_id' => $credentials['businessmen_id'],
                    'designation'    => $credentials['designation'],
                ]);


	        	if ($email1) {

		        	$business = EmailsFoundation::create([
	        		'business_id' 	 => $credentials['business_id'],
	        		'businessmen_id' => $credentials['businessmen_id'],
	        		'email' 		 => $credentials['email1'],
	        		'is_business' 	 => true,
		        	]);

	        	}


	        	if ($email2) {

		        	$business = EmailsFoundation::create([
	        		'business_id' 	 => $credentials['business_id'],
	        		'businessmen_id' => $credentials['businessmen_id'],
	        		'email' 		 => $credentials['email2'],
	        		'is_business' 	 => true,
		        	]);

	        	}


        	}


        	return Groups::orderBy('title', 'ASC')->get();

        }else{

            return response()->json(['error' => $validator->messages()], 422);

        }
    }

    public function fetchDocsOnly($id)
    {

    	$data = BusinessHirarcy::where('business_id', $id)->get();
        $files = $data->pluck('docs');
        return $files->all();

    }

    public function docs($id)
    {

    	$data = BusinessHirarcy::where('business_id', $id)->get();
        return $data;

    }

    public function Removedocs($id)
    {

    	$data = BusinessHirarcy::find($id)->delete();

    }

    public function downloadDocs($id)
   {
      $Docs = BusinessHirarcy::where('business_id', $id)->get();

        $files = [];

        foreach ($Docs as $k) {
			$doc = base64_decode(gzcompress($k->docs, 9));
            // array_push($files, $doc);
        	Zipper::make(public_path('downloads/dummy.zip'))->addString('br', $doc);
        }

        Zipper::make(public_path('downloads/dummy.zip'))->close();

        return response()->download(public_path('downloads/dummy.zip'))->deleteFileAfterSend(true);

   }

   public function editDesignation(Request $request)
   {
        $credentials = $request->input('params');
       $rules = [
            'business_id' => 'required',
            'businessmen_id' => 'required',
            'designation'   => 'required'
       ];

       $validator = Validator::make($credentials, $rules);

       if ($validator->fails()) {
           return response()->json(['error' => $validator->messages()], 422);
       }else{

           $des = Designation::where([
            'business_id' => $credentials['business_id'],
            'businessmen_id' => $credentials['businessmen_id']
            ])->first();

           if (isset($des->designation)) {
                   $des->designation = $credentials['designation'];
                   $des->save();
           }else{
                 Designation::create([
                'business_id' => $credentials['business_id'],
                'businessmen_id' => $credentials['businessmen_id'],
                'designation' => $credentials['designation']
                ]);
           }


       }


   }

   public function removeHirarcyDoc()
   {
   		// $image = $_REQUEST['image'];
   		// DB::table('business_hirarcy')->where('docs','=', $image)->delete();

   }

   public function addFoundation($id, $email)
   {

        $rules = ['email' => 'required|email|unique:emails_foundation,email,'.$id,];
        $validator = Validator::make([ 'email' => $email], $rules);

        if(!$validator->fails()) {
        		$e = EmailsFoundation::find($id);
        		$e->email = $email;
        		$e->save();

        		return 1;
        }else{
 				return response()->json(['error' => $validator->messages()], 422);
        }

   }

   public function addSingleFoundation(Request $request)
   {

   		$credentials = $request->input('params');
        $rules = [
	        'email' => 'required|email|unique:emails_foundation',
	        'business_id' => 'required',
	        'businessmen_id' => 'required',
        ];
        $validator = Validator::make($credentials, $rules);

        if(!$validator->fails()) {
        		EmailsFoundation::create([
        			'business_id' => $credentials['business_id'],
        			'businessmen_id' => $credentials['businessmen_id'],
        			'email' => $credentials['email'],
        			'is_business' => true,
        		]);

        		return 1;
        }else{
 				return response()->json(['error' => $validator->messages()], 422);
        }
   }

}
