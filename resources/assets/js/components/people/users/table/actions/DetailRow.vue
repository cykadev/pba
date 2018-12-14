<template>
<div>
  <div @click="onClick">
    <div class="inline field">
      <label>Name: </label>
      <span>{{rowData.name}}</span>
    </div>
    <div class="inline field">
      <label>Email: </label>
      <span>{{rowData.email}}</span>
    </div>
    <div class="inline field">
      <label>Status: </label>
        <span v-if="rowData.status == 1">{{'Active'}}</span>
        <span v-else>{{'Deactive'}}</span>
    </div>
    <div class="inline field">
      <label>Role: </label>
      <span>{{(rowData.role == 1) ? 'Admin' : 'User'}}</span>
    </div>
  </div>
  <br>
    <div class="inline field">
      <label>Change Password: </label>
      <input v-validate="'required|min:6'" name="Password" type="password" v-model="Password">
      <button class="btn btn-primary btn-xs" @click="changePassword()">Change</button>
      <span class="customError">{{ errors.first('Password') }}</span>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    rowData: {
      type: Object,
      required: true
    },
    rowIndex: {
      type: Number
    }
  },
  data(){
    return{
      Password: '',
    }
  },
  methods: {
    onClick (event) {
      // console.log('my-detail-row: on-click', event.target)
    },
      changePassword:function() {
        // Loader

        this.$validator.validateAll().then((result) => {
          if (result) {
          axios.post('/users/password',{
              params:{
                id:this.rowData.id,
                password:this.Password,
              }
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
                this.Password = ''

                  this.$notify({
                group: 'success',
                type: 'success',
                title: 'Success',
                duration: 4000,
                text: 'Password changed successfully.'
              });

              this.$events.fire('filter-reset')
            }
          })
          }
        })

      },
  },
}
</script>