/*
	MAIN COMPONENTS
*/
import Dashboard from './components/main/Dashboard';

/*
	CHARGES COMPONENTS
*/
import charges from './components/charges/index';
/*
	RECEIPT COMPONENTS
*/
import receipt from './components/receipt/index';
/*
  USERS COMPONENTS
*/
import users from './components/people/users/index';
import businessmen from './components/people/businessmen/index';
import business from './components/people/business/index';
/*
  Accounts COMPONENTS
*/
import pettyCash from './components/accounts/pettyCash';
import cashBook from './components/accounts/cashBook';
import ledger from './components/accounts/ledger';

export const routes = [
  {
    path:'/',
    component:Dashboard,
  },
  {
    path:'/charges',
    component:charges,
    meta: { 
      requiresAuth: true
    }
  },
  {
    path:'/receipt',
    component:receipt,
  },
  {
    path:'/people/users',
    component:users,
    meta: { 
      requiresAuth: true
    }
  },
  {
    path:'/people/businessmen',
    component:businessmen,
    meta: { 
      requiresAuth: true
    }
  },
  {
    path:'/people/business',
    component:business,
    meta: { 
      requiresAuth: true
    }
  },
  {
    path:'/accounts/pettyCash',
    component:pettyCash,
    meta: { 
      requiresAuth: true
    }
  },
  {
    path:'/accounts/cashBook',
    component:cashBook,
    meta: { 
      requiresAuth: true
    }
  },
  {
    path:'/accounts/ledger',
    component:ledger,
    meta: { 
      requiresAuth: true
    }
  }


];

// export default routes