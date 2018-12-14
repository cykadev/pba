<template>
<div>
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
			<li><span>People</span></li>
			<li><span>Businessmen</span></li>
		</ol>

		<a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>
	</div>
</header>
<section class="panel">
<section class="panel" :class="(active == 1) ? 'panel-collapsed' : ''">
					
								<header class="panel-heading">
										<button type="button" class="mb-xs mt-xs mr-xs btn btn-secondary btn-lg btn-block" @click="activate()"><i class="fa fa-plus"></i>
										Register New</button>
									</header>
									<div class="panel-body">
									<form class="form-horizontal" @submit.prevent="saveMember">
								
									<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">

											<div class="form-group" :class="{'has-error': errors.has('membership') }">
											<div class="col-md-6 col-sm-6 col-xs-12 col-lg-6">
											<label class="col-sm-3 control-label">Membership <span class="required" aria-required="true">*</span></label>
											<div class="col-sm-9">
												<div class="radio-custom radio-primary">
													<input v-validate="'required'" name="membership" type="radio" value="General" v-model="memberData.membership">
													<label for="awesome">General (Associate Class)</label>
												</div>
												<div class="radio-custom radio-primary">
													<input name="membership" type="radio" value="Regular" v-model="memberData.membership">
													<label for="awesome">Regular (Associate Class)</label>
												</div>
												<div class="radio-custom radio-primary">
													<input name="membership" type="radio" value="Executive" v-model="memberData.membership">
													<label for="awesome">Executive (Corporate Class)</label>
												</div>
												<div v-if="ExecutiveMember">
												<div class="radio-custom radio-primary radio-inline">
													<input key="executiveclass" v-validate="'required'" name="executiveclass" type="radio" value="Super" v-model="memberData.executiveclass">
													<label for="awesome">Super</label>
												</div>
												<div class="radio-custom radio-primary radio-inline">
													<input key="executiveclass" name="executiveclass" type="radio" value="Superior" v-model="memberData.executiveclass">
													<label for="awesome">Superior</label>
												</div>
												<div class="radio-custom radio-primary radio-inline">
													<input key="executiveclass" name="executiveclass" type="radio" value="Dynamic" v-model="memberData.executiveclass">
													<label for="awesome">Dynamic</label>
												</div>
												<div class="radio-custom radio-primary radio-inline">
													<input key="executiveclass" name="executiveclass" type="radio" value="Tycoon" v-model="memberData.executiveclass">
													<label for="awesome">Tycoon</label>
												</div>
												</div>
												<span class="customError">{{ errors.first('executiveclass') }}</span>
												<label class="error" for="membership"></label>
											</div>
											</div>

											<div class="col-md-6 col-sm-6 col-xs-12 col-lg-6">
											<dropzone v-on:vdropzone-sending="addMember()" ref="myVueDropzone" id="dropzone" :options="dropzoneOptions"></dropzone>
											</div>
											<br>
										</div>

									</div>

									<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
									<div class="col-md-6 col-sm-6 col-xs-12 col-lg-6">

											<div class="form-group" :class="{'has-error': errors.has('Name') }">
												<div class="col-md-12">
													<div class="input-group">
														<span class="input-group-addon btn-primary">Name</span>
														<input v-validate="'required|min:3|alpha_spaces'" type="text" name="Name" class="form-control" placeholder="Name" v-model="memberData.name">
													</div>
												<span class="customError">{{ errors.first('Name') }}</span>
												</div>
											</div>

										<div class="form-group" :class="{'has-error': errors.has('gender') }">
											<label class="col-sm-3 control-label">Gender <span class="required" aria-required="true">*</span></label>
											<div class="col-sm-9">
												<div class="radio-custom radio-primary radio-inline">
													<input v-validate="'required'" name="gender" type="radio" value="Male" v-model="memberData.gender">
													<label for="awesome">Male</label>
												</div>
												<div class="radio-custom radio-primary radio-inline">
													<input name="gender" type="radio" value="Female" v-model="memberData.gender">
													<label for="awesome">Female</label>
												</div>
											</div>
											</div>


											<div class="form-group" :class="{'has-error': errors.has('Father') }">
												<div class="col-md-12">
													<div class="input-group">
														<span class="input-group-addon btn-primary">Father's name</span>
														<input v-validate="'required|min:3|alpha_spaces'" type="text" name="Father" class="form-control" placeholder="Father's name" v-model="memberData.father_name">
													</div>
												<span class="customError">{{ errors.first('Father') }}</span>
												</div>
											</div>


											<div class="form-group" :class="{'has-error': errors.has('Phone') }">
												<div class="col-md-12">
													<div class="input-group">
														<span class="input-group-addon btn-primary">Phone Number</span>
														<input v-validate="'required|phone|min:9|max:11|numeric'" type="text" name="Phone" class="form-control" placeholder="Phone" v-model="memberData.phone">
													</div>
												<span class="customError">{{ errors.first('Phone') }}</span>
												</div>
											</div>


											<div class="form-group" :class="{'has-error': errors.has('Mobile') }">
												<div class="col-md-12">
													<div class="input-group">
														<span class="input-group-addon btn-primary">Mobile</span>
														<input v-mask="'###########'" ref="Mobile" v-validate="'required|mobileunique|min:11|max:11'" type="text" name="Mobile" class="form-control" placeholder="Mobile" v-model="memberData.mobile_number">
													</div>
												<span class="customError">{{ errors.first('Mobile') }}</span>
												</div>
											</div>

											<div class="form-group" :class="{'has-error': errors.has('Mobile2') }">
												<div class="col-md-12">
													<div class="input-group">
														<span class="input-group-addon btn-primary">Mobile2</span>
														<input v-mask="'###########'" ref="Mobile2" v-validate="'isSame:Mobile|min:11|max:11|mobileunique'" type="text" name="Mobile2" class="form-control" placeholder="Mobile2" v-model="memberData.mobile_number2">
													</div>
												<span class="customError">{{ errors.first('Mobile2') }}</span>
												</div>
											</div>


											<div class="form-group" :class="{'has-error': errors.has('Emergency') }">
												<div class="col-md-12">
													<div class="input-group">
														<span class="input-group-addon btn-primary">Emergency</span>
														<input v-validate="'isSame:Mobile|required|min:11|max:11|numeric'" type="text" name="Emergency" class="form-control" placeholder="Emergency Number" v-model="memberData.emergency_number">
													</div>
												<span class="customError">{{ errors.first('Emergency') }}</span>
												</div>
											</div>

											<div class="form-group" :class="{'has-error': errors.has('Relation') }">
												<div class="col-md-12">
													<div class="input-group">
														<span class="input-group-addon btn-primary">Relation</span>
														<input v-validate="'required|alpha_spaces'" type="text" name="Relation" class="form-control" placeholder="Relation" v-model="memberData.relation">
													</div>
												<span class="customError">{{ errors.first('Relation') }}</span>
												</div>
											</div>


											<div class="form-group" :class="{'has-error': errors.has('Email') }">
												<div class="col-md-12">
													<div class="input-group">
														<span class="input-group-addon btn-primary">Email</span>
														<input v-validate="'required|uemail|email'" type="text" name="Email" class="form-control" placeholder="Email" v-model="memberData.email">
													</div>
												<span class="customError">{{ errors.first('Email') }}</span>
												</div>
											</div>

											<div class="form-group" :class="{'has-error': errors.has('Address') }">
												<div class="col-md-12">
													<div class="input-group">
														<span class="input-group-addon btn-primary">Address</span>
														<input v-validate="'required|min:10'" type="text" name="Address" class="form-control" placeholder="Address" v-model="memberData.address">
													</div>
												<span class="customError">{{ errors.first('Address') }}</span>
												</div>
											</div>

									</div>
									<div class="col-md-6 col-sm-6 col-xs-12 col-lg-6">

											<div class="form-group" :class="{'has-error': errors.has('PostalAddress') }">
												<div class="col-md-12">
													<div class="input-group">
														<span class="input-group-addon btn-primary">Postal</span>
														<input v-validate="'required'" type="text" name="PostalAddress" class="form-control" placeholder="Postal Code" v-model="memberData.postal">
													</div>
												<span class="customError">{{ errors.first('PostalAddress') }}</span>
												</div>
											</div>

											<div class="form-group" :class="{'has-error': errors.has('NTN') }">
												<div class="col-md-12">
													<div class="input-group">
														<span class="input-group-addon btn-primary">NT Number</span>
														<input v-mask="'#######-#'" v-validate="'required|untn|min:9|max:9'" type="text" name="NTN" class="form-control" placeholder="NT Number" v-model="memberData.ntn">
													</div>
												<span class="customError">{{ errors.first('NTN') }}</span>
												</div>
											</div>

											<div class="form-group" :class="{'has-error': errors.has('Nationality') }">
											<label class="col-sm-3 control-label">Nationality <span class="required" aria-required="true">*</span></label>
											<div class="col-sm-9">
												<div class="radio-custom radio-primary radio-inline">
													<input v-validate="'required'" name="Nationality" type="radio" value="Single" v-model="memberData.nationality">
													<label for="awesome">Single</label>
												</div>
												<div class="radio-custom radio-primary radio-inline">
													<input name="Nationality" type="radio" value="Dual" v-model="memberData.nationality">
													<label for="awesome">Dual</label>
												</div>
											</div>
											</div>


								<template v-if="!overseasCheck">
									
											<div class="form-group" :class="{'has-error': errors.has('pakistanCNIC') }">
												<div class="col-md-12">
													<div class="input-group">
														<span class="input-group-addon btn-primary">Pakistan Based CNIC</span>
														<input key="pakistanCNIC" v-mask="'#####-#######-#'" v-validate="'required|uniquecnic|min:15'" type="text" name="pakistanCNIC" class="form-control" placeholder="00000-0000000-0" v-model="memberData.cnic">
													</div>
												<span class="customError">{{ errors.first('pakistanCNIC') }}</span>
												</div>
											</div>

								</template>

								<template v-else>
												<div class="form-group" v-show="overseasCheck" :class="{'has-error': errors.has('overseasCNIC') }">
												<div class="col-md-12">
													<div class="input-group">

														<span class="input-group-addon btn-primary">Overseas CNIC</span>
														<input key="overseasCNIC" v-mask="'######-######-#'" v-validate="'required|uniquecnic|min:15'" type="text" name="overseasCNIC" class="form-control" placeholder="000000-000000-0" v-model="memberData.overseas_cnic">

													</div>
												<span class="customError">{{ errors.first('overseasCNIC') }}</span>
												</div>
											</div>

											<div class="form-group" v-show="overseasCheck" :class="{'has-error': errors.has('StayCountry') }">
												<div class="col-md-12">
													<div class="input-group">
														<span class="input-group-addon btn-primary">Stay Country</span>
														<input key="StayCountry" v-validate="'required|alpha_spaces|min:3'" type="text" name="StayCountry" class="form-control" placeholder="Stay Country" v-model="memberData.overseas_country">
													</div>
												<span class="customError">{{ errors.first('StayCountry') }}</span>
												</div>
											</div>
								</template>


											<div class="form-group" :class="{'has-error': errors.has('DOB') }">
												<div class="col-md-12">
													<div class="input-group">
														<span class="input-group-addon btn-primary">DOB</span>
														<input v-mask="'##-##-####'" v-validate="'required'" type="text" name="DOB" class="form-control" placeholder="DOB" v-model="memberData.dob">
													</div>
												<span class="customError">{{ errors.first('DOB') }}</span>
												</div>
											</div>


										<div class="form-group" :class="{'has-error': errors.has('province') }">
											<label class="col-sm-3 control-label">Province <span class="required" aria-required="true">*</span></label>
											<div class="col-sm-9">
												<div class="radio-custom radio-primary radio-inline">
													<input v-validate="'required'" name="province" type="radio" value="Sindh" v-model="memberData.province">
													<label for="awesome">Sindh</label>
												</div>
												<div class="radio-custom radio-primary radio-inline">
													<input name="province" type="radio" value="Punjab" v-model="memberData.province">
													<label for="awesome">Punjab</label>
												</div>
												<div class="radio-custom radio-primary radio-inline">
													<input name="province" type="radio" value="Balochistan" v-model="memberData.province">
													<label for="awesome">Balochistan</label>
												</div>
												<div class="radio-custom radio-primary radio-inline">
													<input name="province" type="radio" value="KPK" v-model="memberData.province">
													<label for="awesome">KPK</label>
												</div>
												<div class="radio-custom radio-primary radio-inline">
													<input name="province" type="radio" value="Gilgit" v-model="memberData.province">
													<label for="awesome">Gilgit</label>
												</div>
											</div>
											</div>

											<div class="form-group" :class="{'has-error': errors.has('City') }">
												<div class="col-md-12">
													<div class="input-group">
														<span class="input-group-addon btn-primary">City</span>
														<input v-validate="'required|alpha_spaces|min:3'" type="text" name="City" class="form-control" placeholder="City" v-model="memberData.city">
													</div>
												<span class="customError">{{ errors.first('City') }}</span>
												</div>
											</div>

											<div class="form-group" :class="{'has-error': errors.has('Passport') }">
												<div class="col-md-12">
													<div class="input-group">
														<span class="input-group-addon btn-primary">Passport Number</span>
														<input v-validate="'required|min:9|passport'" type="text" name="Passport" class="form-control" placeholder="Passport Number" v-model="memberData.passport_number">
													</div>
												<span class="customError">{{ errors.first('Passport') }}</span>
												</div>
											</div>

											<div class="form-group" :class="{'has-error': errors.has('IssueDate') }">

												<div class="col-md-6">
													<div class="input-group">
														<span class="input-group-addon btn-primary">Issue Date</span>
														<input key="IssueDate" v-mask="'##-##-####'" v-validate="'required'" type="text" name="IssueDate" class="form-control" placeholder="Issue Date" v-model="memberData.issue_date">
													</div>
												<span class="customError">{{ errors.first('IssueDate') }}</span>
												</div>

												<div class="col-md-6">
													<div class="input-group">
														<span class="input-group-addon btn-primary">Expiry Date</span>
														<input key="ExpiryDate" v-mask="'##-##-####'" v-validate="'required'" type="text" name="ExpiryDate" class="form-control" placeholder="Expiry Date" v-model="memberData.expiry_date">
													</div>
												<span class="customError">{{ errors.first('ExpiryDate') }}</span>
												</div>

											</div>

											<div class="form-group" :class="{'has-error': errors.has('Since') }">
												<div class="col-md-12">
													<div class="input-group">
														<span class="input-group-addon btn-primary">Since</span>
														<input key="Since" v-mask="'####'" v-validate="'min:4'" type="text" name="Since" class="form-control" placeholder="Since" v-model="memberData.since">
													</div>
												<span class="customError">{{ errors.first('Since') }}</span>
												</div>
											</div>

						<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">

						<button class="mb-xs mt-xs mr-xs btn btn-secondary btn-lg btn-block" @click="saveMember" type="submit">Save <i v-show="sending" class="fa fa-spinner fa-spin"></i></button>
						</div>

									</div>
									</div>
										</form>
										</div>
								</section>



<businessmen-table></businessmen-table>

</section>

</section>

						</div>
</template>
<script>
import { Validator } from 'vee-validate'
import axios from 'axios'
import {mask} from 'vue-the-mask'
import vue2Dropzone from 'vue2-dropzone'
import 'vue2-dropzone/dist/vue2Dropzone.min.css'


/*
	For Phone Unique Name
*/
const extendOptions = { hasTarget: true };

Validator.extend('isSame', {
	validate: (value, [other]) => {
		if (value !== other) {
			return true
		}else{
			return false
		}
  },
  getMessage: (field, [target]) => `The ${field} must not be same as ${target} field.`
}, extendOptions);

// const extendOptions2 = { hasTarget: true };

// Validator.extend('isSame', {
// 	validate: (value, [other]) => {
// 		if (value !== other) {
// 			return true
// 		}else{
// 			return false
// 		}
//   },
//   getMessage: (field, [target]) => `The ${field} must not be same as ${target} field.`
// }, extendOptions2);

/*
	For Phone Unique Name
*/
const isUniquePhone = value => new Promise((resolve) => {
  setTimeout(() => {
  	if (value.length === 11) {
  	axios.get('/businessmen/unique/'+value+'/'+'phone_number').then(
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
  }

  }, 200)
})

Validator.extend('phone', {
  validate: isUniquePhone,
  getMessage: (field, params, data) => data.message
})

/*
	For Mobile Unique Name
*/
const isUniqueMobile = value => new Promise((resolve) => {
  setTimeout(() => {
  	if (value.length === 11) {
  	axios.get('/businessmen/uniqueall/'+value).then(
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
  		}
  }, 2000)
})

Validator.extend('mobileunique', {
  validate: isUniqueMobile,
  getMessage: (field, params, data) => data.message
})

/*
	For Mobile Unique Name
*/
const isUniqueCnic = value => new Promise((resolve) => {
  setTimeout(() => {
  	if (value.length === 15) {
  	axios.get('/businessmen/uniquecnic/'+value).then(
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
  }

  }, 2000)
})

Validator.extend('uniquecnic', {
  validate: isUniqueCnic,
  getMessage: (field, params, data) => data.message
})

/*
	For Passport Unique Name
*/
const isUniquePassport = value => new Promise((resolve) => {
  setTimeout(() => {
  	if (value.length >= 9) {
  	axios.get('/businessmen/unique/'+value+'/'+'passport_number').then(
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
  }

  }, 200)
})

Validator.extend('passport', {
  validate: isUniquePassport,
  getMessage: (field, params, data) => data.message
})

/*
	For email Unique Name
*/
const isUniqueEmail = value => new Promise((resolve) => {
  setTimeout(() => {
  	axios.get('/businessmen/unique/'+value+'/'+'email').then(
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

  }, 2000)
})

Validator.extend('uemail', {
  validate: isUniqueEmail,
  getMessage: (field, params, data) => data.message
})

/*
	For gst Unique Name
*/
const isUniqueNtn = value => new Promise((resolve) => {
  setTimeout(() => {
  	if (value.length >= 9) {
  	axios.get('/businessmen/unique/'+value+'/'+'ntn').then(
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
  }

  }, 2000)
})

Validator.extend('untn', {
  validate: isUniqueNtn,
  getMessage: (field, params, data) => data.message
})


  export default {
    // Do not forget this little guy
    name: 'businessmen',
    components: {dropzone: vue2Dropzone},
    directives: {mask},
    data(){
    	return {
    		wait: false,
			dropzoneOptions: {
				url: '/',
				thumbnailWidth: 150,
				autoProcessQueue: false,
				addRemoveLinks: true,
				acceptedFiles: 'image/*',
				maxFilesize: 1,
				maxFiles: 1,
				dictDefaultMessage: '<p><i class="fa fa-caret-right text-primary"></i> Drop Image Here</p>',
			},
			Name: '',
			Email: '',
			Password: '',
			Role: '',
			memberData:{},
    	}
    },

    computed: {
    	sending(){
    		return this.wait
    	},
    	single(){
    		return (this.memberData.nationality == 'Single') ? true : ''
    	},
    	dual(){
    		return (this.memberData.nationality == 'Dual') ? true : ''
    	},
    	ExecutiveMember(){
    		if (this.memberData.membership == 'Executive') {
    			return true
    		}else{
    			return false
    		}
    	},
    	overseasCheck(){
    		if (this.memberData.nationality != 'Dual') {
    			return false
    		}else{
    			return true
    		}
    	},

		title(){
    		return 'Businessmen'
    	},
    	active(){
    		return this.$store.state.collapse
    	},
    	loading(){
    		return this.$store.state.loading
    	}
    },
    methods: {
    	saveMember:function (e) {
    		e.preventDefault()

    				/*
						Check Validation
					*/

	      this.$validator.validateAll().then((result) => {

    					this.wait = true
	        			if (result) {
	    				var fileInstance = this.$refs.myVueDropzone.dropzone


	    				if (fileInstance.getQueuedFiles() != '') {

	    					Vue.set( this.memberData, 'image', fileInstance.getAcceptedFiles()[0].dataURL )

	    					axios.post('/businessmen', {'params' : this.memberData})
	    					.then(res => {

	    						if (res.data == 1) {

	    							this.$store.dispatch('collapseData')
	    							this.memberData = {}
	    							fileInstance.removeAllFiles()

			    					this.$notify({
										group: 'success',
										type: 'success',
										title: 'Success',
										duration: 4000,
										text: 'Member added successfully.'
									});
	    							
	    						}

	    					}).catch(err => this.wait = false)

	    							
	    				}else{

			    					this.$notify({
										group: 'success',
										type: 'error',
										title: 'Error',
										duration: 4000,
										text: 'PLease add image before submitting form.'
									});
	    				}
    			}else{
    				this.wait = false
    			}
    		})

    	},
    	activate:function(){
    		this.$store.dispatch('collapseData')
    	},

    },
    // component Lifecycle hooks
    beforeCreate() {

    },
    mounted() {
  // console.log(this.$refs.myVueDropzone.dropzone)
    },
};
</script>

<style scoped>

.checked{
	display: none;
}
.input-group-addon{
	width: 30%;
}

.input-group{
	width: 100%;
}

.vue-dropzone {
    border: 2px solid #fdfdfd;
    border-top: 2px dotted #0088cc;
    font-family: 'Arial',sans-serif;
    letter-spacing: .2px;
    color: #0088cc;
    transition: background-color .2s linear;
    background-color: #fdfdfd;
}
.customBorder{
	border:1px solid grey;
}
	
</style>