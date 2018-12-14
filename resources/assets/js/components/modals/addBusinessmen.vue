<template>
	<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12 text-center">
		<h2>Assign To Businessmen</h2>
<div class="load-bar" v-show="loader">
  <div class="bar"></div>
  <div class="bar"></div>
  <div class="bar"></div>
</div>
<hr>
		<multiselect id="ajax" v-model="selectedBusinessmen" placeholder="Select Businessmen" 
  v-validate data-vv-rules="required"
  label="name" 
  data-vv-name="Businessmen"
  track-by="name" 
  :options="options" 
  :option-height="104" 
  :show-labels="true" 
  open-direction="bottom" 
  :searchable="true" 
  :loading="isLoading" 
  :internal-search="false" 
  :clear-on-select="true" 
  :options-limit="300" 
  :limit="3" 
  :max-height="600" 
  @search-change="asyncFind">
    <template slot="singleLabel" slot-scope="props">
      <div class="row">
      <div class="col-xs-3 col-sm-3">
      <img class="option__image" :src="props.option.image" width="80" height="80">
      </div>
      <div class="col-xs-9 col-sm-9">
      <p class="option__title">NAME :{{ props.option.name }}</p>
      <p>BMR :{{ props.option.bmr_number }}</p>
      </div>
      </div>
    </template>
    <template slot="option" slot-scope="props">
      <div class="row">
      <div class="col-xs-3 col-sm-3">
      <img class="option__image" :src="props.option.image" width="80" height="80">
      </div>
      <div class="col-xs-9 col-sm-9">
      <p class="option__title">NAME :{{ props.option.name }}</p>
      <p>BMR :{{ props.option.bmr_number }}</p>
      </div>
      </div>
    </template>
  </multiselect>
  <span class="customError">{{ errors.first('Businessmen') }}</span>
  <hr>

                            <div class="form-group" v-show="business != null" :class="{'has-error': errors.has('Member') }">
                            <label class="col-sm-3 control-label">Business</label>
            
                            <div class="col-sm-9">

                              <template v-for="b in business">
                                
                              <div class="radio-custom pull-left">
                                <input type="radio" v-model="business_id" checked v-validate="'required'" key="business_id" name="business_id" :value="b.id">
                                <label for="radioExample1"><span class="label label-primary">{{b.br_number}}</span> {{b.name}}</label>
                              </div>
            
                              </template>
                            </div>
                          </div>


  <hr>
  <button type="button" class="mb-xs mt-xs mr-xs btn btn-primary btn-block" @click="assignTo()">Save</button>

	</div>

</template>
<script>
import { Validator } from 'vee-validate'

export default{
	props:['id'],
	name: 'assigntobusinessmen',
    data() {
      return {
      	loader: false,
        business: null,
        business_id: '',
        selectedBusinessmen: '',
        options: [],
        isLoading: false,
      }
    },
    watch:{
      selectedBusinessmen : function (val) {

        if (val != null) {
            this.loader = true
            axios.get('/businessmen/business/'+val.id)
                  .then(res => {
                    this.business = null
                    this.business = res.data
                    this.loader = false
                  })
        }else{
          this.business = null
        }
      }
    },
    methods:{
    	asyncFind(query){

        if (query.length > 2) {

          // this.isLoading = true
          this.loader = true
        axios.get('/businessmen/asyncsearchAdd?q='+query)
           .then(res => {
            this.options = res.data
            // this.isLoading = false
            this.loader = false
           })

        }

    	},
    	assignTo:function () {

	      this.$validator.validateAll().then((result) => {

							if (result) {
									this.loader = true
								axios.post('/invoice/assign', 
									{
										'params': {
											'invoice_id' : this.id,
                      'businessmen_id' : this.selectedBusinessmen.id,
											'business_id' : this.business_id,
										}

									}).then( res => {

										// Unset DataVariables
											this.loader = false
											this.selectedBusinessmen = ''
											this.$emit('close')
                      this.$events.fire('filter-reset')

										// Inform User

											this.$notify({
												group: 'success',
												type: 'success',
												title: 'Success',
												duration: 4000,
												text: 'Invoice Assigned successfully.'
											});

									}).catch(err => {
											this.loader = false

											this.$notify({
												group: 'success',
												type: 'error',
												title: 'Error',
												duration: 4000,
												text: err.response.data.message
											});
									})
							}
    		})

    	},
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