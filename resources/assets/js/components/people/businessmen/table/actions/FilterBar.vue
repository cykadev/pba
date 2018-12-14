<template>
    <div class="filter-bar">
      <form class="form-inline">
        <div class="form-group">
          <label>Search for:</label>
          <input type="text" v-model="query" class="form-control" @keyup.enter="doFilter" placeholder="Title">
          <!-- <button class="btn" @click.prevent="doFilter">Go</button> -->
          <button class="btn" @click.prevent="resetFilter">Reset</button>         
        </div>

    <div class="btn-group" :class="(active) ? 'open' : ''" @click="opencategory()">
    <button type="button" class="mb-xs mt-xs mr-xs btn btn-default dropdown-toggle" data-toggle="dropdown" :aria-expanded="(active) ? 'true' : 'false'"> Search In <span class="caret"></span></button>
      <ul class="dropdown-menu" role="menu">

      <li><a tabindex="0"><label class="checkbox"><input name="category" type="radio" v-model="category" value="membership"> Membership</label></a></li>
      <li><a tabindex="0"><label class="checkbox"><input name="category" type="radio" v-model="category" value="bmr_number"> BMR</label></a></li>
      <li><a tabindex="0"><label class="checkbox"><input name="category" type="radio" v-model="category" value="ntn"> NTN</label></a></li>
      <li><a tabindex="0"><label class="checkbox"><input name="category" type="radio" v-model="category" value="cnic"> CNIC</label></a></li>
     <li><a tabindex="0"><label class="checkbox"><input name="category" type="radio" v-model="category" value="province"> Province</label></a></li>
    <li><a tabindex="0"><label class="checkbox"><input name="category" type="radio" v-model="category" value="nationality"> Nationality</label></a></li>
    <li><a tabindex="0"><label class="checkbox"><input name="category" type="radio" v-model="category" value="all"> All</label></a></li>
    <li class="customSearch" @click.prevent="doFilter">Search</li>
      </ul>
    </div>

    <button class="btn" disabled>Selected: {{category}}</button> 

    <div class="btn-group pull-right" :class="(active3) ? 'open' : ''" @click="openpagination()">
    <button type="button" class="mb-xs mt-xs mr-xs btn btn-default dropdown-toggle" data-toggle="dropdown" :aria-expanded="(active3) ? 'true' : 'false'">Per Page <span class="caret"></span></button>
      <ul class="dropdown-menu" role="menu">

        <li><a tabindex="0"><label class="radio"><input type="radio" v-model="paginationValue" value="15"> 15 Perpage</label></a></li>
        <li><a tabindex="0"><label class="radio"><input type="radio" v-model="paginationValue" value="50"> 50 perpage</label></a></li>
        <li><a tabindex="0"><label class="radio"><input type="radio" v-model="paginationValue" value="100"> 100 perpage</label></a></li>

      </ul>
    </div>

    <div class="btn-group pull-right">
    <button type="button" class="mb-xs mt-xs mr-xs btn btn-default" disabled>Selected: {{paginationValue}}</button>
    </div>


      </form>
    </div>
</template>

<script>
import moment from 'moment'
  export default {
  data() {
    return {

      paginationValue: 15,
      query: '',
      category: 'all',
      filterText:'',
      active: false,
      active3: false,
      date: '',
    }
  },
  computed:{

  },
  mounted(){

    this.$events.fire('filter-set' ,this.paginationValue)

  },
    methods: {
     opencategory(){
        (this.active == true) ? this.active = false : this.active = true
      },
      openpagination(){
        (this.active3 == true) ? this.active3 = false : this.active3 = true
      },
      doFilter () {
        this.$events.fire('search-set',this.query , this.paginationValue, this.category)
      },
      UpdateSelected () {
        // this.$events.fire('filter-set', this.filterText)
      },
      RemoveSelected () {
        // this.$events.fire('filter-set', this.filterText)
      },
      resetFilter () {
          this.$events.fire('filter-set', this.paginationValue)
      }
    }
  }
</script>
<style scoped>
.filter-bar {
  padding: 10px;
}
.margin{
  margin-left: 1%;
}
.form-horizontal .checkbox {
    min-height: 27px;
}
.dropdown-menu{
    font-size: 14px;
    list-style: none;
}
.customSearch{
  background-color: #0088cc;
  color: #fff;
  text-align: center;
}
.customSearch:hover{
  text-decoration: underline;
  cursor:pointer;
}
.dropdown-menu 
{
    background-color: #34495e;    
    box-shadow: none;
    -webkit-box-shadow: none;
    width: 250px;
    margin-left: -125px;
    left: 50%;
    }
.dropdown-menu .divider 
{
    background:none;    
    }
.dropdown-menu>li>a
{
    color:#f5f5f5;
}
.dropdown-menu>li>a:hover
{
    color:#0088cc;
}
.dropup .dropdown-menu 
{
    margin-bottom:10px;
    }
.dropup .dropdown-menu:before 
{
    content: "";
    border-top: 10px solid #34495e;
    border-right: 10px solid transparent;
    border-left: 10px solid transparent;
    position: absolute;
    bottom: -10px;
    left: 50%;
    margin-left: -10px;
    z-index: 10;
    }
</style>