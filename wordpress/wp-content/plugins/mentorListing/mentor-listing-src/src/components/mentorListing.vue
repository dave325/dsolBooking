<template>
  <!-- 3 cards per row -->

  <div class="col-12 col-sm-4 col-lg-3 mb-">
    <md-card md-with-hover >
      <!-- the actual card  -->
      <!-- KARANVIRS CODE, MODAL BUTTON IS COMPLETE, CODE JUST NEEDS TO BE REVIWED AND CLEANED UP-->
      <!--LINES 5 - 38 -->
      <appointment-modal :showModal="showDialog" @closeModal="respondToModalCloseRequest"></appointment-modal>

      <!-- Need this div for the horizontal card format -->

      <!-- <md-card-header> -->
      <md-card-media md-medium sm-small>
        <img class="col-12 noPaddingNoMargin" src="../assets/headshot.jpg" alt="People" />
      </md-card-media>
      <!-- </md-card-header> -->

      <md-card-header-text>
        <div class="col-12 card-header text-body">
          <h2 class="card-title">{{name}}</h2>
          <h3 class="md-subhead card-subtitle mb-2">Title or Company</h3>
        </div>
      </md-card-header-text>

      <md-card-content class="card-body">
        <!-- <div class = "row"> -->
        <!-- make this a v-show -->
        <h4 class="card-title text-body">Available Times</h4>
        <p class="card-text text-body"></p>
      </md-card-content>

      <!-- End of col-12 col-md-8 div -->
      <md-button
        type="button"
        class="btn btn-outline-secondary buttonBackground btn-sm"
        @click="showDialog = true"
      >Make Appointment</md-button>

      <!-- End of no gutters -->
    </md-card>
  </div>
</template> 

<script>
import appointmentModal from "./modals/appointmentModal.vue";
import MentorService from "../services/mentorService";

export default {
  name: "mentorListing",
  props: {
    name: {
      type: String,
      default: "Edward"
    },
    subject: String,
    time: String,
    availableTimes: Array,
    duration: Array
  },

  created() {},

  watch: {},

  data: () => ({
    showDialog: false
  }),
  components: {
    appointmentModal
  },
  methods: {
    respondToModalCloseRequest: function(val) {
      this.showDialog = val;
    }
  }
};
</script>

<style>



.buttonBackground {
  background-color: white !important;
}

.noPaddingNoMargin{
  padding: 0 !important;
  margin: 0 !important;
}

img{
  max-width: 100% !important;
}

/* .card-img {
  max-height: 170px !important;
  max-width: 170px !important;
} */
/*
.cardContainer img{

  height: 200px !important;
  width: 175px !important;
  max-height: 200px !important;
  max-width: 175px !important;
  
}

/*
.mentor_list {
  /* margin: 0%; *
  border: 2px solid black;
}

.mentor_container {
  border: 2px solid black; 
}
/* .title {
  margin-left: 2%;
} *
/* .about {
  margin-left: 5%;
} *

.description_container {
  border: 2px solid black;
}
.button_container {
  float: right;
  width: 20%;
  border: 2px solid black; 
} 
*/
</style>
<!--
Notes for Edward

  - Less is more!
  - You had good code, a lot of it could be condensed (see above)
  - Sometimes it is better in HTML to combine the classes in the Elements
  - I did not make a big point of this, but we want to use the bootstrap css styles with the components from vue material
    - The above has a large example of this


-->

<!---
  Edward Notes

    - The classes should be from Bootstrap, not material


-->