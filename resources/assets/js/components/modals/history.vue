<template>
	<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12 text-center">
		<h2>Transaction History</h2>
<div class="load-bar" v-show="loader">
  <div class="bar"></div>
  <div class="bar"></div>
  <div class="bar"></div>
</div>

<section class="panel">
            <div class="panel-body tab-content">
              <div id="access-log" class="tab-pane active" v-if="history.length > 0">
                <table class="table table-striped table-no-more table-bordered  mb-none">
                  <thead>
                    <tr>
                      <th style="width: 15%"><span class="text-weight-normal text-sm">Status</span></th>
                      <th style="width: 15%"><span class="text-weight-normal text-sm">Ref</span></th>
                      <th style="width: 15%"><span class="text-weight-normal text-sm">Date</span></th>
                      <th style="width: 15%"><span class="text-weight-normal text-sm">Business</span></th>
                      <th style="width: 15%"><span class="text-weight-normal text-sm">Amount</span></th>
                    </tr>
                  </thead>
                  <tbody class="log-viewer">
                    <template v-for="h in history">
                      
                    <tr>
                      <td data-title="Type" class="pt-md pb-md">
                      
                        <template v-if="h.invoice_status == 1">
                          
                        <i class="fa fa-check fa-fw text-success text-md va-middle"></i>
                        <span class="va-middle">Paid</span>

                        </template>

                        <template v-else>
                          
                        <i class="fa fa-times fa-fw text-danger text-md va-middle"></i>
                        <span class="va-middle">Unpaid</span>

                        </template>

                      </td>
                      <td data-title="Date" class="pt-md pb-md" >
                        <a href="javascript:void(0)" @click="invoiceDetails(h.id)">{{h.invoice_id}}</a>
                      </td>
                      <td data-title="Date" class="pt-md pb-md" v-html="h.issued_on">

                      </td>
                      <td data-title="Message" class="pt-md pb-md">
                       {{h.invoice_messer}}
                      </td>
                      <td data-title="Message" class="pt-md pb-md">
                       {{h.total}} PKR
                      </td>
                    </tr>
                    </template> 
                  </tbody>
                </table>
              </div>
              <div class="text-center" v-else><i class="fa fa-times-circle text-danger"></i><p>No History Found</p></div>
            </div>
</section>



	</div>

</template>
<script>

export default{
	props:['id'],
	name: 'history',
    data() {
      return {
        index: null,
        history:'', 
      	loader: false,
      }
    },
    methods:{
      invoiceDetails(id){

        axios.get('/invoice/'+id)
                        .then(res => this.$store.dispatch('initviewprint', res.data))

      }
    },
    mounted(){
        axios.get('payment/history/'+this.id)
              .then(res => {
                  this.history = res.data
                  this.loader = false
              }).catch(err => this.loader = false)
    }
}
	
</script>
<style>
.input-group .form-control {
    position: relative;
    z-index: 0;
    float: left;
    width: 100%;
    margin-bottom: 0;
}

.load-bar {
  position: relative;
  margin-top: 20px;
  width: 100%;
  height: 6px;
  background-color: #fdba2c;
}
.bar {
  content: "";
  display: inline;
  position: absolute;
  width: 0;
  height: 100%;
  left: 50%;
  text-align: center;
}
.bar:nth-child(1) {
  background-color: #da4733;
  animation: loading 3s linear infinite;
}
.bar:nth-child(2) {
  background-color: #3b78e7;
  animation: loading 3s linear 1s infinite;
}
.bar:nth-child(3) {
  background-color: #fdba2c;
  animation: loading 3s linear 2s infinite;
}
@keyframes loading {
    from {left: 50%; width: 0;z-index:100;}
    33.3333% {left: 0; width: 100%;z-index: 10;}
    to {left: 0; width: 100%;}
}
</style>