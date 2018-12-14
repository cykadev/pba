<template>
<div>
<template v-if="this.$store.state.printview == false">
<section role="main" class="content-body">
<notifications group="success" />
<header class="page-header">
	<h2>{{title}}</h2>

	<div class="right-wrapper pull-right">
		<ol class="breadcrumbs">
			<li>
				<a href="">
					<i class="fa fa-home"></i>
				</a>
			</li>
			<li><span>Accounts</span></li>
			<li><span>Petty Cash</span></li>
		</ol>

		<a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>
	</div>
</header>

							<div class="col-md-12 col-xs-12 col-lg-12 col-sm-12">
					<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
						<section class="panel panel-featured-left panel-featured-primary">
							<div class="panel-body">
								<div class="widget-summary">
									<div class="widget-summary-col widget-summary-col-icon">
										<div class="summary-icon bg-primary">
											<i class="fa fa-usd"></i>
										</div>
									</div>
									<div class="widget-summary-col">
										<div class="summary">
											<h4 class="title">Total Expenses</h4>
											<div class="info">
												<strong class="amount">{{debit}} PKR</strong>
											</div>
										</div>
									</div>
								</div>
							</div>
						</section>
					</div>
					<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
						<section class="panel panel-featured-left panel-featured-primary">
							<div class="panel-body">
								<div class="widget-summary">
									<div class="widget-summary-col widget-summary-col-icon">
										<div class="summary-icon bg-primary">
											<i class="fa fa-usd"></i>
										</div>
									</div>
									<div class="widget-summary-col">
										<div class="summary">
											<h4 class="title">Total Balance</h4>
											<div class="info">
												<strong class="amount">{{credit[0]['total'] - debit}} PKR</strong>
											</div>
										</div>
									</div>
								</div>
							</div>
						</section>
					</div>
					<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
						<section class="panel panel-featured-left panel-featured-primary">
							<div class="panel-body">
								<div class="widget-summary">
									<div class="widget-summary-col widget-summary-col-icon">
										<div class="summary-icon bg-primary">
											<i class="fa fa-usd"></i>
										</div>
									</div>
									<div class="widget-summary-col">
										<div class="summary">
											<h4 class="title">Total Credit</h4>
											<div class="info">
												<strong class="amount">{{credit[0]['total']}} PKR</strong>
											</div>
										<div class="summary-footer">
											<p class="text-muted text-uppercase">Last credit date: {{credit[1]['date']}}</p>
										</div>
										</div>
									</div>
								</div>
							</div>
						</section>
					</div>
					</div>


								<div class="row">
						<div class="col-md-6" :class="(active1 == 1) ? 'panel-collapsed' : ''">
							 <form @submit.prevent="addExpenses('form_addexpense')" class="form-horizontal" data-vv-scope="form_addexpense">
								<section class="panel">
									<header class="panel-heading">

										<button type="button" class="mb-xs mt-xs mr-xs btn btn-secondary btn-lg btn-block" @click="activate()"><i class="fa fa-plus"></i> Make New Expense</button>
										<p class="panel-subtitle">
											Add <code>Daily Basis</code> expenses
										</p>
									</header>
									<div class="panel-body">

										<div class="form-group" :class="{'has-error': errors.has('form_addexpense.expensecategory') }">
											<label class="col-sm-4 control-label">Expense: </label>
											<div class="col-sm-8">
													<select v-validate="'required'" v-model="expense.category" name="expensecategory" class="form-control populate select2-hidden-accessible" tabindex="-1" aria-hidden="true">

													<template v-for="v in categories">
															<option :value="v.category">{{v.category}}</option>
													</template>

													</select>
													<span class="customError">{{ errors.first('form_addexpense.expensecategory') }}</span>
											</div>
										</div>


										<div class="form-group" :class="{'has-error': errors.has('form_addexpense.total') }">
											<label class="col-sm-4 control-label">Total: </label>
											<div class="col-sm-8">
												<input type="text" v-validate="'required|integer'" v-model="expense.total" name="total" class="form-control">
												<span class="customError">{{ errors.first('form_addexpense.total') }}</span>
											</div>
										</div>

										<div class="form-group" :class="{'has-error': errors.has('form_addexpense.description') }">
											<label class="col-sm-4 control-label">Description: </label>
											<div class="col-sm-8">
												<textarea v-validate="'required'" class="form-control" v-model="expense.description" name="description" rows="5" placeholder="Type your message"></textarea>
												<span class="customError">{{ errors.first('form_addexpense.expensecategory') }}</span>
											</div>
										</div>

									</div>
									<footer class="panel-footer">
										<button class="btn btn-primary" type="submit">Add </button>
									</footer>
								</section>
								</form>
						</div>


						<div class="col-md-6" :class="(active2 == 1) ? 'panel-collapsed' : ''">
						<form @submit.prevent="addExpenses('form_addcategory')" class="form-horizontal" data-vv-scope="form_addcategory">
								<section class="panel">
									<header class="panel-heading">

									<button type="button" class="mb-xs mt-xs mr-xs btn btn-secondary btn-lg btn-block" @click="activate2()"><i class="fa fa-plus"></i> Make New Category</button>

										<p class="panel-subtitle">
											Make <code>Daily Basis</code> category
										</p>
									</header>
									<div class="panel-body">
										<div class="form-group" :class="{'has-error': errors.has('form_addcategory.category') }">
											<label class="col-sm-4 control-label">Category:</label>
											<div class="col-sm-8">
												<input v-validate="'required|uniques'" type="text" v-model="category" name="category" class="form-control">
												<span class="customError">{{ errors.first('form_addcategory.category') }}</span>
											</div>
										</div>
									</div>
									<footer class="panel-footer">
										<button class="btn btn-primary">Add</button>
									</footer>
								</section>
							</form>
						</div>
							</div>
							
						</div>
<pettycash-table></pettycash-table>

</section>

</template>
<!-- Hide element when print popup end -->

						</div>
</template>
<script>
import { Validator } from 'vee-validate'
import axios from 'axios'
import moment from 'moment'
/*
	For Company Unique Name
*/
const isUnique = value => new Promise((resolve) => {
setTimeout(() => {
	axios.get('/pettycash/checkUniqueCategory/'+value).then(
		function(res){
		if (res.data == 0) {
			return resolve({
				valid: true
			})
		}else{
			return resolve({
		valid: false,
		data: {
			message: `${value} is already in use.`
			}
				})
		}
			
		})
}, 200)
})
Validator.extend('uniques', {
validate: isUnique,
getMessage: (field, params, data) => data.message
})

/*
	For Company Unique Name
*/

  export default {
    // Do not forget this little guy
    name: 'PettyCash',
    title: '',
    loading: '',
    data(){
    	return {
    		categories: [],
    		active1: 1,
    		active2: 1,
    		expense:{
    			category: '',
    			total: '',
    			description: ''
    		},
    		category: '',
    	}
    },

    computed: {
		title(){
    		return this.title = 'Petty Cash'
    	},
    	loading(){
    		return this.loading = this.$store.state.loading
    	},
    	credit(){
    		return this.$store.state.pettycredit
    	},
    	debit(){
    		return this.$store.state.pettydebit
    	}

    },
    methods: {
    	addExpenses(scope){
	      this.$validator.validateAll(scope).then((result) => {
	        if (result) {

	        	if (scope != 'form_addcategory') {
	        		this.addExpensesForm()
	        	}else{
	        		this.addCategoryForm()
	        	}
	        }
	      })
    	},
        setcategories(){

	    		axios.get('/pettycash/category').then(json => {
				 					this.categories = json.data
    						})
    	},
    	addExpensesForm(){
		     	axios.post('/pettycash/expense',{
		     				params: this.expense
		     		}
		     	).then(res => {

	    			if (res.data.success === false) {
	    				    this.$notify({
								group: 'success',
								type: 'error',
								duration: 4000,
								title: 'Error',
								text: res.data.error,
							});
	    			}else{


	    				/*
							Truncate Form
	    				*/
	    					this.$store.dispatch('setpettyaccounts')
	    					this.active1 = 1
				    		this.expense.category = ''
				    		this.expense.total = ''
				    		this.expense.description = ''


	    				    this.$notify({
								group: 'success',
								type: 'success',
								title: 'Success',
								duration: 4000,
								text: 'Expenses added successfully.'
							});

	    				this.$events.fire('refresh-table')
	    			}
	    		})
    	},
    	addCategoryForm(){
		     	axios.post('/pettycash/category',{
		     				params:{category:this.category}
		     		}
		     	).then(res => {

	    			if (res.data.success === false) {
	    				    this.$notify({
								group: 'success',
								type: 'error',
								duration: 4000,
								title: 'Error',
								text: res.data.error,
							});
	    			}else{

	    				this.setcategories()

	    				/*
							Truncate Form
	    				*/
	    					this.category = ''

	    				    this.$notify({
								group: 'success',
								type: 'success',
								title: 'Success',
								duration: 4000,
								text: 'Category added successfully.'
							});

							this.$events.fire('refresh-table')
	    			}
	    		})
    	},
    	activate:function(){
    		if (this.active1 == 1) {
    			return this.active1 = 0
    		}else{
    			return this.active1 = 1
    		}
    	},
    	activate2:function(){
    		if (this.active2 == 1) {
    			return this.active2 = 0
    		}else{
    			return this.active2 = 1
    		}
    	},

    },
    // component Lifecycle hooks
    beforeCreate() {
		this.$store.dispatch('setpettyaccounts')
    },
    mounted() {

	axios.get('/pettycash/category').then(json => {
				 this.categories = json.data
				 this.$store.dispatch('setpettycategories', json.data)
    		})
    },
};
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<style scoped>

.checked{
	display: none;
}
.custommargin{
	margin-bottom: 5%;
}
.custompadding{
	padding-top: 7%;
}
.multiselect__tag{
	background: #0088cc;
}
.customUl{
	list-style: none;
	margin-top: 5%;
}
.customUl li{
	margin-bottom: 3%;
}
.hvr-bounce-to-right {
	background: #e1e1e1;
    color: #666;
    cursor: pointer;
    line-height: 1.2em;
    margin: .4em;
    padding: 1em;
    text-decoration: none;
    -webkit-tap-highlight-color: rgba(0,0,0,0);
}
.on-background{
	background: #41b883 !important;
}
.bg-secondary {
    background: #41b883;
}
.panel-featured-secondary {
    border-color: #41b883;
}
.removeCharge{
	background: #f79797;
}
.testing{
	background: #d65656;
}
.customzIndex{
	z-index: 0;
}
.hideFooter{
	display: none;
}
	
</style>