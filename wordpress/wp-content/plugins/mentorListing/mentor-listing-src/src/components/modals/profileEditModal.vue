<template>
  <div>
    <md-dialog :md-active.sync="showModal">
      <form id="profileEditForm" @submit="checkForm" action method="post">
        <title><strong>Edit Profile</strong></title>
        <md-field>
          <label>first Name</label>
          <md-input v-bind:maxlength="maxShort" v-model="firstName_from"/>
        </md-field>
        <p> {{errors.firstName}} </p>
        <!--copy this form to other fields-->
        <md-field>
          <label>Last Name</label>
          <md-input v-bind:maxlength="maxShort" v-model="lastName_from"/>
        </md-field>
        <p> {{errors.lastName}} </p>
        <md-field>
          <label>Email</label>
          <md-input v-bind:maxlength="maxShort" v-model="email_from"/>
        </md-field>
        <p> {{errors.email}} </p>
        <md-field>
          <label>Bios</label>
          <md-textarea v-bind:maxlength="maxLength" v-model="bios_from"/>
        </md-field>
        <p> {{errors.bios}} </p>
        <md-field>
          <label>Degree</label>
          <md-input v-bind:maxlength="maxShort" v-model="degree_from"/>
        </md-field>
        <p> {{errors.degree}} </p>
        <md-field>
          <label>Specialty</label>
          <md-input v-bind:maxlength="maxShort" v-model="specialty_from"/>
        </md-field>
        <p> {{errors.specialty}} </p>
        <md-field>
          <label>Industry</label>
          <md-input v-bind:maxlength="maxShort" v-model="industry_from"/>
        </md-field>
        <p> {{errors.industry}} </p>

        <md-dialog-actions>
          <md-button class = "md-raised md-outline" @click ="closeModal()"> close </md-button>
          <md-button class="md-raised md-outline-primary" @click="handleDataFc()">Save Changes</md-button>
        </md-dialog-actions>
      </form>
    </md-dialog>
    <!-- modal -->
  </div>
</template>

<script>
import globals from "../../globals";

export default {
  name: "profileEditModal",
  props: {
    profile: {firstName: String, 
      lastName: String,
      email: String,
      bios: String,
      profilePic: String,
      yearsExperience: Number,
      skills: Array,
      degree: Number,
      specialty: String,
      industry: String,
      certifications: String
    }
  },
  data: function(){
    return{
      maxLength: 256,
      maxShort: 37,
      errors: {},
      firstName_from: null,
      lastName_from: null,
      email_from: null,
      bios_from: null,
      degree_from: null,
      specialty_from: null,
      industry_from: null,
      showModal: false
    }
  },
  watch: {
    firstName_from: function(val){
      if(val === ""){
        this.errors.firstName = "First name cannot be empty";
      }else{
        delete this.errors.firstName;
      }
    },
    lastName_from: function(val){
      if(val === ""){
        this.errors.lastName = "Last name cannot be empty";
      }else{
        delete this.errors.lastName;
      }
    },
    email_from: function(val){
      if(!globals.regExTests.emailRegex.test(val)){
        this.errors.email = "Invalid email address";
      }else{
        delete this.errors.email; 
      }
    }
  },
  
  methods: {
    checkForm: function() {
      if(this.firstName_from === ""){
        return false;
      } 
      if(this.lastName_from === ""){
        return false;
      }
      if(!globals.regExTests.emailRegex.test(this.email_from)){
        return false;
      }
      return true;
    },
    handleDataFc: function(event) {
      if(this.checkForm()){
        this.$emit('changed', this.firstName_from, this.lastName_from, this.email_from, this.bios_from, this.degree_from, this.specialty_from, this.industry_from);
        this.closeModal();
        //upload to database
        //reload page
      }
      
    },
    setModal: function(){
      this.errors = {};
      this.firstName_from = this.profile.firstName;
      this.lastName_from = this.profile.lastName;
      this.email_from = this.profile.email;
      this.bios_from = this.profile.bios;
      this.degree_from = this.profile.degree;
      this.specialty_from = this.profile.specialty;
      this.industry_from = this.profile.industry;

      this.showModal = true;
    },
    closeModal: function(){
      this.showModal = false;
    }

  }
};
</script>