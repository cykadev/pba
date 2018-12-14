<?php

namespace App\Http\Controllers;

use App\models\Cashbook;
use App\models\CashbookCategory;
use App\models\ExpenseCategory;
use App\models\pettyCash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{

    public function getExpenses($filter=null)
    {

        sleep(1);
        return pettyCash::where(['date' => $filter])->get();

        // return pettyCash::where('date', 'like', $filter.'%')
        //                              ->orWhere('updated_at', 'like', $filter.'%')->get();

    }

    public function credit()
    {
        return response()->json(Cashbook::where(['type' => 'Income', 'status' => 1])->sum('total'));
    }

    public function debit()
    {
        return response()->json(Cashbook::where(['type' => 'Expense', 'status' => 1])->sum('total'));
    }

    public function pettycredit()
    {

        $lastDate = [];

        array_push($lastDate, ['total' => Cashbook::where(['type'=>'Expense', 'category' => 'Petty Cash', 'status' => 1])->sum('total')]);
        array_push($lastDate, ['date' => Cashbook::where(['type'=>'Expense', 'category' => 'Petty Cash', 'status' => 1])->orderBy('id', 'DESC')->first()->date]);

        return response()->json($lastDate);

    }

    public function pettydebit()
    {
        return response()->json(pettyCash::sum('total'));
    }

    private function searchWithType( $filter=null, $per_page=null, $type=null)
    {
                return DB::table('cashbook')
                               ->where('status', 1)
                               ->whereIn('type', $type)
                               ->whereBetween('date', [$filter[0],$filter[1]])
                               ->orderBy('date', 'DESC')
                               ->paginate(($per_page) ? $per_page : 20);

    }

    private function searchWithCategory( $filter=null, $per_page=null, $category=null)
    {
                return DB::table('cashbook')
                               ->where('status', 1)
                               ->whereIn('category', $category)
                               ->whereBetween('date', [$filter[0],$filter[1]])
                               ->orderBy('date', 'DESC')
                               ->paginate(($per_page) ? $per_page : 20);
    }

    private function searchWithCategoryPetty( $filter=null, $per_page=null, $category=null)
    {
                return DB::table('pettycash')
                               ->whereIn('category', $category)
                               ->whereBetween('date', [$filter[0],$filter[1]])
                               ->orderBy('date', 'DESC')
                               ->paginate(($per_page) ? $per_page : 20);
    }

    private function searchWithBoth( $filter=null, $per_page=null, $category=null, $type=null)
    {
                return DB::table('cashbook')
                               ->where('status', 1)
                               ->whereIn('type', $type)
                               ->whereIn('category', $category)
                               ->whereBetween('date', [$filter[0],$filter[1]])
                               ->orderBy('date', 'DESC')
                               ->paginate(($per_page) ? $per_page : 20);
    }

    private function searchWithoutBoth( $date=null, $per_page=null )
    {

        if ($date[0] == date('Y-m-d')) {
                return DB::table('cashbook')
                               ->where('status',1)
                               ->whereBetween('date', [date('Y-m-d'),date('Y-m-d')])
                               ->orderBy('date', 'DESC')
                               ->paginate(($per_page) ? $per_page : 20);
        }else{
                return DB::table('cashbook')
                               ->where('status',1)
                               ->whereBetween('date', [$date[0],$date[1]])
                               ->orderBy('date', 'DESC')
                               ->paginate(($per_page) ? $per_page : 20);
        }
    }

    private function searchWithoutBothPetty( $per_page=null, $date=null)
    {

            if ($date[0] == date('Y-m-d')) {
                    return DB::table('pettycash')
                                   ->whereBetween('date', [date('Y-m-d'),date('Y-m-d')])
                                   ->orderBy('date', 'DESC')
                                   ->paginate(($per_page) ? $per_page : 20);
            }else{
                    return DB::table('pettycash')
                                   ->whereBetween('date', [$date[0],$date[1]])
                                   ->orderBy('date', 'DESC')
                                   ->paginate(($per_page) ? $per_page : 20);
            }

    }

    private function searchBaseQuery( $query=null, $per_page=null)
    {
                return DB::table('cashbook')
                               ->where('ref', 'like', $query.'%')
                               ->orWhere('method', 'like', $query.'%')
                               ->orWhere('total', 'like', $query.'%')
                               ->orWhere('payer', 'like', $query.'%')
                               ->orWhere('date', 'like', $query.'%')
                               ->where('status',1)
                               ->orderBy('id', 'DESC')
                               ->paginate(($per_page) ? $per_page : 20);
    }

    private function searchBaseQueryPetty( $query=null, $per_page=null)
    {
                return DB::table('pettycash')
                               ->where('total', 'like', $query.'%')
                               ->orderBy('id', 'DESC')
                               ->paginate(($per_page) ? $per_page : 20);
    }

    public function getxls()
    {

        $category = explode(',',$_REQUEST['category']);
        $type = explode(',',$_REQUEST['type']);

        $queryDate = explode('_', $_REQUEST['filter']);

        if ($category[0] && $type[0]) {

            return Cashbook::where('status',1)
                    ->whereIn('category', $category)
                    ->whereIn('type', $type)
                    ->whereBetween('date', [$queryDate[0],$queryDate[1]])
                    ->orderBy('id', 'DESC')
                    ->paginate($_REQUEST['per_page']);
        }elseif($category[0] == true && $type[0] == false){

            return Cashbook::where('status',1)
                    ->whereIn('category', $category)
                    ->whereBetween('date', [$queryDate[0],$queryDate[1]])
                    ->orderBy('id', 'DESC')
                    ->paginate($_REQUEST['per_page']);
        }elseif($category[0] == false && $type[0] == true){

            return Cashbook::where('status',1)
                    ->whereIn('type', $type)
                    ->whereBetween('date', [$queryDate[0],$queryDate[1]])
                    ->orderBy('id', 'DESC')
                    ->paginate($_REQUEST['per_page']);
        }else{
             return Cashbook::where('status',1)
                    ->whereBetween('date', [$queryDate[0],$queryDate[1]])
                    ->orderBy('id', 'DESC')
                    ->paginate($_REQUEST['per_page']);           
        }


    }

    public function getCashbookIndex()
    {

        
            $per_page = isset($_REQUEST['per_page']);
            $filter = isset($_REQUEST['filter']);
            $requestType = isset($_REQUEST['type']);
            $requestCategory = isset($_REQUEST['category']);

            $newType =  ($requestType) ? json_decode($_REQUEST['type'], true) : '';
            $newCategory =  ($requestCategory) ? json_decode($_REQUEST['category'], true) : '';

            $category = empty($newCategory['category']);
            $type = empty($newType['type']);


                 $queryDate = explode('_', $_REQUEST['filter']);



                if (isset($_REQUEST['q'])) {

                   return $this->searchBaseQuery($_REQUEST['q'], $_REQUEST['per_page']);

                }elseif ($type == false && $category == true) {

                    return $this->searchWithType($queryDate, $_REQUEST['per_page'], $newType['type']);


                }elseif ($category == false && $type == true) {
                
                    return $this->searchWithCategory($queryDate, $_REQUEST['per_page'], $newCategory['category']);


                }elseif($category == false && $type == false){

                    return $this->searchWithBoth($queryDate, $_REQUEST['per_page'], $newCategory['category'], $newType['type']);

                }else{
                    return $this->searchWithoutBoth($queryDate,$_REQUEST['per_page']);
                }


    }

    public function getPettycashIndex(Request $filter=null, $per_page=null)
    {

            $per_page = isset($_REQUEST['per_page']);
            $filter = isset($_REQUEST['filter']);
            $requestCategory = isset($_REQUEST['category']);

            $newCategory =  ($requestCategory) ? json_decode($_REQUEST['category'], true) : '';

            $category = empty($newCategory['category']);


                 $queryDate = explode('_', $_REQUEST['filter']);


                if (isset($_REQUEST['q'])) {

                   return $this->searchBaseQueryPetty($_REQUEST['q'], $_REQUEST['per_page']);


                }elseif (!empty($newCategory['category'])) {

                    return $this->searchWithCategoryPetty($queryDate, $_REQUEST['per_page'], $newCategory['category']);


                }else{

                    return $this->searchWithoutBothPetty($_REQUEST['per_page'], $queryDate);
                }


    }

    public function addCategory(Request $request)
    {
        $credentials = $request->input('params');

        $rules = [
            'category' => 'required|unique:expense_categories',
        ];

        $validator = Validator::make($credentials, $rules);

        if(!$validator->fails()) {

        	ExpenseCategory::create([
        		'category' => $credentials['category']
        	]);

        }else{

        	return response()->json(['success' => false, 'error' => $validator->messages()], 200);

        }
    }

    public function addCashbookCategory(Request $request)
    {
        $credentials = $request->input('params');

        $rules = [
            'category' => 'required|unique:cashbook_categories',
        ];

        $validator = Validator::make($credentials, $rules);

        if(!$validator->fails()) {

            CashbookCategory::create([
                'category' => $credentials['category']
            ]);

        }else{

            return response()->json(['success' => false, 'error' => $validator->messages()], 200);

        }
    }
    public function addExpenses(Request $request)
    {
        $credentials = $request->input('params');

        $rules = [
            'category' => 'required',
            'total' => 'required|integer',
            'description' => 'required',
        ];

        $validator = Validator::make($credentials, $rules);

        if(!$validator->fails()) {

            $treasure = Cashbook::where(['type'=>'Expense', 'category' => 'Petty Cash', 'status' => 1])->sum('total');
            $expended = pettyCash::sum('total');

                if ($expended + $credentials['total'] <= $treasure) {

                    	pettyCash::create([
                    		'category' => $credentials['category'],
                    		'total' => $credentials['total'],
                    		'description' => $credentials['description'],
                            'date' => date("Y-m-d")
                    	]);
                }else{

                    return response()->json(['success' => false, 'error' => 'You dont have enough remaining balance!'], 200);

                }



        }else{

        	return response()->json(['success' => false, 'error' => $validator->messages()], 200);

        }
    }
    public function addCashbook(Request $request)
    {
        $credentials = $request->input('params');

        $rules = [
            'type' => 'required',
            'category' => 'required',
            'payer' => 'required',
            'method' => 'required',
            'ref' => 'sometimes',
            'total' => 'required|integer',
            'description' => 'required',
        ];

        $validator = Validator::make($credentials, $rules);

        if(!$validator->fails()) {

                if ($credentials['type'] == 'Expense') {

                            $treasure = Cashbook::where(['type' => 'Income', 'status' => 1])->sum('total');
                            $expended = Cashbook::where(['type' => 'Expense', 'status' => 1])->sum('total');

                            if ($expended + $credentials['total'] <= $treasure) {

                                    Cashbook::create([
                                        'type' => $credentials['type'],
                                        'category' => $credentials['category'],
                                        'payer' => $credentials['payer'],
                                        'method' => $credentials['method'],
                                        'ref' => $credentials['ref'],
                                        'total' => $credentials['total'],
                                        'description' => $credentials['description'],
                                        'date' => date("Y-m-d"),
                                        'status' => 1
                                    ]);
                            }else{

                                return response()->json(['success' => false, 'error' => 'You dont have enough Balance!'], 200);

                            }

                }else{
                         Cashbook::create([
                            'type' => $credentials['type'],
                            'category' => $credentials['category'],
                            'payer' => $credentials['payer'],
                            'method' => $credentials['method'],
                            'ref' => $credentials['ref'],
                            'total' => $credentials['total'],
                            'description' => $credentials['description'],
                            'date' => date("Y-m-d"),
                            'status' => 1
                        ]); 
                }

        }else{

            return response()->json(['success' => false, 'error' => $validator->messages()], 200);

        }
    }
    public function getCategory()
    {
    	$categories = ExpenseCategory::get();
    	return $categories;
    }
    public function getCashbookCategory()
    {
        $categories = CashbookCategory::get();
        return $categories;
    }
    public function checkUnique($value=null)
    {
        $unique = ExpenseCategory::where('category', $value)->first();

        if ($unique) {
            echo 1;
        }else{
            echo 0;
        }
    }
    public function cashbookCheckUnique($value=null)
    {
        $unique = CashbookCategory::where('category', $value)->first();

        if ($unique) {
            echo 1;
        }else{
            echo 0;
        }
    }

    public function removePettycash(pettyCash $id)
    {
        $id->delete();
    }
}
