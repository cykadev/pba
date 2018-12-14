<?php

namespace App\Http\Controllers;

use App\models\Cashbook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LedgerController extends Controller
{
    public function index()
    {
    	if (isset( $_REQUEST['filter'] )) {
    		$date = explode('_', $_REQUEST['filter']);

    		if ( $date[1] ) {
    			return $this->prepare( $date );
    		}

    	}else{
    		return response()->json(['status'=>401]);
    	}
    }

        private function checkDuplicate($data, $date)
        {
            foreach ($data as $v ) {
                if ($v['date'] == $date) {
                    return $v['total'];
                }
            }
        }

    public static function compare($a, $b)
    {
        return strtotime($a['date']) - strtotime($b['date']);
    }

    public function getGraph()
    {
        
        $report = [];

        $income = Cashbook::selectRaw('sum(total) AS total, date')
                                ->where('status', 1)
                                ->where('type', 'Income')
                                ->groupBy('date')
                                ->get()->toArray();

        // $incomeTotal = $income->pluck('total');

        $min = Cashbook::where('status',1)->min('date');
        $max = Cashbook::where('status',1)->max('date');

        $now = strtotime(date("Y-m-d H:i:s"));
        $your_date = strtotime($min);
        $datediff = $now - $your_date;


        $s = [];
        $arrayIncome = [];

        for ($i=0; $i <= round($datediff / (60 * 60 * 24)); $i++) {

            $date = strtotime(+$i.' day', strtotime($min));
            array_push($s, [ 'total' => "0" , 'date' => date("Y-m-d", $date)]);

        }

        //Uncomment

        $newIncome = [];

        foreach ($s as $v) {

            $checked = $this->checkDuplicate($income , $v['date']);
            if ($checked !== false) {
                array_push($newIncome, (int)$checked);
            }else{
                array_push($newIncome, (int)$v['total']);
            }
        }

         array_push($report, ['income' => $newIncome]);


        $expense = Cashbook::selectRaw('sum(total) AS total, date')
                                ->where('status', 1)
                                ->where('type', 'Expense')
                                ->groupBy('date')
                                ->get()->toArray();


        $newExpense = [];

        foreach ($s as $v) {

            $checked = $this->checkDuplicate($expense , $v['date']);
            if ($checked !== false) {
                array_push($newExpense, (int)$checked);
            }else{
                array_push($newExpense, (int)$v['total']);
            }
        }

         array_push($report, ['expense' => $newExpense]);


/*
    Push Date
*/
        // array_push($report, ['days' => round($datediff / (60 * 60 * 24))]);

        $seprate = explode('-', $min);

        array_push($report, ['dateYear' => $seprate[0]]);
        array_push($report, ['dateMonth' => $seprate[1] - 1]);
        array_push($report, ['dateDay' => $seprate[2]]);

    	return $report;
    }


    private function prepare( $date=null )
    {


    	$ledger = [];
    	$change = '';

    	/*
			Get Income Categories
    	*/

			$income = $this->getIncomeCategories( $date );
			array_push($ledger, ['income' => $income]);

    	/*
			Get Expense Categories
    	*/

			$expense = $this->getExpenseCategories( $date );
			array_push($ledger, ['expense' => $expense]);

		/*
			Get Present Income Total
		*/

			$presentIncome = $this->setPresentIncome( $income );
			array_push($ledger, ['presentIncome' => $presentIncome]);

		/*
			Get Present Income Total
		*/

			$presentExpense = $this->setPresentIncome( $expense );
			array_push($ledger, ['presentExpense' => $presentExpense]);


		/*
			Get days Diffrence
		*/

			$change = $this->setChange($date[0], $date[1]);

		/*
			Get Recent Days Record
		*/

			$recentIncome = $this->getIncomeCategories( [ $change ,$date[0] ] );
			array_push($ledger, ['recentIncome' => $recentIncome]);

    	/*
			Get Recent Expense Categories
    	*/

			$recentExpense = $this->getExpenseCategories( [ $change ,$date[0] ] );
			array_push($ledger, ['recentExpense' => $recentExpense]);


		/*
			Get Recent Income Total
		*/

			$recentIncomeTotal = $this->setRecentIncomeTotal( $income , $recentIncome );
			array_push($ledger, ['recentIncomeTotal' => $recentIncomeTotal]);

		/*
			Get Recent Expense Total
		*/

			$recentExpenseTotal = $this->setRecentIncomeTotal( $expense , $recentExpense );
			array_push($ledger, ['recentExpenseTotal' => $recentExpenseTotal]);

			return $ledger;

    }




    private function getIncomeCategories( $date=null )
    {

		return Cashbook::selectRaw('sum(total) AS total, category')
								->where('status', 1)
								->where('type', 'Income')
								->whereBetween('date', [$date[0], $date[1]])
								->groupBy('category')
								->get()->toArray();

    }

    private function getExpenseCategories( $date=null )
    {

		return Cashbook::selectRaw('sum(total) AS total, category')
								->where('status', 1)
								->where('type', 'Expense')
								->whereBetween('date', [$date[0], $date[1]])
								->groupBy('category')
								->get()->toArray();
    }

    private function setPresentIncome( $income=null )
    {
    	$total = 0;
    	foreach ( $income as $k ) {
    		$total += $k['total'];
    	}

    	return $total;
    }

    private function setRecentIncomeTotal( $income=null, $recent=null )
    {
    	$total = 0;


    	foreach ( $recent as $k ) {


    		foreach ($income as $v) {
    			if ($k['category'] == $v['category']) {
    				$total += $k['total'];
    			}
    		}

    	}

    	return $total;
    }

    private function setRecentIncome( $income=null )
    {
    	$total = 0;
    	foreach ( $income as $k ) {
    		$total += $k->total;
    	}

    	return $total;
    }

    private function setChange( $from=null, $to=null )
    {
			$start = strtotime($from);
			$end = strtotime($to);
			$datediff = $end - $start;

			$diffrence = round( $datediff / (60 * 60 * 24) );

			$timestamp = $diffrence - $start;


				$date = date_create(date("Y-m-d", $start) . PHP_EOL);

				if ($diffrence <= 1) {
					date_modify($date, '-1 day');
				}else{
					date_modify($date, -$diffrence.' days');					
				}

				return date_format($date, 'Y-m-d');

    }

    private function setRecent( $date=null )
    {
    	return Cashbook::where('status',1)
						->where('type', 'Expense')
						->whereBetween('date', [$date[0], $date[1]])
						->get();
    }

}
