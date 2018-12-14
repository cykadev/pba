<template>
  <div @click="onClick">
    <div class="inline field">
      <label>Date: </label>
      <span>{{rowData.date}}</span>
    </div>
    <div class="inline field">
      <label>Type: </label>
      <span>{{rowData.type}}</span>
    </div>
    <div class="inline field">
      <label>Category: </label>
      <span>{{rowData.category}}</span>
    </div>
    <div class="inline field">
      <label>Method: </label>
      <span>{{rowData.method}}</span>
    </div>
    <div class="inline field">
      <label>Total: </label>
      <span>{{rowData.total}}</span>
    </div>
    <div class="inline field">
      <label>Ref: </label>
      <span>{{rowData.ref}}</span>
    </div>
    <div class="inline field">
      <label>Payer: </label>
      <span>{{rowData.payer}}</span>
    </div>
    <div class="inline field">
      <label>Description: </label>
      <span>{{rowData.description}}</span>
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
  methods: {
    onClick (event) {
      // console.log('my-detail-row: on-click', event.target)
    }
  },
}
</script>