<template>
	<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12 text-center">
  <form @submit.prevent="renewBusinessmen()">
 <section class="panel">
                  <header class="panel-heading">            
                    <h2 class="panel-title">Membership Renewal</h2>
                  </header> 
   <div class="panel-body">

<div class="row alert-danger" v-if="duration > 0">
 <p>{{penaltyCount}} - Expired From: {{expiry}}</p>
</div>

<div class="row alert-success" v-else>
 <p>No Penalty</p>
</div>


  <multiselect 
  class="custompadding" 
  data-vv-name="title"
  v-model="value" 
  :options="charges" 
  @select="totalPush" 
  @remove="removedItem" 
  :multiple="true" 
  :close-on-select="true" 
  :clear-on-select="true" 
  :hide-selected="true" 
  :preserve-search="true" 
  placeholder="Pick Charges" 
  label="title" 
  track-by="title" 
  :preselect-first="true" >

      <template slot="option" slot-scope="props"><h4>{{ props.option.title }}</h4>
      <div class="option__desc"><span class="option__title">{{ props.option.price }}</span><span class="option__small">
    <template v-if="props.option.category == 1">
          {{ ' - Membership' }} 
    </template>
    <template v-if="props.option.category == 2">
          {{ ' - Recommendation' }} 
    </template>
    <template v-if="props.option.category == 3">
          {{ ' - Miscellaneous' }}  
    </template>
    <template v-if="props.option.category == 4">
          {{ ' - Penalty' }}  
    </template>

      </span></div>
    </template>
  </multiselect>
  </div>
  </section>

<section class="panel">
                  <header class="panel-heading" :class="{'customBorder': errors.has('Business') }">            
                    <h2 class="panel-title">Select Business</h2>
                  </header>
                  <div class="panel-body">
                    <div class="row">

                        <div class="form-group" v-if="business != null">
                          <label class="col-sm-3 control-label">Business</label>

                          <div class="col-sm-9">

                            <template v-for="b in business">
                              
                            <div class="radio-custom pull-left">
                              <input type="radio" v-model="params.business_id" checked v-validate="'required'" key="business_id" name="Business" :value="b.id">
                              <label for="radioExample1"><span class="label label-primary">{{b.br_number}}</span> {{b.name}}</label>
                            </div>

                            </template>
                          </div>
                        </div>
                          </div>
                          </div>

</section>

<section class="panel">
                  <header class="panel-heading" :class="{'customBorder': errors.has('Check') }">            
                    <h2 class="panel-title">Payment Method</h2>
                  </header>
                  <div class="row">
                      <div class="load-bar" v-show="loader">
                      <div class="bar"></div>
                      <div class="bar"></div>
                      <div class="bar"></div>
                      </div>
                  </div>
                  <div class="panel-body">
                    <div class="row">
                      
                      <div class="col-md-12">
                          <div class="form-group">
            
                              <div class="radio-custom radio-inline radio-success">
                                <input type="radio" v-validate="'required'" value="challan" v-model="params.Mode" name="payment">
                              <label for="radioExample3">Bank Challan</label>
                              </div>
                              <div class="radio-custom radio-inline radio-success">
                                <input type="radio" value="receipt" v-model="params.Mode" name="payment">
                              <label for="radioExample3">Online Transaction</label>
                              </div>
                              <div class="radio-custom radio-inline radio-success">
                                <input type="radio" value="cheque" v-model="params.Mode" name="payment">
                              <label for="radioExample3">Cheque Payment</label>
                              </div>
                          </div>


            
                          <div class="form-group">

                          <label class="col-sm-4 control-label" for="w1-username">Custom Field 1:</label>

                            <div class="col-sm-8">

                            <div class="col-sm-6" :class="{'has-error': errors.has('cf1name') }">
                              <input type="text" v-validate="''" placeholder="Custom Field Title" class="form-control input-sm" name="cf1name" v-model="params.cf1name">
                            <span class="customError">{{ errors.first('cf1name') }}</span>
                            </div>

                            <div class="col-sm-6" :class="{'has-error': errors.has('cf1value') }">
                              <input type="text" v-validate="'integer'" placeholder="Custom Field Value" class="form-control input-sm" name="cf1value" v-model="params.cf1v">
                            <span class="customError">{{ errors.first('cf1value') }}</span>
                            </div>

                            </div>

                          </div>

                          <div class="form-group">

                          <label class="col-sm-4 control-label" for="w1-username">Custom Field 2:</label>

                            <div class="col-sm-8">

                            <div class="col-sm-6" :class="{'has-error': errors.has('cf1name2') }">
                              <input type="text" v-validate="'alpha_spaces'" placeholder="Custom Field Title" class="form-control input-sm" name="cf1name2" v-model="params.cf1name2">
                            <span class="customError">{{ errors.first('cf1name2') }}</span>
                            </div>

                            <div class="col-sm-6" :class="{'has-error': errors.has('cf1value2') }">
                              <input type="text" v-validate="'integer'" placeholder="Custom Field Value" class="form-control input-sm" name="cf1value2" v-model="params.cf1v2">
                            <span class="customError">{{ errors.first('cf1value2') }}</span>
                            </div>

                            </div>

                          </div>

                          <div class="form-group">

                          <label class="col-sm-4 control-label" for="w1-username">Custom Field 3:</label>

                            <div class="col-sm-8">

                            <div class="col-sm-6" :class="{'has-error': errors.has('cf1name3') }">
                              <input type="text" v-validate="'alpha_spaces'" placeholder="Custom Field Title" class="form-control input-sm" name="cf1name3" v-model="params.cf1name3">
                            <span class="customError">{{ errors.first('cf1name3') }}</span>
                            </div>

                            <div class="col-sm-6" :class="{'has-error': errors.has('cf1value3') }">
                              <input type="text" v-validate="'integer'" placeholder="Custom Field Value" class="form-control input-sm" name="cf1value3" v-model="params.cf1v3">
                            <span class="customError">{{ errors.first('cf1value3') }}</span>
                            </div>

                            </div>

                          </div>

                          <div class="form-group">

                          <label class="col-sm-4 control-label" for="w1-username">Custom Field 4:</label>

                            <div class="col-sm-8">

                            <div class="col-sm-6" :class="{'has-error': errors.has('cf1name4') }">
                              <input type="text" v-validate="'alpha_spaces'" placeholder="Custom Field Title" class="form-control input-sm" name="cf1name4" v-model="params.cf1name4">
                            <span class="customError">{{ errors.first('cf1name4') }}</span>
                            </div>

                            <div class="col-sm-6" :class="{'has-error': errors.has('cf1value4') }">
                              <input type="text" v-validate="'integer'" placeholder="Custom Field Value" class="form-control input-sm" name="cf1value4" v-model="params.cf1v4">
                            <span class="customError">{{ errors.first('cf1value4') }}</span>
                            </div>

                            </div>

                          </div>


                          <div class="form-group">

                          <label class="col-sm-4 control-label" for="w1-username">Custom Field 5:</label>

                            <div class="col-sm-8">

                            <div class="col-sm-6" :class="{'has-error': errors.has('cf1name5') }">
                              <input type="text" v-validate="'alpha_spaces'" placeholder="Custom Field Title" class="form-control input-sm" name="cf1name5" v-model="params.cf1name5">
                            <span class="customError">{{ errors.first('cf1name5') }}</span>
                            </div>

                            <div class="col-sm-6" :class="{'has-error': errors.has('cf1value5') }">
                              <input type="text" v-validate="'integer'" placeholder="Custom Field Value" class="form-control input-sm" name="cf1value5" v-model="params.cf1v5">
                            <span class="customError">{{ errors.first('cf1value5') }}</span>
                            </div>

                            </div>

                          </div>

                          <template v-if="(params.Mode == 'receipt' || params.Mode == 'cheque')">
                            
                          <div class="form-group" :class="{'has-error': errors.has('Cheque') }">
                            <label class="col-sm-4 control-label" for="w1-username">Transaction / Cheque #:</label>
                            <div class="col-sm-8">
                              <input type="text" v-validate="'required'" placeholder="Bank Transaction #" class="form-control input-sm" name="Cheque" v-model="params.check">
                            <span class="customError">{{ errors.first('Cheque') }}</span>
                            </div>
                          </div>

                          <div class="form-group" :class="{'has-error': errors.has('Branch') }" >
                            <label class="col-sm-4 control-label" for="w1-username">Branch Name:</label>
                            <div class="col-sm-8">
                              <input type="text" v-validate="'required|alpha_spaces'" placeholder="Branch Name" class="form-control input-sm" name="Branch" v-model="params.branch">
                            <span class="customError">{{ errors.first('Branch') }}</span>
                            </div>
                          </div>

                          </template>
                          <div class="form-group">
                            <label class="col-sm-4 control-label" for="w1-username">Instructions:</label>
                            <div class="col-sm-8">
                              <textarea cols="56" rows="4" v-model="params.Instructions"></textarea>
                            </div>
                          </div>

                          <div class="form-group">
                            <div class="col-sm-12">
                            <button type="button" class="mb-xs mt-xs mr-xs btn btn-primary btn-block" @click="renewBusinessmen()">Save</button>
                            </div>
                          </div>



                      </div>
                    </div>
                  </div>
                </section>
                </form>


	</div>

</template>
<script>
import { Validator } from 'vee-validate'

export default{
	props:['id', 'duration', 'expiry'],
	name: 'renewal',
    data() {
      return {
        d: new Date(),
        currentdate: '',
        params: {
          penalty: 0,
          total : 0,
          Instructions: 'Additional Notes For Member Here'
        },
        admissions : {},
        chargesTotal : 0,
        admissionsHelper: [],
      	loader: false,
        value: null,
        charges: [],
        business: null,
        options: [],
        isLoading: false,
        monthnames: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
        dayformat: ["","1<sup>st</sup>", "2<sup>nd</sup>", "3rd", "4<sup>th</sup>", "5<sup>th</sup>", "6<sup>th</sup>","7<sup>th</sup>", "8<sup>th</sup>", "9<sup>th</sup>", "10<sup>th</sup>", "11<sup>th</sup>", "12<sup>th</sup>", "13<sup>th</sup>", "14<sup>th</sup>", "15<sup>th</sup>", "16<sup>th</sup>", "17<sup>th</sup>", "18<sup>th</sup>", "19<sup>th</sup>", "20<sup>th</sup>", "21<sup>st</sup>","22<sup>nd</sup>","23rd", "24<sup>th</sup>", "25<sup>th</sup>", "26<sup>th</sup>", "27<sup>th</sup>", "28<sup>th</sup>", "29<sup>th</sup>", "30<sup>th</sup>", "31<sup>st</sup>"],
      }
    },
    // watch:{
    //   selectedBusinessmen : function (val) {

    //     if (val != null) {
    //         this.loader = true
    //         axios.get('/businessmen/business/'+val.id)
    //               .then(res => {
    //                 this.business = null
    //                 this.business = res.data
    //                 this.loader = false
    //               })
    //     }else{
    //       this.business = null
    //     }
    //   }
    // },
    computed:{

      today(){
      return this.currentdate = new Date()
      },
      getmonth(){
      return this.monthnames[this.d.getMonth()]+','
      },
      getday(){
      return ["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"][(new Date()).getDay()]
      },
      getdate(){
      return this.dayformat[this.d.getDate()]
      },
      getyear(){
      return this.d.getFullYear()
      },


        cf1vChanged(){

        // var num = parseInt(this.params.cf1v) || 0
        // var num2 = parseInt(this.params.cf1v2) || 0
        // var num3 = parseInt(this.params.cf1v3) || 0
        // var num4 = parseInt(this.params.cf1v4) || 0
        // var num5 = parseInt(this.params.cf1v5) || 0

          
        // if (num > 0 || num2 > 0 || num3 > 0 || num4 > 0 || num4 > 0) {

        //     if (this.params.total >= num + num2 + num3 + num4 + num5 + this.params.penalty) {
        //               return this.params.total = num + num2 + num3 + num4 + num5
        //     }else{
        //               return this.params.total += num + num2 + num3 + num4 + num5
        //     }

        // }

      },
      penaltyCount(){

        this.params.penalty = this.calculatePenalty() * this.duration

        if (this.duration > 0) {
            this.params.cf1name = `Penalty for ${this.duration} month(s)`
            this.params.cf1v = this.calculatePenalty() * this.duration
        }

        return `Penalty ${this.calculatePenalty()} x ${this.duration} month(s) = ${this.calculatePenalty() * this.duration}`
      }
    },
    methods:{

      removedItem(id){

        if (id.admission != '') {

                  if (this.admissions.hasOwnProperty(id.title)) {

                                    var admission = id.admission.replace(/[^0-9]/g,'')
                                    var newcharge = id.price.replace(/[^0-9]/g,'')
                                    var treasure = parseInt(this.chargesTotal) || 0

                                    var num = parseInt(newcharge) || 0
                                    var num2 = parseInt(admission) || 0

                                    var total = num + num2

                                    this.chargesTotal -= total


                                    Vue.delete(this.admissions, id.title)
                                    let index = this.admissionsHelper.indexOf(id.title)
                                                this.admissionsHelper.splice(index, 1);
                                    // this.admissions.splice(id.title, 1);    

                    }else{

                                  var newcharge = id.price.replace(/[^0-9]/g,'')
                                  var treasure = parseInt(this.chargesTotal) || 0

                                  var num = parseInt(newcharge) || 0

                                  this.chargesTotal -= num
                }


        }else{

                  var newcharge = id.price.replace(/[^0-9]/g,'')
                  var treasure = parseInt(this.chargesTotal) || 0

                  var num = parseInt(newcharge) || 0

                  this.chargesTotal -= num


        }

      },

      totalPush(id){


        if (id.admission != '') {

            swal({
              title: "Additional Charges",
              text: "This item has additional of "+id.admission+" do you want to add them?",
              icon: "success",
              buttons: ["No", "Yes"],
              dangerMode: false,
            }).then((done) => {

              if (done) {

                  var admission = id.admission.replace(/[^0-9]/g,'')
                  var newcharge = id.price.replace(/[^0-9]/g,'')

                  var num = parseInt(newcharge) || 0
                  var num2 = parseInt(admission) || 0

                  var total = num + num2

                      if (this.chargesTotal > 0) {
                            this.chargesTotal += total
                      }else{
                            this.chargesTotal = total
                      }

                        Vue.set(this.admissions, id.title)
                        this.admissionsHelper.push(id.title);
                     // this.admissions.push(id.title)

              }else{

                    var newcharge = id.price.replace(/[^0-9]/g,'')

                    var num = parseInt(newcharge) || 0

                    this.chargesTotal += num

              }


            })

        }else{

                    var newcharge = id.price.replace(/[^0-9]/g,'')

                    var num = parseInt(newcharge) || 0

                    this.chargesTotal += num

              }

      },

      calculatePenalty(){

          for (var i = 0; i < this.charges.length; i++) {
                  
                  if (this.charges[i].category == 4) {
                     return this.charges[i].price.split(" ")[0]
                  }
          }

      },

    	renewBusinessmen:function () {

	      this.$validator.validateAll().then((result) => {

							if (result) {
									this.loader = true

                /*
                  Calculate before submitting form
                */

                var num = parseInt(this.params.cf1v) || 0
                var num2 = parseInt(this.params.cf1v2) || 0
                var num3 = parseInt(this.params.cf1v3) || 0
                var num4 = parseInt(this.params.cf1v4) || 0
                var num5 = parseInt(this.params.cf1v5) || 0

                  
                if (num > 0 || num2 > 0 || num3 > 0 || num4 > 0 || num4 > 0) {
                    var total = num + num2 + num3 + num4 + num5 + this.chargesTotal
                    this.params.total = total
                }else{
                    this.params.total += this.chargesTotal
                }


                /*
                    Convert total to alpha
                */

                  this.params.total_in_words = this.getTotalInWords(this.params.total)

                  this.params.issued_on = this.getday+' '+this.getdate+' '+this.getmonth+' '+this.getyear


                  this.params.Values = this.value
                  this.params.Admission = this.admissionsHelper


								axios.post('/membership/renewal/'+this.id, 
									{
										'params': this.params,

									}).then( res => {

                      this.currentdate = '',
                      this.params = {},
                      this.admissions = {},
                      this.chargesTotal = 0,
                      this.admissionsHelper = [],
                      this.loader = false,
                      this.value = null,
                      this.charges = [],
                      this.business = null,
                      this.options = [],

                      this.$emit('close')


              this.$notify({
                group: 'success',
                type: 'success',
                duration: 4000,
                title: 'Success',
                text: 'Renewal invoice generated Invoice ID: '+ res.data,
              })


									}).catch(err => {

									})
							}else{
                console.log(this.$validator.errors)
              }
    		})

    	},

      getTotalInWords(s){
          var th = ['', 'thousand', 'million', 'billion', 'trillion']
          var dg = ['zero', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine']
          var tn = ['ten', 'eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen']
          var tw = ['twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety']
          s = s.toString();
          s = s.replace(/[\, ]/g, '');
          if (s != parseFloat(s)) return 'not a number';
          var x = s.indexOf('.');
          if (x == -1) x = s.length;
          if (x > 15) return 'too big';
          var n = s.split('');
          var str = '';
          var sk = 0;
          for (var i = 0; i < x; i++) {
          if ((x - i) % 3 == 2) {
          if (n[i] == '1') {
          str += tn[Number(n[i + 1])] + ' ';
          i++;
          sk = 1;
          } else if (n[i] != 0) {
          str += tw[n[i] - 2] + ' ';
          sk = 1;
          }
          } else if (n[i] != 0) {
          str += dg[n[i]] + ' ';
          if ((x - i) % 3 == 0) str += 'hundred ';
          sk = 1;
          }
          if ((x - i) % 3 == 1) {
          if (sk) str += th[(x - i - 1) / 3] + ' ';
          sk = 0;
          }
          }
          if (x != s.length) {
          var y = s.length;
          str += 'point ';
          for (var i = x + 1; i < y; i++) str += dg[n[i]] + ' ';
          }
          return str.replace(/\s+/g, ' ');
      },

    },
    mounted(){
            axios.get('/charges/charges')
        .then(json => {
         this.charges = json.data
        })

        this.calculatePenalty()

        axios.get('/businessmen/business/'+this.id)
              .then(res => {
                this.business = null
                this.business = res.data
        })


    }
}
	
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
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
.customBorder{
  border-bottom: 2px solid red;
}

    .v--modal-overlay {
    position: fixed;
    box-sizing: border-box;
    left: 0;
    top: 0;
    width: 100%;
    height: 100vh;
    background: rgba(0, 0, 0, 0.6);
    z-index: 1021;
    opacity: 1;
    }
</style>