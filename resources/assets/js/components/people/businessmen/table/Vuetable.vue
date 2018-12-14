<template>
  <div>
    <businessmen-filter-bar></businessmen-filter-bar>
    <!-- http://vuetable.ratiw.net/api/users -->
    <businessmen ref="businessmen"
      api-url="/businessmen"
      :fields="fields"
      pagination-path=""
      :css="css.table"
      :multi-sort="true"
      detail-row-component="businessmen-detail-row"
      :append-params="moreParams"
      @vuetable:cell-clicked="onCellClicked"
      @vuetable:pagination-data="onPaginationData"
    ></businessmen>
    <div class="vuetable-pagination">
      <vuetable-pagination-info ref="paginationInfobusinessmen"
        info-class="pagination-info"
      ></vuetable-pagination-info>
      <vuetable-pagination ref="paginationbusinessmen"
        :css="css.pagination"
        @vuetable-pagination:change-page="onChangePage"
      ></vuetable-pagination>
    </div>
  </div>
</template>

<script>
import accounting from 'accounting'
import moment from 'moment'
import businessmen from 'vuetable-2'
import VuetablePagination from 'vuetable-2/src/components/VuetablePagination'
import VuetablePaginationInfo from 'vuetable-2/src/components/VuetablePaginationInfo'
import VueEvents from 'vue-events'
import CustomActions from './actions/CustomAction'
import DetailRow from './actions/DetailRow'
import FilterBar from './actions/FilterBar'
Vue.use(VueEvents)
Vue.component('businessmen-custom-actions', CustomActions)
Vue.component('businessmen-detail-row', DetailRow)
Vue.component('businessmen-filter-bar', FilterBar)

        // {
        //   name: '__checkbox',
        //   titleClass: 'text-center',
        //   dataClass: 'text-center',
        // },
export default {
  components: {
    businessmen,
    VuetablePagination,
    VuetablePaginationInfo,
  },
  data () {
    return {
      loading: true,
      categories: [],
      fields: [
        {
          name: '__sequence',
          title: '#',
          titleClass: 'text-right',
          dataClass: 'text-right'
        },
        {
          name: 'bmr_number',
          title: 'BMR',
          sortField: 'bmr_number',
          titleClass: 'text-center',
          dataClass: 'text-center'
        },
        {
          name: 'name',
          sortField: 'name',
          titleClass: 'text-center',
          dataClass: 'text-center'
        },
        {
          name: 'cnic',
          sortField: 'cnic',
          titleClass: 'text-center',
          dataClass: 'text-center',
          callback: 'customCnic'
        },
        {
          name: 'overseas_cnic',
          title: 'Overseas',
          sortField: 'overseas_cnic',
          titleClass: 'text-center',
          dataClass: 'text-center',
          callback: 'customOverseasCnic'
        },
        {
          name: 'membership',
          sortField: 'membership',
          titleClass: 'text-center',
          dataClass: 'text-center',
          callback: 'customMemberShip'
        },
        {
          name: '__component:businessmen-custom-actions',
          title: 'Manage',
          titleClass: 'text-center',
          dataClass: 'text-center'
        }
      ],
      css: {
        table: {
          tableClass: 'table table-bordered table-striped table-hover',
          ascendingIcon: 'glyphicon glyphicon-chevron-up',
          descendingIcon: 'glyphicon glyphicon-chevron-down'
        },
        pagination: {
          wrapperClass: 'pagination',
          activeClass: 'active',
          disabledClass: 'disabled',
          pageClass: 'page',
          linkClass: 'link',
          icons: {
            first: '',
            prev: '',
            next: '',
            last: '',
          },
        },
        icons: {
          first: 'glyphicon glyphicon-step-backward',
          prev: 'glyphicon glyphicon-chevron-left',
          next: 'glyphicon glyphicon-chevron-right',
          last: 'glyphicon glyphicon-step-forward',
        },
      },
      moreParams: {}
    }
  },
  methods: {
    customMemberShip (value) {
              if (value == 'Executive') {
                    return 'Executive ( Corporate Class )'
              }else if(value == 'Regular'){
                    return 'Regular ( Associate Class )'
              }else{
                    return 'General ( Associate Class )'
              }
          },
    customCnic (value) {
              if (value == '') {
                    return '<i class="fa fa-times alert-danger"></i>'
              }else{
                return value
              }
          },
    customOverseasCnic (value) {
              if (value == '') {
                    return '<i class="fa fa-times alert-danger"></i>'
              }else{
                return value
              }
          },
    allcap (value) {
      return value.toUpperCase()
    },
    status (value) {

      if (value == 1) {
        return '<span class="label label-success"> Paid</span>'
      }else{
        return '<span class="label label-danger"> Pending</span>'
      }
      
    },
    formatNumber (value) {
      return accounting.formatNumber(value, 2)
    },
    formatDate (value, fmt = 'D MMM YYYY') {
      return (value == null)
        ? ''
        : moment(value, 'YYYY-MM-DD').format(fmt)
    },
    onPaginationData (paginationData) {
      this.$refs.paginationbusinessmen.setPaginationData(paginationData)
      this.$refs.paginationInfobusinessmen.setPaginationData(paginationData)
    },
    onChangePage (page) {
      this.$refs.businessmen.changePage(page)
    },
    onCellClicked (data, field, event) {
      this.$refs.businessmen.toggleDetailRow(data.id)
    },
     refreshTable (filterText) {
      this.moreParams = {
        filter: filterText
      }
      Vue.nextTick( () => this.$refs.businessmen.refresh() )
    },
  },
  events: {
     'refresh-table' () {

      this.moreParams = {
         per_page: 15,
      }
      Vue.nextTick( () => this.$refs.businessmen.refresh() )
    },
    'filter-set' (pagination) {
      this.moreParams = {
        ini: true,
        per_page: pagination,
      }
      Vue.nextTick( () => this.$refs.businessmen.refresh() )
    },
    'search-set' (query, pagination, category) {
      this.moreParams = {
        q: query,
        per_page: pagination,
        category:category,
      }
      Vue.nextTick( () => this.$refs.businessmen.refresh() )
    },
    'filter-reset' () {
      this.moreParams = {
        per_page: 15
      }
      Vue.nextTick( () => this.$refs.businessmen.refresh() )
    }
  },
}
</script>
<style>
.pagination {
  margin: 0;
  float: right;
}
.pagination a.page {
  border: 1px solid lightgray;
  border-radius: 3px;
  padding: 5px 10px;
  margin-right: 2px;
}
.pagination a.page.active {
  color: white;
  background-color: #337ab7;
  border: 1px solid lightgray;
  border-radius: 3px;
  padding: 5px 10px;
  margin-right: 2px;
}
.pagination a.btn-nav {
  border: 1px solid lightgray;
  border-radius: 3px;
  padding: 5px 7px;
  margin-right: 2px;
}
.pagination a.btn-nav.disabled {
  color: lightgray;
  border: 1px solid lightgray;
  border-radius: 3px;
  padding: 5px 7px;
  margin-right: 2px;
  cursor: not-allowed;
}
.pagination-info {
  float: left;
}
</style>