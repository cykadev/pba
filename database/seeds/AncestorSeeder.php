<?php

use App\models\NavAncestor;
use Illuminate\Database\Seeder;

class AncestorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
	DB::table('nav_ancestors')->truncate();


/*
    People ANCESTORS
*/
        NavAncestor::create(
            [
            'navbar_id' => 4,
            'navbar' => 'Businessmen',
            'navigate_to' => '/people/businessmen',
            ]
            );

        NavAncestor::create(
            [
            'navbar_id' => 4,
            'navbar' => 'Business',
            'navigate_to' => '/people/business',
            ]
            );

        NavAncestor::create(
            [
            'navbar_id' => 4,
            'navbar' => 'Users',
            'navigate_to' => '/people/users',
            ]
            );


/*
    Accounts ANCESTORS
*/
        NavAncestor::create(
        	[
        	'navbar_id' => 5,
        	'navbar' => 'Petty Cash',
        	'navigate_to' => '/accounts/pettyCash',
        	]
        	);

        NavAncestor::create(
        	[
        	'navbar_id' => 5,
        	'navbar' => 'Cash Book',
        	'navigate_to' => '/accounts/cashBook',
        	]
        	);

        NavAncestor::create(
            [
            'navbar_id' => 5,
            'navbar' => 'Ledger',
            'navigate_to' => '/accounts/ledger',
            ]
            );


/*
    ACCOUNTS ANCESTORS
*/

        // NavAncestor::create(
        //     [
        //     'navbar_id' => 3,
        //     'navbar' => 'New Receipt',
        //     'navigate_to' => '/addNewReceipt',
        //     ]
        //     );

        // NavAncestor::create(
        //     [
        //     'navbar_id' => 3,
        //     'navbar' => 'Receipts List',
        //     'navigate_to' => '/receiptsList',
        //     ]
        //     );
    }
}
