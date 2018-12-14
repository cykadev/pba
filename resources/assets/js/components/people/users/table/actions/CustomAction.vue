 <template>
    <div class="custom-actions">
      <button class="btn btn-sm" @click="itemAction('change-status', rowData, rowIndex)"><i class="glyphicon glyphicon-refresh" :class="{'glyphicon-refresh-animate' : activeIndex === rowIndex}"></i></button>
      <button class="btn btn-sm" @click="itemAction('change-role', rowData, rowIndex)"><i :class="checkStatus(rowData)"></i></button>
      <button class="btn btn-sm" @click="itemAction('delete-item', rowData, rowIndex)"><i class="glyphicon glyphicon-trash alert-danger"></i></button>
    </div>
  </template>

  <script>
  export default {
    data() {
      return {
        activeIndex: undefined,
      }
    },
    props: {
      rowData: {
        type: Object,
        required: true
      },
      rowIndex: {
        type: Number
      }
    },
    computed:{
      clickedStatus(){
        return this.clickedStatus = this.$store.state.status
      }
    },
    methods: {
      checkStatus(data){
        if (data.role == 1) {
            return 'glyphicon glyphicon-arrow-up'
        }else{
            return 'glyphicon glyphicon-arrow-down'
        }
      },
      itemAction (action, data, index) {
      if (action == 'change-status'){

              this.activeIndex = index

              axios.get('/users/status/'+data.id)
              .then(res => setTimeout(() => {this.activeIndex = undefined, this.$parent.refresh()}, 2000))
              .catch(err => setTimeout(() => {this.activeIndex = undefined, this.$parent.refresh()}, 2000))


        } else if (action == 'change-role'){

            swal({
              title: "Are you really want to change role of user?",
              icon: "warning",
              buttons: ["Back", "Confirm"],
              dangerMode: true,
            }).then((done) => {

              if (done) {
              axios.get('/users/role/'+data.id)
              .then(res => {

              this.$parent.refresh()
              this.$notify({
                group: 'success',
                type: 'success',
                duration: 4000,
                title: 'Success',
                text: 'Role Updated',
              })
            }
                ).catch(err => {

              this.$parent.refresh()
              this.$notify({
                group: 'success',
                type: 'error',
                duration: 4000,
                title: 'Error',
                text: 'Error! something went wrong',
              })
            }

                )             
              }

            })

        }else{

            swal({
              title: "Are you really want to remove user?",
              icon: "warning",
              buttons: ["Back", "Confirm"],
              dangerMode: true,
            }).then((done) => {

              if (done) {
              axios.get('/users/remove/'+data.id)
              .then(res => {

              this.$parent.refresh()
              this.$notify({
                group: 'success',
                type: 'success',
                duration: 4000,
                title: 'Success',
                text: 'User removed',
              })
            }
                ).catch(err => {

              this.$parent.refresh()
              this.$notify({
                group: 'success',
                type: 'error',
                duration: 4000,
                title: 'Error',
                text: 'Error! something went wrong',
              })
            }

                )             
              }

            })

        }
      },
      editcharges(){
        conosle.log('clicked')
      }

    }
  }
  </script>

  <style>
    .custom-actions button.ui.button {
      padding: 8px 8px;
    }
    .custom-actions button.ui.button > i.icon {
      margin: auto !important;
    }
    .glyphicon-refresh-animate {
        color: #0088cc;
        -animation: spin 2s infinite linear;
        -webkit-animation: spin2 2s infinite linear;
    }

    @-webkit-keyframes spin2 {
        from { -webkit-transform: rotate(0deg);}
        to { -webkit-transform: rotate(360deg);}
    }

    @keyframes spin {
        from { transform: scale(1) rotate(0deg);}
        to { transform: scale(1) rotate(360deg);}
    }
  </style>