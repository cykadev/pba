<template>
  <div>
    <filter-bar></filter-bar>
    <!-- http://vuetable.ratiw.net/api/users -->
    <usertable ref="usertableref"
      api-url="/users"
      :fields="fields"
      pagination-path=""
      :css="css.table"
      :sort-order="sortOrder"
      :multi-sort="true"
      detail-row-component="users-detail-row"
      :append-params="moreParams"
      @vuetable:cell-clicked="onCellClicked"
      @vuetable:pagination-data="onPaginationData"
    ></usertable>
    <div class="vuetable-pagination">
      <vuetable-pagination-info ref="paginationInfoUsers"
        info-class="pagination-info"
      ></vuetable-pagination-info>
      <vuetable-pagination ref="paginationUsers"
        :css="css.pagination"
        @vuetable-pagination:change-page="onChangePage"
      ></vuetable-pagination>
    </div>
  </div>
</template>

<script>
import accounting from 'accounting'
import moment from 'moment'
import usertable from 'vuetable-2'
import VuetablePagination from 'vuetable-2/src/components/VuetablePagination'
import VuetablePaginationInfo from 'vuetable-2/src/components/VuetablePaginationInfo'
import VueEvents from 'vue-events'
import CustomActions from './actions/CustomAction'
import DetailRow from './actions/DetailRow'
import FilterBar from './actions/FilterBar'
Vue.use(VueEvents)
Vue.component('users-custom-actions', CustomActions)
Vue.component('users-detail-row', DetailRow)
Vue.component('users-filter-bar', FilterBar)

        // {
        //   name: '__checkbox',
        //   titleClass: 'text-center',
        //   dataClass: 'text-center',
        // },
export default {
  components: {
    usertable,
    VuetablePagination,
    VuetablePaginationInfo,
  },
  data () {
    return {
      fields: [
        {
          name: '__sequence',
          title: '#',
          titleClass: 'text-right',
          dataClass: 'text-right'
        },
        {
          name: 'name',
          sortField: 'name',
        },
        {
          name: 'email',
          sortField: 'email'
        },    
        {
          callback: 'status',
          name: 'status',
          sortField: 'status'
        },
        {
          callback: 'role',
          name: 'role',
          sortField: 'role'
        },
        {
          name: '__component:users-custom-actions',
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
      sortOrder: [
        { field: 'created_at', sortField: 'created_at', direction: 'desc'}
      ],
      moreParams: {}
    }
  },
  methods: {
    allcap (value) {
      return value.toUpperCase()
    },
    status (value) {

      if (value == 1) {
        return '<span class="label label-success"> Active</span>'
      }else{
        return '<span class="label label-warning"> Deactive</span>'
      }
      
    },
    role (value) {

      if (value == 1) {
        return '<span class="label label-success"> Admin</span>'
      }else{
        return '<span class="label label-success"> User</span>'
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
      this.$refs.paginationUsers.setPaginationData(paginationData)
      this.$refs.paginationInfoUsers.setPaginationData(paginationData)
    },
    onChangePage (page) {
      this.$refs.usertableref.changePage(page)
    },
    onCellClicked (data, field, event) {
      this.$refs.usertableref.toggleDetailRow(data.id)
    },
  },
  events: {
    'filter-set' (filterText) {
      this.moreParams = {
        filter: filterText
      }
      Vue.nextTick( () => this.$refs.usertableref.refresh() )
    },
    'filter-reset' () {
      this.moreParams = {}
      Vue.nextTick( () => this.$refs.usertableref.refresh() )
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