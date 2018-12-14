 <template>
    <div class="custom-actions">
      <button class="btn btn-sm" @click="itemAction('delete-item', rowData, rowIndex)"><i class="glyphicon glyphicon-trash alert-danger"></i></button>
    </div>
  </template>

  <script>
  export default {
    data() {
      return {
        activeIndex: undefined
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

      itemAction (action, data, index) {

            swal({
              title: "Are you sure?",
              text: "Total amount will be adjusted accordingly.",
              icon: "warning",
              buttons: ["Back", "Confirm"],
              dangerMode: true,
            }).then((done) => {

              if (done) {
              axios.delete('/cashbook/'+data.id)
              .then(res => {

              this.$store.dispatch('setaccounts')
              this.$parent.refresh()
              this.$notify({
                group: 'success',
                type: 'success',
                duration: 4000,
                title: 'Success',
                text: 'Cashbook entry removed and amount adjusted',
              })
            }
                ).catch(err => {

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
      },

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