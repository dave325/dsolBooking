<template>
  <div class="container-fluid">
    <md-dialog :md-active.sync="showModalLocal">
      <md-dialog-title>Filter Available Times</md-dialog-title>

      <md-tabs md-label="Filter Dates">
        <md-tab>
          <h5>Start Date</h5>
          <md-datepicker v-model="startDate" />
          <div class="value">{{startDate}}</div>
          <br />
          <h5>End Date</h5>
          <md-datepicker v-model="endDate" />
          <div class="value">{{endDate}}</div>
        </md-tab>
      </md-tabs>

      <md-dialog-actions>
        <md-button class="md-primary" @click="showModalLocal = false">Close</md-button>
        <md-button class="md-primary" @click="showModalLocal = false">Filter</md-button>
      </md-dialog-actions>
    </md-dialog>
  </div>
</template>

<script>
export default {
  //wrapping showModal in parens allows for objects to be returned
  //and not null
  data: () => ({
    //default the dialog to not show
    startDate: new Date(),
    endDate: new Date()
  }),
  props: { showModal: false },
  computed: {
    showModalLocal: {
      get() {
        return this.showModal;
      },
      set(newValue) {
        if (!newValue) {
          this.$emit("closeModal", false);
        }
      }
    }
  }
};
</script>

<style>
.md-dialog {
  max-width: 400px;
}
</style>
