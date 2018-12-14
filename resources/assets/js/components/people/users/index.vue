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
			<li><span>Users</span></li>
		</ol>

		<a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>
	</div>
</header>
<section class="panel">
<section class="panel" :class="(active == 1) ? 'panel-collapsed' : ''">
					
								<header class="panel-heading">
										<button type="button" class="mb-xs mt-xs mr-xs btn btn-secondary btn-lg btn-block" @click="activate()"><i class="fa fa-plus"></i>
										Make New User</button>
									</header>
									<div class="panel-body">
									<form class="form-horizontal" @submit.prevent="addUsers">




											<div class="form-group" :class="{'has-error': errors.has('Name') }">
												<div class="col-md-12">
													<div class="input-group">
														<span class="input-group-addon btn-primary">Name</span>
														<input v-validate="'required|min:3'" type="text" name="Name" class="form-control" placeholder="Username" v-model="Name">
													</div>
												<span class="customError">{{ errors.first('Name') }}</span>
												</div>
											</div>

											<div class="form-group" :class="{'has-error': errors.has('Email') }">
												<div class="col-md-12">
													<div class="input-group">
														<span class="input-group-addon btn-primary">Email</span>
														<input v-validate="'required|uniques|email'" type="text" name="Email" class="form-control" placeholder="Email" v-model="Email">
													</div>
												<span class="customError">{{ errors.first('Email') }}</span>
												</div>
											</div>

											<div class="form-group" :class="{'has-error': errors.has('Password') }">
												<div class="col-md-12">
													<div class="input-group">
														<span class="input-group-addon btn-primary">Password</span>
														<input v-validate="'required|min:6'" type="password" name="Password" class="form-control" placeholder="Password" v-model="Password">
													</div>
												<span class="customError">{{ errors.first('Password') }}</span>
												</div>
											</div>

									<div class="form-group" :class="{'has-error': errors.has('Role') }">
												<div class="col-md-12">
													<div class="input-group">
														<span class="input-group-addon btn-primary">Role</span>
													<select v-validate="'required'" name="Role" class="form-control" v-model="Role">
												<option value="2">User</option>
												<option value="1">Admin</option>
													</select>
													</div>
									<span class="customError">{{ errors.first('Role') }}</span>
												</div>
											</div>

								

											<div class="form-group">
												<div class="col-md-12">
													<div class="input-group pull-right">
												<button class="btn btn-primary" @click="addUsers" type="submit">Create <i class="" style="font-size:17px" :class="(loading == 1) ? 'fa fa-spinner fa-spin' : ''"></i></button>
													</div>
						
												</div>
											</div>
										</form>
									</div>
								</section>



<user-table></user-table>

</section>

</section>

						</div>
</template>
<script>
import { Validator } from 'vee-validate'
import axios from 'axios'

/*
	For Company Unique Name
*/
const isUnique = value => new Promise((resolve) => {
  setTimeout(() => {
  	axios.get('/charges/checkUniqueUser/'+value).then(
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
    name: 'Users',
    title: '',
    active: '',
    loading: '',
        // collapse : this.$store.state.collapse,
    data(){
    	return {
			Name: '',
			Email: '',
			Password: '',
			Role: '',
    	}
    },

    computed: {
		title(){
    		return this.title = 'Users'
    	},
    	active(){
    		return this.active = this.$store.state.collapse
    	},
    	loading(){
    		return this.loading = this.$store.state.loading
    	}
    },
    // when component uses other components
    components: {},
    methods: {
    	addUsers:function (e) {
    		e.preventDefault()
    		// Loader

	      this.$validator.validateAll().then((result) => {
	        if (result) {
    			this.$store.dispatch('initLoading');


		     	axios.post('/role',{
		     			params:{
			     			name:this.Name,
			     			email:this.Email,
			     			password:this.Password,
			     			role:this.Role,
		     			}
		     		}
		     	).then(res => {
		     		this.$store.dispatch('initLoading');
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
	    					this.Name = ''
							this.Email = ''
							this.Password = ''
							this.Role = ''

	    				    this.$notify({
								group: 'success',
								type: 'success',
								title: 'Success',
								duration: 4000,
								text: 'Charges added successfully.'
							});

							this.$events.fire('filter-reset')
	    			}
	    		})
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
    	
    },
};
</script>

<style scoped>

.checked{
	display: none;
}
	
</style>