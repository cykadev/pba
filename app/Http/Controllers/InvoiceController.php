<?php

namespace App\Http\Controllers;

use App\models\Business;
use App\models\Businessmen;
use App\models\Cashbook;
use App\models\Invoice;
use App\models\InvoiceChild;
use App\models\MembershipExpiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $filter=null, $per_page=null)
    {
        $filter = isset($_REQUEST['filter']);
        $per_page = isset($_REQUEST['per_page']);

        if ($filter) {

            $condition = $this->searchMethod($_REQUEST['filter']);

            if ($_REQUEST['filter'] != '') {
                return $invoices = Invoice::with('assigned')
                ->where('invoice_id', 'like', $_REQUEST['filter'].'%')
                ->orWhere('invoice_status', 'like', $condition.'%')
                ->orWhere('invoice_for', 'like', $_REQUEST['filter'].'%')
                ->orWhere('invoice_messer', 'like', $_REQUEST['filter'].'%')
                ->orWhere('total', 'like', $_REQUEST['filter'].'%')
                ->orWhere('payment_mode', 'like', $_REQUEST['filter'].'%')
                ->orWhere('branch_name', 'like', $_REQUEST['filter'].'%')
                ->paginate(($per_page) ? $_REQUEST['per_page'] : 20);
            }else{
                return $invoices = Invoice::with('assigned')->orderBy('id', 'Desc')->paginate(($per_page) ? $_REQUEST['per_page'] : 20);
            }

        }else{
            return $invoices = Invoice::with('assigned')->orderBy('id', 'Desc')->paginate(($per_page) ? $_REQUEST['per_page'] : 20); 
        }
    }


    private function searchMethod($value=null)
    {
        if ($value == 'paid') {
                 return 1;
        }elseif ($value == 'pending') {
                 return 0;
        }else{
                return $value;
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function assign(Request $request)
    {
        $credentials = $request->input('params');

        $rules = [
            'businessmen_id' => 'required',
            'business_id' => 'required',
            'invoice_id' => 'required',
        ];

        $validator = Validator::make($credentials, $rules);


        if (!$validator->fails()) {

                $invoice = Invoice::find($credentials['invoice_id']);

                $invoice->assigned()->attach($credentials['business_id'], ['businessmen_id' => $credentials['businessmen_id']]);

                MembershipExpiry::create([
                    'business_id' => $credentials['business_id'],
                    'businessmen_id' => $credentials['businessmen_id'],
                    'invoice_id' => $credentials['invoice_id'],
                    'status' => true,
                    'paid_date' => date('Y-m-d'),
                    'expiry_date' => date('Y-m-d', strtotime('+1 year'))
                ]);

        }else{
            return response()->json(['success' => false, 'message' => 'Invoice Assigned Error'], 422);
        }



    }

    private function generateInvoice($value=null)
    {
        $prevID = '';
        $newYear = '';

        if (isset($value->invoice_id)) {
            $prevID = explode('-', $value->invoice_id);

            $oldyear = $prevID[0];

            if ($oldyear == date('Y')) {
                $newYear = $oldyear;
            }else{
                $newYear = date('Y');
            }

            $number = $prevID[1];
            $newnumber = $number + 1;
            return $newYear.'-'.str_pad($newnumber, 6, '0', STR_PAD_LEFT);

        }else{
            return date('Y').'-000001';
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {

        $invoice_id = DB::table('invoices')->orderBy('id', 'desc')->first();

        $newinvoice = $this->generateInvoice($invoice_id);

        $inserted = '';
        $invoiceChild = '';
        $applied_admission = (isset($request['params']['Values'][0])) ? 1 : 0;
        $transaction_id = (isset($request['params']['Check'])) ? $request['params']['Check'] : '';
        $branch_name = (isset($request['params']['Branch'])) ? $request['params']['Branch'] : '';
        $cf1name = (isset($request['params']['CF1NAME'])) ? $request['params']['CF1NAME'] : null;
        $cf1value = (isset($request['params']['CF1VALUE'])) ? $request['params']['CF1VALUE']: null;
        $cf1name2 = (isset($request['params']['CF1NAME2'])) ? $request['params']['CF1NAME2'] : null;
        $cf1value2 = (isset($request['params']['CF1VALUE2'])) ? $request['params']['CF1VALUE2'] : null;
        $cf1name3 = (isset($request['params']['CF1NAME3'])) ? $request['params']['CF1NAME3'] : null;
        $cf1value3 = (isset($request['params']['CF1VALUE3'])) ? $request['params']['CF1VALUE3'] : null;
        $cf1name4 = (isset($request['params']['CF1NAME4'])) ? $request['params']['CF1NAME4'] : null;
        $cf1value4 = (isset($request['params']['CF1VALUE4'])) ? $request['params']['CF1VALUE4'] : null;
        $cf1name5 = (isset($request['params']['CF1NAME5'])) ? $request['params']['CF1NAME5'] : null;
        $cf1value5 = (isset($request['params']['CF1VALUE5'])) ? $request['params']['CF1VALUE5'] : null;


        $inserted = Invoice::create([
            'invoice_id' => $newinvoice,
            'issued_on' => $request['issued_on'],
            'instruction' => $request['params']['Instructions'],
            'invoice_for' => $request['params']['Member'],
            'cf1name' => $cf1name,
            'cf1value' => $cf1value . ' PKR',
            'cf1name2' => $cf1name2,
            'cf1value2' => $cf1value2 . ' PKR',
            'cf1name3' => $cf1name3,
            'cf1value3' => $cf1value3 . ' PKR',
            'cf1name4' => $cf1name4,
            'cf1value4' => $cf1value4 . ' PKR',
            'cf1name5' => $cf1name5,
            'cf1value5' => $cf1value5 . ' PKR',
            'applied_admission' => $applied_admission,
            'invoice_messer' => $request['params']['Messer'],
            'total' => $request['params']['Total'],
            'total_in_words' => $request['total_in_words'],
            'payment_mode' => $request['params']['Mode'],
            'transaction_id' => $transaction_id,
            'branch_name' => $branch_name,
            'generated_by' => Auth::user()->name,
            'invoice_status' => 0,
        ]);


        if ($inserted) {

                            $validDonation = 0;

                            /*
                                Log Into Cashbook
                            */                

                            // Log For Donation

                            if ($cf1name == 'Donation') {
                                $validDonation = $cf1value;
                                $this->logDonation($request, $cf1value, $newinvoice);
                            }elseif ($cf1name2 == 'Donation') {
                                $validDonation = $cf1value2;
                                $this->logDonation($request, $cf1value2, $newinvoice);
                            }elseif ($cf1name3 == 'Donation') {
                                $validDonation = $cf1value3;
                                $this->logDonation($request, $cf1value3, $newinvoice);
                            }elseif ($cf1name4 == 'Donation') {
                                $validDonation = $cf1value4;
                                $this->logDonation($request, $cf1value4, $newinvoice);
                            }elseif ($cf1name5 == 'Donation') {
                                $validDonation = $cf1value5;
                                $this->logDonation($request, $cf1value5, $newinvoice);
                            }else{

                            }


                            $subDonation = ($validDonation > 0) ? $request['params']['Total'] - $validDonation : $request['params']['Total'];


                            // Log For Membership

                            Cashbook::create([
                                'type' => 'Income',
                                'category' => 'Membership',
                                'payer' => $request['params']['Member'],
                                'method' => $request['params']['Mode'],
                                'ref' => $newinvoice,
                                'total' => $subDonation,
                                'description' => $request['params']['Instructions'],
                                'date' => date("Y-m-d"),
                                'status' => 0
                            ]);

                
                foreach ($request['params']['Values'] as $k) {

                $valid = (in_array($k['title'],$request['params']['Admission'])) ? 1 : 0;


                            InvoiceChild::create([
                                'invoice_id' => $inserted->id,
                                'title' => $k['title'],
                                'admission' => $valid,
                                'price' => $k['price'],
                                'admission_price' => $k['admission'],
                            ]);

            }

        }else{

            return 'Something went wrong';

        }

         return $newinvoice;
    }


    private function logDonation($request, $total, $newinvoice, $payer=null)
    {

        if (isset($request['params']['Member'])) {

            Cashbook::create([
                'type' => 'Income',
                'category' => 'Donation',
                'payer' => $request['params']['Member'],
                'method' => $request['params']['Mode'],
                'ref' => $newinvoice,
                'total' => $total,
                'description' => $request['params']['Instructions'],
                'date' => date("Y-m-d"),
                'status' => 0
            ]);
        }else{
            Cashbook::create([
                'type' => 'Income',
                'category' => 'Donation',
                'payer' => $payer,
                'method' => $request['params']['Mode'],
                'ref' => $newinvoice,
                'total' => $total,
                'description' => $request['params']['Instructions'],
                'date' => date("Y-m-d"),
                'status' => 0
            ]);           
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */

    public function show(Request $request, Invoice $invoice)
    {

            $newdata = [];

            array_push($newdata, $invoice);
            array_push($newdata, $invoice->childs);
            return $newdata;

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function edit(Invoice $invoice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Invoice $invoice)
    {

            // For Update Status

        $status = $invoice->invoice_status;
        $cashLogs = Cashbook::where('ref', $invoice->invoice_id)->get();
        $expiry = MembershipExpiry::where( 'invoice_id' , $invoice->id )->orderBy('id', 'DESC')->first();


        if ($expiry !== null) {

            $expiry->status = true;
            $expiry->save();
        }else{

        }


        foreach ($cashLogs as $k) {

            if ($k->status == 0) {

                $invoice->invoice_status = 1;

                        $k->status = 1;
                        $k->save();

                $invoice->save();
                return;
            }

             return response()->json('Unable to change status receipt already logged in Cashbook', 403);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function destroy(Invoice $invoice)
    {

        $k = InvoiceChild::where('invoice_id', $invoice->id)->get();
        $cashLogs = Cashbook::where('ref', $invoice->invoice_id)->get();

        /*
            check for invoice is not logged in cashbook entry
        */
        if ($invoice->invoice_status == 0) {

            foreach ($k as $e) {
                $e->delete();
            }

            foreach ($cashLogs as $e) {
                $e->delete();
            }

            $invoice->delete();
            return;
        }

        return response()->json('Unable to remove receipt already logged in Cashbook', 403);

    }

    public function makeRenewal(Request $request, $id)
    {
        // return $request->input('params');
        $invoice_id = DB::table('invoices')->orderBy('id', 'desc')->first();

                $newinvoice = $this->generateInvoice($invoice_id);

                $inserted = '';
                $invoiceChild = '';
                $applied_admission = (isset($request['params']['Values'][0])) ? 1 : 0;
                $transaction_id = (isset($request['params']['Check'])) ? $request['params']['Check'] : '';
                $branch_name = (isset($request['params']['Branch'])) ? $request['params']['Branch'] : '';
                $cf1name = (isset($request['params']['cf1name'])) ? $request['params']['cf1name'] : null;
                $cf1value = (isset($request['params']['cf1v'])) ? $request['params']['cf1v'] : null;
                $cf1name2 = (isset($request['params']['cf1name2'])) ? $request['params']['cf1name2'] : null;
                $cf1value2 = (isset($request['params']['cf1v2'])) ? $request['params']['cf1v2'] : null;
                $cf1name3 = (isset($request['params']['cf1name3'])) ? $request['params']['cf1name3'] : null;
                $cf1value3 = (isset($request['params']['cf1v3'])) ? $request['params']['cf1v3'] : null;
                $cf1name4 = (isset($request['params']['cf1name4'])) ? $request['params']['cf1name4'] : null;
                $cf1value4 = (isset($request['params']['cf1v4'])) ? $request['params']['cf1v4'] : null;
                $cf1name5 = (isset($request['params']['cf1name5'])) ? $request['params']['cf1name5'] : null;
                $cf1value5 = (isset($request['params']['cf1v5'])) ? $request['params']['cf1v5'] : null;


                /*
                    Get Businessmen
                */

                $bmen = Businessmen::find($id)->first();
                $bid = Business::find($request['params']['business_id'])->first();

                $inserted = Invoice::create([
                    'invoice_id' => $newinvoice,
                    'issued_on' => $request['params']['issued_on'],
                    'instruction' => $request['params']['Instructions'],
                    'invoice_for' => $bmen->name,
                    'cf1name' => $cf1name,
                    'cf1value' => $cf1value,
                    'cf1name2' => $cf1name2,
                    'cf1value2' => $cf1value2,
                    'cf1name3' => $cf1name3,
                    'cf1value3' => $cf1value3,
                    'cf1name4' => $cf1name4,
                    'cf1value4' => $cf1value4,
                    'cf1name5' => $cf1name5,
                    'cf1value5' => $cf1value5,
                    'applied_admission' => 1,
                    'invoice_messer' => $bid->name,
                    'total' => $request['params']['total'],
                    'total_in_words' => $request['params']['total_in_words'],
                    'payment_mode' => $request['params']['Mode'],
                    'transaction_id' => $transaction_id,
                    'branch_name' => $branch_name,
                    'generated_by' => Auth::user()->name,
                    'invoice_status' => 0,
                ]);


                /*
                    Insert Into Pivot Assigned table
                */
                $inserted->assigned()->attach($request['params']['business_id'], ['businessmen_id' => $id]);


                if ($inserted) {

                                    $validDonation = 0;

                                    /*
                                        Log Into Cashbook
                                    */                

                                    // Log For Donation

                                    if ($cf1name == 'Donation') {
                                        $validDonation = $cf1value;
                                        $this->logDonation($request, $cf1value, $newinvoice, $bmen->name);
                                    }elseif ($cf1name2 == 'Donation') {
                                        $validDonation = $cf1value2;
                                        $this->logDonation($request, $cf1value2, $newinvoice, $bmen->name);
                                    }elseif ($cf1name3 == 'Donation') {
                                        $validDonation = $cf1value3;
                                        $this->logDonation($request, $cf1value3, $newinvoice, $bmen->name);
                                    }elseif ($cf1name4 == 'Donation') {
                                        $validDonation = $cf1value4;
                                        $this->logDonation($request, $cf1value4, $newinvoice, $bmen->name);
                                    }elseif ($cf1name5 == 'Donation') {
                                        $validDonation = $cf1value5;
                                        $this->logDonation($request, $cf1value5, $newinvoice, $bmen->name);
                                    }else{

                                    }


                                    $subDonation = ($validDonation > 0) ? $request['params']['total'] - $validDonation : $request['params']['total'];


                                    // Log For Membership

                                    Cashbook::create([
                                        'type' => 'Income',
                                        'category' => 'Membership',
                                        'payer' => $bmen->name,
                                        'method' => $request['params']['Mode'],
                                        'ref' => $newinvoice,
                                        'total' => $subDonation,
                                        'description' => $request['params']['Instructions'],
                                        'date' => date("Y-m-d"),
                                        'status' => 0
                                    ]);

                        
                        foreach ($request['params']['Values'] as $k) {

                        $valid = (in_array($k['title'],$request['params']['Admission'])) ? 1 : 0;


                                    InvoiceChild::create([
                                        'invoice_id' => $inserted->id,
                                        'title' => $k['title'],
                                        'admission' => $valid,
                                        'price' => $k['price'],
                                        'admission_price' => $k['admission'],
                                    ]);

                    }

                    /*
                        Log Into Expiry Table
                    */

                    MembershipExpiry::create([
                        'business_id' => $request['params']['business_id'],
                        'businessmen_id' => $id,
                        'invoice_id' => $inserted->id,
                        'status' => false,
                        'paid_date' => date('Y-m-d'),
                        'expiry_date' => date('Y-m-d', strtotime('+1 year')),
                    ]);




                }else{

                    return 'Something went wrong';

                }

                 return $newinvoice;
    }
}
