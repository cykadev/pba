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
				<li><span>Ledger</span></li>
			</ol>
			<a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>
		</div>
	</header>
	<div class="col-md-12 col-xs-12 col-lg-12 col-sm-12">
		<!-- <div class="row">
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
		</div> -->
		<div class="row">
			<div class="col-md-12" :class="(active2 == 1) ? 'panel-collapsed' : ''">
				<section class="panel">
					<header class="panel-heading">
						<button type="button" class="mb-xs mt-xs mr-xs btn btn-secondary btn-lg btn-block" @click="activate2()"><i class="fa fa-search"></i> Make Ledger</button>
					</header>
					<div class="panel-body text-center">
					<div v-show="loading" class="loader pull-right" style="width:20px;height:20px;margin-left:0px;"></div>
						<date-picker v-model="date" date-format="YYYY-MM-DD" type="date" confirm lang="en" range :shortcuts="shortcuts"></date-picker>
					</div>
					<footer class="panel-footer">
					</footer>
				</section>
			</div>
		</div>
		<div class="row" v-show="show" id="printableDiv">
			<div class="col-md-12">
				<div class="invoice">
					<header class="clearfix">
						<div class="row">
							<div class="col-sm-6 mt-md">
								<h2 class="h2 mt-none mb-sm text-dark text-weight-bold">Income & Expenditure</h2>
								<h4 class="h4 m-none text-dark text-weight-bold">Date: 2018-10-14 - 2019-10-14</h4>
							</div>
							<div class="col-sm-6 text-right mt-md mb-md">
								<address class="ib mr-xlg">
									Pakistan Businessmen Association
									<br>
									D-5, 4<sup>th</sup> Floor, Osif Center Plot B-25, Block 13-A
									<br>
									University Road, Gulshan-e-Iqbal, Karachi-Pakistan
									<br>
									Phone: +92 21-34975773, 34821881
									<br>
									info@apba.org.pk, www.apba.org.pk
								</address>
								<div class="ib">
									<img src="/porto/images/logo.png" alt="PBA">
								</div>
							</div>
						</div>
					</header>
					
					
					<div class="table-responsive">
						<table class="table invoice-items">
							<thead>
								<tr class="h4 text-dark">
									<th id="cell-item" class="text-weight-semibold">CRITERIA</th>
									<th id="cell-qty" class="text-center text-weight-semibold">RECENT</th>
									<th id="cell-qty" class="text-center text-weight-semibold">CHANGE</th>
									<th id="cell-total" class="text-center text-weight-semibold">PRESENT</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
								<tr style="background-color:#0088cc;color:#fff;">
									<td class="text-weight-semibold">INCOME</td>
									<td class="text-center"></td>
									<td></td>
									<td></td>
								</tr>
								<tr>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
								<tr v-for="(v, i) in incomeLoop">
									<td class="text-weight-semibold text-dark" :keyIndex="{i}" v-if="v.category">{{v.category}}</td>
									<td class="text-weight-semibold text-dark" :keyIndex="{i}" v-else>-</td>
									
									<template v-if="ifInArray(v.category, i)">
									<td class="text-center" v-html="ifInArray(v.category, i)"></td>
									</template>
									<template v-else>
									<td class="text-center">0</td>
									</template>
									<template v-if="predictChange(v.category, v.total)">
									<td class="text-center" v-html="predictChange(v.category, v.total)"></td>
									</template>
									<template v-else>
									<td class="text-center"><i class="fa fa-caret-up" style="color:green;"></i> +100%</td>
									</template>
									<td class="text-center" :keyIndex="{i}">{{v.total}}</td>
								</tr>
								<tr>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
								<tr>
									<td class="text-weight-semibold text-dark"><b>TOTAL</b></td>
									<td class="text-center"> {{recentIncomeTotal}}</td>
									<td class="text-center" v-html="predictChangeIncome(recentIncomeTotal, presentTotal)"></td>
									<td class="text-center">{{presentTotal}}</td>
								</tr>
								<tr>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
								<tr style="background-color:#0088cc;color:#fff;">
									<td class="text-weight-semibold">EXPENDITURE</td>
									<td class="text-center"></td>
									<td></td>
									<td></td>
								</tr>
								<tr>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
								<tr v-for="v in expenditureLoop">
									<td class="text-weight-semibold text-dark" v-if="v.category">{{v.category}}</td>
									<td class="text-weight-semibold text-dark" v-else>-</td>
									<template v-if="ifInArrayExpense(v.category)">
									<td class="text-center" v-html="ifInArrayExpense(v.category)"></td>
									</template>
									<template v-else>
									<td class="text-center">0</td>
									</template>
									<template v-if="predictChangeExpense(v.category, v.total)">
									<td class="text-center" v-html="predictChangeExpense(v.category, v.total)"></td>
									</template>
									<template v-else>
									<td class="text-center"><i class="fa fa-caret-up" style="color:green;"></i> +100%</td>
									</template>
									<td class="text-center">{{v.total}}</td>
								</tr>
								<tr>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
								<tr>
									<td class="text-weight-semibold text-dark"><b>TOTAL</b></td>
									<td class="text-center"> {{recentExpenseTotal}}</td>
									<template v-if="predictChangeExpenseTotal(recentExpenseTotal, recentTotal)">
									<td class="text-center" v-html="predictChangeExpenseTotal(recentExpenseTotal, recentTotal)"></td>
									</template>
									<template v-else>
									<td class="text-center"><i class="fa fa-caret-up" style="color:green;"></i> +100%</td>
									</template>
									<td class="text-center">{{recentTotal}}</td>
								</tr>
								<tr>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
							</tbody>
						</table>
					</div>
					
					<!-- <div class="invoice-summary">
						<div class="row">
							<div class="col-sm-4 col-sm-offset-8">
								<table class="table h5 text-dark">
									<tbody>
										<tr class="b-top-none">
											<td colspan="2">Subtotal</td>
											<td class="text-left">$73.00</td>
										</tr>
										<tr>
											<td colspan="2">Shipping</td>
											<td class="text-left">$0.00</td>
										</tr>
										<tr class="h4">
											<td colspan="2">Grand Total</td>
											<td class="text-left">$73.00</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div> -->
				</div>

				<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12 signature">

				<div class="col-md-4 col-sm-4 col-xs-4 col-lg-4 text-center">
				___________________________
					Chairman
				</div>
				<div class="col-md-4 col-sm-4 col-xs-4 col-lg-4 text-center">
				___________________________
					Vice Chairman
				</div>
				<div class="col-md-4 col-sm-4 col-xs-4 col-lg-4 text-center">
				___________________________
					Secretary General
				</div>
				</div>
			</div>
		</div>
			<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12" v-show="show">
					<button @click="printDiv('printableDiv')" class="btn btn-primary pull-right">Print</button>
			</div>
		
	</div>
</section>
</template>
<!-- Hide element when print popup end -->

						</div>
</template>
<script>
import DatePicker from 'vue2-datepicker'
import axios from 'axios'
import moment from 'moment'


/*
	For Company Unique Name
*/

  export default {
    // Do not forget this little guy
    name: 'ledger',
	components: { DatePicker },
    data(){
    	return {
    		active2: 0,
    		found: [],
    		show: false,
    		loading:false,
    		incomeLoop: '',
    		expenditureLoop: '',
    		presentTotal: '',
    		recentTotal: '',
    		recentIncomeTotal: '',
    		recentExpenseTotal: '',
    		recentIncome: [],
    		recentExpense: [],
    		date:'',
			shortcuts: [
				{
					text: 'Today',
						onClick: () => {
							this.date = [ new Date(), new Date() ]
						}
				},
			],
			timePickerOptions:{
				start: '00:00',
				step: '00:30',
				end: '23:30'
			}
    	}
    },

    computed: {
		title(){
    		return 'Ledger'
    	},

    },
  watch: {
    // whenever question changes, this function will run
    date: function (val, oldval) {

    	this.show = false
    	this.loading = true

        var fmt = 'YYYY-MM-DD'
        var dat = moment(val[0], 'YYYY-MM-DD').format(fmt)
        var dat2 = moment(val[1], 'YYYY-MM-DD').format(fmt)

      axios.get('ledger?filter='+dat+'_'+dat2).then(res =>{

      		this.incomeLoop = res.data[0].income
      		this.expenditureLoop = res.data[1].expense
      		this.presentTotal = res.data[2].presentIncome
      		this.recentTotal = res.data[3].presentExpense
      		this.recentIncome = res.data[4].recentIncome
      		this.recentExpense = res.data[5].recentExpense

      		// Compute Recent Criteria Against Present
      		this.recentIncomeTotal = res.data[6].recentIncomeTotal
      		this.recentExpenseTotal = res.data[7].recentExpenseTotal

      		setTimeout( () => {
      			this.loading = false
      			this.show = true
      		},1000)
      })

    }
  },

    methods: {

    	printDiv: function (divName) {

			var printContents = document.getElementById(divName).innerHTML;
			var originalContents = document.body.innerHTML;
			document.body.innerHTML = printContents;
			window.print();
			document.body.innerHTML = originalContents;

    	},

    	ifInArray: function (value, ind) {

			for (var i = 0; i < this.recentIncome.length; i++) { 

    			if (this.recentIncome[i].category == value) {
    				return `<td class="text-center">${this.recentIncome[i].total}</td>`
    			}

			}
		},

    	ifInArrayExpense: function (value) {

			for (var i = 0; i < this.recentExpense.length; i++) { 

    			if (this.recentExpense[i].category == value) {
    				return `<td class="text-center">${this.recentExpense[i].total}</td>`
    			}

			}
		},

    	predictChange: function (value, total) {

    		// ((list price - actual price) / (list price)) * 100%

			for (var i = 0; i < this.recentIncome.length; i++) { 

    			if (this.recentIncome[i].category == value) {

    				var dbNumber = (this.recentIncome[i].total) ? this.recentIncome[i].total : 0

    				var avg = (1 - dbNumber / total) * 100

    				var num = avg.toFixed(2)

    					if (num > 0) {

    						return `<td class="text-center"><i class="fa fa-caret-up" style="color:green;"></i> +${num}%</td>`

    					}else if (num == 0) {

    						return `<td class="text-center">${num}%</td>`
    					}
    					else{

    						return `<td class="text-center"><i class="fa fa-caret-down" style="color:red;"></i> ${num}%</td>`
    					}

    			}

			}

        },

    	predictChangeExpense: function (value, total) {

    		// ((list price - actual price) / (list price)) * 100%

			for (var i = 0; i < this.recentExpense.length; i++) { 

    			if (this.recentExpense[i].category == value) {

    				var dbNumber = this.recentExpense[i].total


    				var avg = (1 - dbNumber / total) * 100

    				var num = avg.toFixed(2)

    					if (num > 0) {

    						return `<td class="text-center"><i class="fa fa-caret-up" style="color:green;"></i> +${num}%</td>`

    					}else if (num == 0) {

    						return `<td class="text-center">${num}%</td>`
    					}
    					else{

    						return `<td class="text-center"><i class="fa fa-caret-down" style="color:red;"></i> ${num}%</td>`
    					}

    			}

			}

        },

        predictChangeIncome:function( recents, total){

    				var avg = (1 - recents / total) * 100

    				var num = avg.toFixed(2)

    					if (num > 0) {

    						return `<td class="text-center"><i class="fa fa-caret-up" style="color:green;"></i> +${num}%</td>`

    					}else if (num == 0) {

    						return `<td class="text-center">${num}%</td>`
    					}
    					else{

    						return `<td class="text-center"><i class="fa fa-caret-down" style="color:red;"></i> ${num}%</td>`
    					}
        },

        predictChangeExpenseTotal:function( recents, total){

    				var avg = (1 - recents / total) * 100

    				var num = avg.toFixed(2)

    					if (num > 0) {

    						return `<td class="text-center"><i class="fa fa-caret-up" style="color:green;"></i> +${num}%</td>`

    					}else if (num == 0) {

    						return `<td class="text-center">${num}%</td>`
    					}
    					else{

    						return `<td class="text-center"><i class="fa fa-caret-down" style="color:red;"></i> ${num}%</td>`
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
    },
    mounted() {
    },
};
</script>
<style scoped>

.invoice header{
	border-bottom: none;
}
.signature{
	margin-top: 8%;
}
@media print {
	.h2{
		font-size: 22px;
	}
}  
	
</style>