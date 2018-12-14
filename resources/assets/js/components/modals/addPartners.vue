<template>
	<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12 text-center">
		<h2>Add Partner</h2>
<div class="load-bar" v-show="loader">
  <div class="bar"></div>
  <div class="bar"></div>
  <div class="bar"></div>
</div>
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

  <br>

  <form class="form-group" @submit.prevent="savePartner">

            <div class="form-group" :class="{'has-error': errors.has('Designation') }">
                <div class="input-group"> <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
                    <input type="text" v-model="designation" name="Designation" v-validate="'required'" class="form-control" placeholder="Designation">
                </div>
                <span class="customError">{{ errors.first('Designation') }}</span>
            </div>

            <div class="form-group" :class="{'has-error': errors.has('Email') }">
                <div class="input-group"> <span class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></span>
                    <input type="email" v-model="email1" name="Email" ref="partnerEmail" v-validate="'email|emailUnique'" class="form-control" placeholder="Business Email">
                </div>
                <span class="customError">{{ errors.first('Email') }}</span>
            </div>

            <div class="form-group" :class="{'has-error': errors.has('Email_2') }">
                <div class="input-group"> <span class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></span>
                    <input type="email" v-model="email2" name="Email_2" v-validate="'isSame:Email|email|emailUnique'" class="form-control" placeholder="Business Email">
                </div>
                <span class="customError">{{ errors.first('Email_2') }}</span>
            </div>


  <button type="submit" class="mb-xs mt-xs mr-xs btn btn-primary btn-block">Save</button>
</form>
<br>

                
                

	</div>

</template>
<script>
import { Validator } from 'vee-validate'

export default{
	props:['id'],
	name: 'partners',
    data() {
      return {
      	loader: false,
      	email1: '',
      	email2: '',
        designation: '',
        selectedBusinessmen: '',
        options: [],
        isLoading: false,
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
    	savePartner:function (e) {
    		e.preventDefault()

	      this.$validator.validateAll().then((result) => {

							if (result) {
									this.loader = true
								axios.post('/business/partners/add', 
									{
										params: {
											'business_id' : this.id,
											'businessmen_id' : this.selectedBusinessmen.id,
                      'designation' : this.designation,
											'email1' : this.email1,
											'email2' : this.email2,
										}

									}).then( res => {

										// Unset DataVariables
											this.loader = false
											this.email1 = ''
											this.email2 = ''
                      this.designation = ''
											this.selectedBusinessmen = ''
											this.$emit('close')

										// Inform User

											this.$notify({
												group: 'success',
												type: 'success',
												title: 'Success',
												duration: 4000,
												text: 'Partner added successfully.'
											});

									}).catch(err => {
											this.loader = false

											this.$notify({
												group: 'success',
												type: 'error',
												title: 'Error',
												duration: 4000,
												text: err.response.data.error
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
</style>