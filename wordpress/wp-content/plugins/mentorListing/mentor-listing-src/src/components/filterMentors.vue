<template>
  <div class="searchContainer">
    <h2>Find your mentor</h2>
    <date-range-modal :showModal="showDialog" @closeModal="respondToDateRangeModalCloseEvent"></date-range-modal>

    <div class="row">
      <div class="col-md-8">
        <md-field>
          <label>Search...</label>
          <md-input></md-input>
        </md-field>
      </div>

      <div class="col-md-4">
        <md-button class="md-primary md-raised" @click="showDialog = true">Filter</md-button>
      </div>
    </div>
  </div>
</template>

<style>
.searchContainer {
  margin: 2%;
  margin-top: 3%;
}
</style>





<script>
import dateRangeModel from "./modals/dateRangeModal.vue";

export default {
  name: "filterMentors",

  data: () => ({
    showDialog: false
  }),

  methods: {

    respondToDateRangeModalCloseEvent: function(event){
        //should always close
        this.showDialog = false;
    }
    
  },

  components: {
    "date-range-modal": dateRangeModel
  }
};
</script>