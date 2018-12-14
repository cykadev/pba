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
			<li><span>Business</span></li>
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
									<div class="col-md-6 col-sm-6 col-xs-12 col-lg-6">

										<!-- 

										Show List Of Groups
	
										 -->

									<template v-if="categoryFormOpen">
										
								<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
									<table class="table table-bordered">
								    <thead>
								      <tr>
								        <th>Code</th>
								        <th>Group</th>
								        <th>Manage</th>
								      </tr>
								    </thead>
								    <tbody>

								      <tr v-for="g in groups">
								      <template v-if="editIndex == g.id">
								      	
										<td><input type="text" name="codeEdit" v-model="editFieldData[0].editCode"></td>
										<td><input type="text" name="titleEdit" v-model="editFieldData[0].editTitle"></td>
										<td>

										<a href="javascript:void(0)" @click="saveGroupEdit(g.id)"><i class="fa fa-save alert-success"></i></a>
										<a href="javascript:void(0)" @click="editGroupField(g.id, 'cancel', {})"><i class="fa fa-edit"></i></a>
								        <a href="javascript:void(0)" @click="removeGroupData(g.id)"><i class="fa fa-trash alert-danger"></i></a>
								      
								        </td>

								      </template>

								      <template v-else>

								      	<td>{{g.code}}</td>
										<td>{{g.title}}</td>
										<td>

										<a href="javascript:void(0)" @click="editGroupField(g.id, 'edit', g.code, g.title)"><i class="fa fa-edit"></i></a>
								        <a href="javascript:void(0)" @click="removeGroupData(g.id)"><i class="fa fa-trash alert-danger"></i></a>
								      
								        </td>

								      </template>

								      </tr>
								    </tbody>
								  </table>
	
								</div>

									</template>


										<!-- 

										Show List Of Groups
	
										 -->


											<div v-if="!categoryFormOpen" class="form-group">
												<div class="col-md-12">

  <multiselect id="ajax" v-model="selectedBusinessmen" placeholder="Select Businessmen" 
  label="name" 
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
    	<div class="col-md-3 col-xs-3 col-lg-3 col-sm-3">
    	<img class="option__image" :src="props.option.image">
    	</div>
    	<div class="col-md-9 col-xs-9 col-lg-9 col-sm-9">
      <p class="option__title">NAME :{{ props.option.name }}</p>
      <p>BMR :{{ props.option.bmr_number }}</p>
      </div>
      </div>
    </template>
    <template slot="option" slot-scope="props">
    	<div class="row">
    	<div class="col-md-3 col-xs-3 col-lg-3 col-sm-3">
    	<img class="option__image" :src="props.option.image">
    	</div>
    	<div class="col-md-9 col-xs-9 col-lg-9 col-sm-9">
      <p class="option__title">NAME :{{ props.option.name }}</p>
      <p>BMR :{{ props.option.bmr_number }}</p>
      </div>
      </div>
    </template>
  </multiselect>

												</div>
											</div>

											<div v-if="!categoryFormOpen" class="form-group" :class="{'has-error': errors.has('Name') }">
												<div class="col-md-12">
													<div class="input-group">
														<span class="input-group-addon btn-primary">Company Name</span>
														<input  v-validate="'required|min:2|alpha_spaces'" type="text" name="Name" class="form-control" placeholder="Company Name" v-model="companyData.name">
													</div>
												<span class="customError">{{ errors.first('Name') }}</span>
												</div>
											</div>

											<div v-if="!categoryFormOpen" class="form-group" :class="{'has-error': errors.has('Designation') }">
												<div class="col-md-12">
													<div class="input-group">
														<span class="input-group-addon btn-primary">Designation</span>
														<input  v-validate="'required|min:2|alpha_spaces'" type="text" name="Designation" class="form-control" placeholder="Designation" v-model="companyData.designation">
													</div>
												<span class="customError">{{ errors.first('Designation') }}</span>
												</div>
											</div>


											<div v-if="!categoryFormOpen" class="form-group" :class="{'has-error': errors.has('address') }">
												<div class="col-md-12">
													<div class="input-group">
														<span class="input-group-addon btn-primary">Address</span>
														<input v-validate="'required|min:3'" type="text" name="address" class="form-control" placeholder="Office Address" v-model="companyData.address">
													</div>
												<span class="customError">{{ errors.first('address') }}</span>
												</div>
											</div>

										<div v-if="!categoryFormOpen" class="form-group" :class="{'has-error': errors.has('office') }">
										<label class="col-sm-3 control-label">Office Owned <span aria-required="true"></span></label>
										<div class="col-sm-9">
											<div class="radio-custom radio-primary radio-inline">
												<input v-validate="'required'" name="office" type="radio" value="Yes" v-model="companyData.office">
												<label for="awesome">Yes</label>
											</div>
											<div class="radio-custom radio-primary radio-inline">
												<input name="office" type="radio" value="No" v-model="companyData.office">
												<label for="awesome">No</label>
											</div>
										</div>
										</div>

											<div v-if="" class="form-group" :class="{'has-error': errors.has('BusinessEmail') }">
												<div class="col-md-12">
													<div class="input-group">
														<span class="input-group-addon btn-primary">Business Email</span>
														<input v-validate="'email|emailUnique'" type="text" name="BusinessEmail" class="form-control" placeholder="Business Email" v-model="companyData.email">
													</div>
												<span class="customError">{{ errors.first('BusinessEmail') }}</span>
												</div>
											</div>

											<div v-if="" class="form-group" :class="{'has-error': errors.has('BusinessEmail2') }">
												<div class="col-md-12">
													<div class="input-group">
														<span class="input-group-addon btn-primary">Business Email 2</span>
														<input v-validate="'email|emailUnique'" type="text" name="BusinessEmail2" class="form-control" placeholder="Business Email 2" v-model="companyData.email2">
													</div>
												<span class="customError">{{ errors.first('BusinessEmail2') }}</span>
												</div>
											</div>

											<div v-if="!categoryFormOpen" class="form-group" :class="{'has-error': errors.has('website') }">
												<div class="col-md-12">
													<div class="input-group">
														<span class="input-group-addon btn-primary">Website</span>
														<input v-validate="'url:require_protocol'" type="text" name="website" class="form-control" placeholder="Website" v-model="companyData.website">
													</div>
												<span class="customError">{{ errors.first('website') }}</span>
												</div>
											</div>


											<div v-if="!categoryFormOpen" class="form-group" :class="{'has-error': errors.has('Product') }">
												<div class="col-md-12">
													<div class="input-group">
														<span class="input-group-addon btn-primary">Product</span>
														<input v-validate="'required'" type="text" name="Product" class="form-control" placeholder="Product" v-model="companyData.product">
													</div>
												<span class="customError">{{ errors.first('Product') }}</span>
												</div>
											</div>

										<div class="form-group">
										<div class="col-md-12">
												<div class="input-group">
													<span class="input-group-addon btn-primary">Type</span>
													<select multiple="true" v-validate="'required'" name="Type" class="form-control" v-model="companyData.type">

														<option value="Manufacturer">Manufacturer</option>
														<option value="Services">Services</option>
														<option value="Importer">Importer</option>
														<option value="Exporter">Exporter</option>
														<option value="Wholesaler">Wholesaler</option>

													</select>
												</div>
												<span class="customError">{{ errors.first('Type') }}</span>
												<br>

												<template v-for="v in companyData.type">
													<span>{{v}}, </span>
												</template>

											</div>
											</div>


									</div>
									<div class="col-md-6 col-sm-6 col-xs-12 col-lg-6">

										<div v-if="!categoryFormOpen" class="form-group" :class="{'has-error': errors.has('Group') }">
											<div class="col-md-12">
												<div class="input-group">
													<span class="input-group-addon btn-primary">Group</span>
													<select multiple="true" v-validate="'required'" name="Group" class="form-control" v-model="companyData.group">
													<template v-for="g in groups">
														<option :value="g.title">{{ g.title }} - ( <span style="color:red;">{{g.code}}</span> )</option>
													</template>
													</select>
												</div>
												<span class="customError">{{ errors.first('Group') }}</span>
												<br>

												<template v-for="v in companyData.group">
													<span>{{v}}, </span>
												</template>

											</div>
												<a href="javascript:void(0)" @click="addCategoryNew()"><i class="fa fa-plus pull-right fa-lg mt-3"></i></a>
										</div>


										<!-- 

										ADD NEW GROUP FORM
	
										 -->

									<template v-if="categoryFormOpen">
										
								<div class="form-group" :class="{'has-error': errors.has('code') }">
								<div class="col-md-3 col-sm-3 col-xs-3 col-lg-3">
								<input v-validate="'required|codeUnique|max:2'" type="text" name="code" class="form-control" v-model="groupData.code" value="" placeholder="Code">
								<span class="customError">{{ errors.first('code') }}</span>
									
								</div>
								<div class="col-md-5 col-sm-5 col-xs-5 col-lg-5">
								<input v-validate="'required|titleUnique|min:3'" type="text" name="title" class="form-control" v-model="groupData.title" value="" placeholder="Title">
								<span class="customError">{{ errors.first('title') }}</span>
									
								</div>
								<div class="col-md-2 col-sm-2 col-xs-2 col-lg-2">
								<button type="button" class="btn btn-default mb-2" @click="addCategoryNew()">Back</button>		
								</div>
								<div class="col-md-2 col-sm-2 col-xs-2 col-lg-2">
								<button type="button" class="btn btn-primary mb-2" @click="submitGroup()">Create</button>		
								</div>
								</div>
									</template>


										<!-- 

										ADD NEW GROUP FORM END
	
										 -->


										<div v-if="!categoryFormOpen" class="form-group" :class="{'has-error': errors.has('GST') }">
											<div class="col-md-12">
												<div class="input-group">
													<span class="input-group-addon btn-primary">GST</span>
													<input v-mask="'##-##-####-###-##'" v-validate="'gstUnique'" type="text" name="GST" class="form-control" placeholder="GST" v-model="companyData.gst">
												</div>
											<span class="customError">{{ errors.first('GST') }}</span>
											</div>
										</div>

										<div v-if="!categoryFormOpen" class="form-group" :class="{'has-error': errors.has('category') }">
										<label class="col-sm-3 control-label">Category </label>
										<div class="col-sm-9">
											<div class="radio-custom radio-primary radio-inline">
												<input v-validate="'required'" name="category" type="radio" value="Individual" v-model="companyData.category">
												<label for="awesome">Individual</label>
											</div>
											<div class="radio-custom radio-primary radio-inline">
												<input name="category" type="radio" value="Company" v-model="companyData.category">
												<label for="awesome">Company</label>
											</div>
											<div class="radio-custom radio-primary radio-inline">
												<input name="category" type="radio" value="Firm" v-model="companyData.category">
												<label for="awesome">Firm</label>
											</div>
											<span class="customError">{{ errors.first('category') }}</span>
										</div>
										</div>

										<template v-if="!categoryFormOpen">
											
										<div v-if="companyData.category == 'Firm'" class="form-group" :class="{'has-error': errors.has('criteria') }">
										<label class="col-sm-3 control-label">Criteria <span aria-required="true"></span></label>
										<div class="col-sm-9">
											<div class="radio-custom radio-primary">
												<input v-validate="'required'" name="criteria" type="radio" value="Limited" v-model="companyData.criteria">
												<label for="awesome">Limited</label>
											</div>
											<div class="radio-custom radio-primary">
												<input name="criteria" type="radio" value="SMC Limited" v-model="companyData.criteria">
												<label for="awesome">SMC Limited</label>
											</div>
											<div class="radio-custom radio-primary">
												<input name="criteria" type="radio" value="Private Limited" v-model="companyData.criteria">
												<label for="awesome">Private Limited</label>
											</div>
											<div class="radio-custom radio-primary">
												<input name="criteria" type="radio" value="Public Limited" v-model="companyData.criteria">
												<label for="awesome">Public Limited</label>
											</div>
										</div>
										</div>

										</template>

										<template v-if="!categoryFormOpen">
											
										<template v-if="CheckCategoryForAop">

										<div class="form-group" :class="{'has-error': errors.has('AOP') }">
												<div class="col-md-12">
													<div class="input-group">
														<span class="input-group-addon btn-primary">AOP</span>
														<input v-mask="'#######-#'" v-validate="'required|aopUnique'" type="text" name="AOP" class="form-control" placeholder="AOP" v-model="companyData.aop">
													</div>
												<span class="customError">{{ errors.first('AOP') }}</span>
												</div>
											</div>	

										</template>

										</template>

										<div v-if="!categoryFormOpen" class="form-group">
												<div class="col-md-12">
													<div class="input-group">

										<dropzone ref="companyDropzone" id="dropzone" :options="dropzoneOptions"></dropzone>
													</div>
												
												</div>
											</div>	



						<div v-if="!categoryFormOpen" class="col-md-12 col-sm-12 col-xs-12 col-lg-12">

						<button class="mb-xs mt-xs mr-xs btn btn-secondary btn-lg btn-block" @click="saveMember" type="submit">Save <i v-show="sending" class="fa fa-spinner fa-spin"></i></button>
						</div>

									</div>
									</div>
										</form>
										</div>
								</section>



<business-table></business-table>

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
const isUniqueGst = value => new Promise((resolve) => {
  setTimeout(() => {
  	if (value.length === 17) {

		  	axios.get('/business/unique/'+value+'/'+'gst').then(
		  		function(res){
		  	  	if (res.data == 0) {
					return resolve({
						valid: true
					})
				}else{

					 return resolve({
			        	valid: false,
			        	data: {
					        message: `${value} is already in used by other Business.`
					      }
			      	})		
				}
		  			
		  		})
  	}

  }, 200)
})

Validator.extend('gstUnique', {
  validate: isUniqueGst,
  getMessage: (field, params, data) => data.message
})

/*
	For Phone Unique Name
*/
const isUniqueEmail = value => new Promise((resolve) => {
  setTimeout(() => {

		  	axios.get('/business/uniqueemails/'+value+'/').then(
		  		function(res){
		  	  	if (res.data == 0) {
					return resolve({
						valid: true
					})
				}else{

					 return resolve({
			        	valid: false,
			        	data: {
					        message: `${value} is already in used by other Business.`
					      }
			      	})		
				}
		  			
		  		})

  }, 200)
})

Validator.extend('emailUnique', {
  validate: isUniqueEmail,
  getMessage: (field, params, data) => data.message
})

/*
	For AOP Unique
*/
const isUniqueAop = value => new Promise((resolve) => {
  setTimeout(() => {
  	if (value.length === 9) {

		  	axios.get('/business/unique/'+value+'/'+'aop').then(
		  		function(res){
		  	  	if (res.data == 0) {
					return resolve({
						valid: true
					})
				}else{

					 return resolve({
			        	valid: false,
			        	data: {
					        message: `${value} is already in used by other Business.`
					      }
			      	})		
				}
		  			
		  		})
  	}

  }, 200)
})

Validator.extend('aopUnique', {
  validate: isUniqueAop,
  getMessage: (field, params, data) => data.message
})

/*
	For CODE Unique
*/
const isUniqueCode = value => new Promise((resolve) => {
  setTimeout(() => {
  	if (value.length === 2) {

		  	axios.get('/business/group/'+value+'/'+'code').then(
		  		function(res){
		  	  	if (res.data == 0) {
					return resolve({
						valid: true
					})
				}else{

					 return resolve({
			        	valid: false,
			        	data: {
					        message: `Already in Groups.`
					      }
			      	})		
				}
		  			
		  		})
  	}

  }, 200)
})

Validator.extend('codeUnique', {
  validate: isUniqueCode,
  getMessage: (field, params, data) => data.message
})

/*
	For TITLE Unique
*/
const isUniqueTitle = value => new Promise((resolve) => {
  setTimeout(() => {
  	if (value.length > 2) {

		  	axios.get('/business/group/'+value+'/'+'title').then(
		  		function(res){
		  	  	if (res.data == 0) {
					return resolve({
						valid: true
					})
				}else{

					 return resolve({
			        	valid: false,
			        	data: {
					        message: `${value} is already in used by other Group.`
					      }
			      	})		
				}
		  			
		  		})
  	}

  }, 200)
})

Validator.extend('titleUnique', {
  validate: isUniqueTitle,
  getMessage: (field, params, data) => data.message
})

  export default {
    // Do not forget this little guy
    name: 'business',
    components: {dropzone: vue2Dropzone},
    directives: {mask},
    data(){
    	return {
    		wait: false,
    		groupData: {},
    		editFieldData: {},
    		editIndex: undefined,
    		categoryFormOpen: false,
    		isLoading: false,
    		options:[],
    		groups:[],
			dropzoneOptions: {
				url: '/',
				thumbnailWidth: 150,
				autoProcessQueue: false,
				acceptedFiles: 'image/*',
				addRemoveLinks: true,
				maxFilesize: 3,
				maxFiles: 3,
				dictDefaultMessage: '<p><i class="fa fa-caret-right text-primary"></i> Drop Documents Here</p>',
			},
			Name: '',
			Email: '',
			Password: '',
			Role: '',
			selectedBusinessmen: '',
			companyData:{
				group:[],
				type:[],
			},
			submitData: '',
			images: [],
    	}
    },

    computed: {
    	CheckCategoryForAop(){
    		if (this.companyData.category == 'Company') {
    			return true
    		}else if(this.companyData.category == 'Firm'){
    			return true
    		}else{
    			return false
    		}
    	},
    	sending(){
    		return this.wait
    	},

		title(){
    		return 'Business'
    	},
    	active(){
    		return this.$store.state.collapse
    	},
    	loading(){
    		return this.$store.state.loading
    	}
    },
    methods: {
    	removeGroupData(id){
    		axios.delete('business/'+id+'/group')
    		.then(res => {

    				this.groups = res.data

     				this.$notify({
					group: 'success',
					type: 'success',
					title: 'Success',
					duration: 4000,
					text: 'Group removed successfully'
				})   			
    		})
    	},
    	saveGroupEdit(id){

    		axios.put('business/'+id+'/group?code='+this.editFieldData[0].editCode+'&title='+this.editFieldData[0].editTitle
    		).then( res  => {

    			this.groups = res.data
    			this.editIndex = undefined
  				this.$notify({
				group: 'success',
				type: 'success',
				title: 'Success',
				duration: 4000,
				text: 'Group edited successfully'
				})  	

    		})
    		.catch(err => {	

				this.$notify({
				group: 'success',
				type: 'error',
				title: 'Error',
				duration: 6000,
				text: err.response.data.error
				})
    		})

    	},
    	editGroupField(index, flag, code, title){

    		Vue.set(this.editFieldData, 0, { editCode: code, editTitle: title });
    		(flag === 'edit') ? this.editIndex = index : this.editIndex = undefined
    		
    	},
    	submitGroup(){
				this.$validator.validateAll().then((result) => {
						axios.post('business/group', {
							params: this.groupData
						}).then(res => {

								this.groups = []
								this.groupData = {}
								// this.categoryFormOpen = false
								this.$notify({
									group: 'success',
									type: 'success',
									title: 'Success',
									duration: 4000,
									text: 'Group added successfully.'
								})
								this.groups = res.data

						})

				})
    	},
    	addCategoryNew(){
    		(this.categoryFormOpen) ? this.categoryFormOpen = false: this.categoryFormOpen = true
    	},
    	asyncFind(query){

    		if (query.length > 2) {

    			this.isLoading = true
    		axios.get('/businessmen/asyncsearch?q='+query)
    			 .then(res => {
    			 	this.options = res.data
    			 	this.isLoading = false
    			 })

    		}

    	},
    	processData(fileInstance){

    		this.wait = true
			axios.post('/business', {'params' : this.companyData})
			.then(res => {

					this.$store.dispatch('collapseData')
					this.companyData = {}
					this.companyData = {
						group: []
					}
					this.selectedBusinessmen = ''
					this.images = []
					this.options = []
					this.groups = []
					fileInstance.removeAllFiles()

					this.$events.fire('filter-set', 15)

					this.$notify({
						group: 'success',
						type: 'success',
						title: 'Success',
						duration: 4000,
						text: 'Business added successfully.'
					});

					this.wait = false


			}).catch(err => {

					this.wait = false
					this.$notify({
						group: 'success',
						type: 'error',
						title: 'Error',
						duration: 4000,
						text: 'Exception caught while submitting form'
					});
			})
    	},
    	saveMember:function (e) {
    		e.preventDefault()

    				/*
						Check Validation
					*/

	      this.$validator.validateAll().then((result) => {

	      				if (this.selectedBusinessmen == '') {
	      					alert('Select Businessmen Before Proceeding')
	      				}else{

	        			if (result) {
	    				var fileInstance = this.$refs.companyDropzone.dropzone


	    				if (fileInstance.getQueuedFiles() != '') {

	    					for (var i = 0; i < fileInstance.getAcceptedFiles().length; i++) {
	    						this.images.push(fileInstance.getAcceptedFiles()[i].dataURL)
	    					}

	    					Vue.set( this.companyData, 'images', this.images )
	    					Vue.set( this.companyData, 'businessmen_id', this.selectedBusinessmen.id )

	    						this.processData(fileInstance)
	    							
	    				}else{

	    					Vue.set( this.companyData, 'businessmen_id', this.selectedBusinessmen.id )
	    						this.processData(fileInstance)
	    				}
    			}else{
    				this.wait = false
    			}
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
  		// axios.get('/businessmen/customsearch')
  		// 	 .then(res => {

  		// 	 	this.options = res.data
  		// 	 	console.log(this.options)
  		// 	 })

  			// this.companyData.category = 'Individual'

    		axios.get('/businessmen/groups')
    			 .then(res => {
    			 	this.groups = res.data
    			 })
    },
};
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<style scoped>

.checked{
	display: none;
}
.input-group-addon{
	width: 30%;
}

.form-group{
	z-index: 0;
}
.input-group input{
	z-index: 0;
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
.option__image{
	width: 100%;
}
.multiselect__element{
	z-index: 3;
}
.multiselect__option--highlight{
	background: #0088cc;
}
</style>