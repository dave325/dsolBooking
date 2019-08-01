<template>
  <div class="mentorProfile">
    <div class="container" v-if="!hidden">
      <profileEditModal
        ref="edit"
        @clicked="profileChanged"
        :profile="{
          firstName: this.firstName, 
          lastName: this.lastName,
          email: this.email,
          bios: this.bios,
          profilePic: this.profilePic,
          yearsExperience: this.yearsExperience,
          skills: this.skills,
          degree: this.degree,
          specialty: this.specialty,
          industry: this.industry,
          certifications: this.certifications,
          appointments: this.appointments
        }"
      />
      <div class="row">
        <!-- make space -->
        <!-- <div class = "col-1">  
        </div>-->

        <div class="col-md-4 profile-picture">
          <img src="../assets/headshot.jpg" alt="profile picture" />
        </div>

        <!-- make space -->
        <!-- <div class = "col-1">
        </div>-->

        <div class="about col-md-8">
          <h2>{{firstName}} {{lastName}}</h2>
          <p>{{email}}</p>
          <div>
            <span v-if="specialty">{{specialty}}, </span>
            <span v-if="industry">{{industry}}</span>
          </div>
        </div>
      </div>
      <br />

      <div v-if="degree">
        <h3>Degree</h3>
        <p class="description">{{degree}}</p>
      </div>

      <div v-if="bios">
        <h3>Bios</h3>
        <p class="description">{{bios}}</p>
      </div>

      <div v-if="skills">
        <h3>Skills</h3>
        <div class="card-container">
          <div class="row">
            <p
              class="col-4 list-group-item"
              v-for="(skill,index) in skills"
              v-bind:key="skill.id"
              v-bind:index="index"
              v-bind:skill="skill"
            >{{skill}}</p>
          </div>
        </div>
      </div>

      <br>
      
      <div v-if="certifications">
        <h3>Certifications</h3>
        <div class="card-container">
          <div class="row">
            <p
              class="col-4 list-group-item"
              v-for="(cert, index) in certifications"
              v-bind:key="cert.id"
              v-bind:index="index"
              v-bind:cert="cert"
            >{{cert}}</p>
          </div>
        </div>
      </div>

      <br>

      <!-- TODO: render edit info with passed-in values-->
      <md-button
        type="button"
        class="btn btn-outline-primary btn-sm float-right"
        @click="showModal()"
      >Edit</md-button>
    </div>
    <br/>
    <br/>

    <hr color="black" />
    <!-- TODO: book a room feature -->
    <br />
    <div class="col-12">
      <md-card md-with-hover v-on:click="bookRoom" class="md-elevation-3">
        <md-ripple>
          <md-card-content class="text-center">Book a Room</md-card-content>
        </md-ripple>
      </md-card>
    </div>
    <br />
    <!-- TODO: display current session available -->
    <br />
    <div v-if="appointments">
      <ul class="col-12">
        <li class="md-elevation-1" v-for="appointment in appointments" v-bind:key="appointment.id">
          <md-card>
            <md-card-header class="text-center">{{appointment.location}}</md-card-header>
            <md-card-content class="text-center">{{appointment.time}}</md-card-content>
          </md-card>
        </li>
      </ul>
    </div>
    <div class="container" v-else>
      <p class="text-center">Hidden Profile</p>
    </div>
  </div>
</template>



<script>
import profileEditModal from "./modals/profileEditModal";
import MentorService from "../services/mentorService";

export default {
  //get rid of defaults
  name: "mentorProfile",
  props: {
    hidden: { type: Boolean, default: false },
    firstName: { type: String, default: "john" },
    lastName: { type: String, default: "smith" },
    email: { type: String, default: "dongsoochung99@gmail.com" },
    bios: {
      type: String,
      default:
        "Lorem ipsum dolor sit amet, consectetur adipisicing elit. Error quibusdam, non molestias et! Earum magnam, similique, quo recusandae placeat dicta asperiores modi sint ea repudiandae maxime? Quae non explicabo, neque."
    },
    profilePic: String,
    yearsExperience: Number,
    skills: { default: () => ["return", "heel", "level", "comp", "another"] },
    degree: { type: String, default: "Computer Science" },
    specialty: { type: String, default: "front End" },
    industry: { type: String, default: "web development" },
    certifications: { default: () => ["docker", "agile development"] },
    appointments: {
      default: () => [
        { location: "Madison square", time: "7:15" },
        { location: "Penn station", time: "15:15" },
        { location: "Queens College", time: "17:30" }
      ]
    }
  },
  data: function() {
    return {
      edit: false,
      inputProfile: null
    };
  },
  methods: {
    bookRoom: function() {},
    profileChanged: function() {
      //refresh data from database
    },
    showModal: function() {
      this.$refs.edit.setModal();
    }
  },
  created() {
    this.mentorService = new MentorService();
    this.mentorService.getMentorProfile(1).then(res => {
      this.inputProfile = res;
      console.log(res);
    });
  },
  components: {
    profileEditModal
  }
};
</script>

<style>
/* * {
  outline: solid red 1px;
} */
h3 {
  margin-bottom: none;
}
.about {
  /* text-align: center; */
  vertical-align: center;
}
.description {
  margin-left: 15px;
}
.card-container {
  margin-left: 23px;
  margin-bottom: 10px;
}
.profile-picture {
  margin-left: 0%;
}
.mentorProfile {
  margin-top: 100px; /*needs to be changed -- left exadurated to remind to fix*/
}
.center-block {
  display: block;
  margin-left: auto;
  margin-right: auto;
}
ul {
  list-style-type: none;
}
</style>

<!-- 

Notes for Peter 

  - The code was good ( I like the fake data that you added)
  - I would suggest you look into not using so many if statements and find an easier alternative
  - Remember that 1 big if statement makes it better if multiple elements rely on it

-->

<!-- 
  Notes for Peter

    - For the showModal function an easier way would be to store all of the info in an object
      and call a function from the modal that takes in all the properties from that singular object
      We do not need to set all of that so many times. 
    - We want to have the edit button in a more noticable place as well
    - The modal should not be fhe full width. It looks out of place that way. 

-->