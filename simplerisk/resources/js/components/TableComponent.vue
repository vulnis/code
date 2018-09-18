<template>
  <div>
    <vue-good-table
      :columns="columns"
      :rows="rows"
      :search-options="{
        enabled: true
      }"/>
  </div>
</template>

<script>
  import { VueGoodTable } from 'vue-good-table';
  import 'vue-good-table/dist/vue-good-table.css'
  export default {
    data () {
        return {
            columns: [],
            rows: []
        }
    },
    created() {
        axios.get(`assessments`)
        .then(response => {
            this.columns = response.data.columns;
            this.rows = response.data.rows;
        })
        .catch(e => {
            this.errors.push(e)
        })
    }
  }
</script>
