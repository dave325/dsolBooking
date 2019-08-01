<!-- KARANVIR - CREATED APPOINTMENT COMPONENT FILE, HAS ONLY COPY AND PASTED WHAT WAS IN THE MENTOR 
LISTING FILE AS A BASIS, NO EDITS HAVE BEEN DONE-->



<!--

TODO this is a duplicated appointmentModal component, do not use, instead use appointmentModal.vue


-->

<template>
  <md-card md-with-hover>
    <div class="mentor_list">

      <md-dialog :md-active.sync="showDialog">
        <div class="modal-mask">
          <div class="modal-wrapper">
            <div class="modal-container">
      
          <div class="modal-first">
            <slot name="slot-first">
              First name
            </slot>
        </div>

        <div class="modal-last">
            <slot name="slot-last">
              Last name
            </slot>
        </div>
        
        <div class="modal-email">
            <slot name="slot-email">
              Email
            </slot>
        </div>

        <div class="modal-time">
            <slot name="slot-time">
              Meeting time
            </slot>
        </div>
        </div>
      </div>
      <md-button class = "model" @click="showDialog = true"> model</md-button>
    </div>
    </md-dialog>

        
      <div class="mentor_container">
        <md-card-header>
        
        <div class = "row"> 
          <h2 class="title col-md-8">{{name}}</h2>
          <md-card-media>
            <img src="../assets/smile.png" alt="People">
          </md-card-media>
          <div class="md-subhead">Title or Company</div>
          
        
          <md-card-action>
            <div class="model col-md-4">
               <md-button class="md-primary md-raised" @click="showDialog = true">Show Dialog</md-button>
            </div>
          </md-card-action>
        </div>
        </md-card-header>
      </div>
      <!-- <div class = "row"> -->
        <div class = "col-md-12">
          <div class="description_container">
            <!-- make this a v-show -->
            <h2 class="title">Description</h2>

            <md-card-content>
              <p class="about">{{description}}</p>
            </md-card-content>
          </div>
        </div>
      <!-- </div> -->
    </div>
  </md-card>
</template> 

<script>   
export default {
  name: "mentorListing",
  props: {
    name: String,
    subject: String,
    time: String,
    description: String,
  },

  data: () => ({
    showDialog: false
  })
};

</script>

<style>
*{
  margin: 0;
}

.mentor_list {
  /* margin: 0%; */
  border: 2px solid black;
}

.mentor_container {
  border: 2px solid black; 
}
/* .title {
  margin-left: 2%;
} */
/* .about {
  margin-left: 5%;
} */

.description_container {
  border: 2px solid black;
}
.button_container {
  float: right;
  width: 20%;
  border: 2px solid black; 
} 
</style>