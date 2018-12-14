<template>
  <div>
    <cashbook-filter-bar></cashbook-filter-bar>
    <!-- http://vuetable.ratiw.net/api/users -->
    <cashbook ref="cashbook"
      api-url="/cashbook"
      :fields="fields"
      pagination-path=""
      :css="css.table"
      :multi-sort="true"
      detail-row-component="cashbook-detail-row"
      :append-params="moreParams"
      @vuetable:cell-clicked="onCellClicked"
      @vuetable:pagination-data="onPaginationData"
    ></cashbook>
    <div class="vuetable-pagination">
      <vuetable-pagination-info ref="paginationInfoCashbook"
        info-class="pagination-info"
      ></vuetable-pagination-info>
      <vuetable-pagination ref="paginationCashbook"
        :css="css.pagination"
        @vuetable-pagination:change-page="onChangePage"
      ></vuetable-pagination>
    </div>
  </div>
</template>

<script>
import accounting from 'accounting'
import moment from 'moment'
import cashbook from 'vuetable-2'
import VuetablePagination from 'vuetable-2/src/components/VuetablePagination'
import VuetablePaginationInfo from 'vuetable-2/src/components/VuetablePaginationInfo'
import VueEvents from 'vue-events'
// import CustomActions from './actions/CustomAction'
import DetailRow from './actions/DetailRow'
import FilterBar from './actions/FilterBar'
Vue.use(VueEvents)
// Vue.component('cashbook-custom-actions', CustomActions)
Vue.component('cashbook-detail-row', DetailRow)
Vue.component('cashbook-filter-bar', FilterBar)

        // {
        //   name: '__checkbox',
        //   titleClass: 'text-center',
        //   dataClass: 'text-center',
        // },
export default {
  props: ['categories'],
  components: {
    cashbook,
    VuetablePagination,
    VuetablePaginationInfo,
  },
  data () {
    return {
      categories: [],
      fields: [
        {
          name: '__sequence',
          title: '#',
          titleClass: 'text-right',
          dataClass: 'text-right'
        },
        {
          name: 'date',
          sortField: 'date',
          titleClass: 'text-center',
          dataClass: 'text-center'
        },
        {
          name: 'type',
          sortField: 'type',
        },
        {
          name: 'category',
          sortField: 'category',
          titleClass: 'text-center',
          dataClass: 'text-center'
        },
        {
          name: 'method',
          sortField: 'method',
          titleClass: 'text-center',
          dataClass: 'text-center'
        },     
        {
          name: 'total',
          sortField: 'total',
          titleClass: 'text-center',
          dataClass: 'text-center'
        },
        {
          name: 'ref',
          sortField: 'ref',
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
      this.$refs.paginationCashbook.setPaginationData(paginationData)
      this.$refs.paginationInfoCashbook.setPaginationData(paginationData)
    },
    onChangePage (page) {
      this.$refs.cashbook.changePage(page)
    },
    onCellClicked (data, field, event) {
      this.$refs.cashbook.toggleDetailRow(data.id)
    },
     refreshTable (filterText) {
      this.moreParams = {
        filter: filterText
      }
      Vue.nextTick( () => this.$refs.cashbook.refresh() )
    },
  },
  events: {
     'refresh-table' () {

      var fmt = 'YYYY-MM-DD'
      var initialDate = ''
      initialDate = moment(new Date(), 'YYYY-MM-DD').format(fmt) + '_' + moment(new Date(), 'YYYY-MM-DD').format(fmt)

      this.moreParams = {
         filter: initialDate,
         per_page: 15,
         type:{},
         category:{}
      }
      Vue.nextTick( () => this.$refs.cashbook.refresh() )
    },
    'filter-set' (filterText, category, type, pagination) {
      this.moreParams = {
        filter: filterText,
        per_page: pagination,
        category:{category},
        type:{type}
      }
      Vue.nextTick( () => this.$refs.cashbook.refresh() )
    },
    'search-set' (query, pagination) {
      this.moreParams = {
        filter:'',
        q: query,
        per_page: pagination,
        category:{},
        type:{}
      }
      Vue.nextTick( () => this.$refs.cashbook.refresh() )
    },
    'filter-reset' () {
      this.moreParams = {
        per_page: 15
      }
      Vue.nextTick( () => this.$refs.cashbook.refresh() )
    }
  }
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