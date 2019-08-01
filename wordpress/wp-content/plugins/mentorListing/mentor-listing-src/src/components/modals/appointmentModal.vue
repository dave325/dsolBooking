<template>
  <div>
    <md-dialog :md-active.sync="showModalLocalWatch">
      <md-dialog-title>Make An Appointment</md-dialog-title>

      <p class="errorColor" v-if="!allValuesSubmittedWatch">Please fill out all fields</p>
      <p
        class="errorColor"
        v-if="errors.submissionError"
      >There was an error submitting your appointment, please try again</p>

      <div v-if="loadingResponse" class="loading">
        <h4>Sending your request...</h4>
      </div>

      <form>
        <md-field>
          <label for="name">First Name</label>
          <md-input
            id="firstName"
            v-model="firstName"
            type="text"
            name="firstName"
            :minlength="nameMin"
            :maxlength="nameMax"
          />
        </md-field>
        <p class="errorColor">{{errors.firstName}}</p>

        <md-field>
          <label for="name">Last Name</label>
          <md-input
            id="firstName"
            v-model="lastName"
            type="text"
            name="firstName"
            :minlength="nameMin"
            :maxlength="nameMax"
          />
        </md-field>
        <p class="errorColor">{{errors.lastName}}</p>

        <md-field>
          <label for="name">Phone Number</label>
          <md-input
            id="firstName"
            v-model="phone"
            type="text"
            name="firstName"
            :minlength="phoneMin"
            :maxlength="phoneMax"
          />
        </md-field>
        <p class="errorColor">{{errors.phone}}</p>

        <md-field>
          <label for="name">Email</label>
          <md-input
            id="firstName"
            v-model="email"
            type="text"
            name="firstName"
            :minlength="emailMin"
            :maxlength="emailMax"
          />
        </md-field>
        <p class="errorColor">{{errors.email}}</p>
      </form>

      <md-dialog-actions>
        <md-button @click="showModalLocalWatch = false" v-if="!loadingResponse" class="model">Close</md-button>
        <md-button @click="submitModal()" class="model" v-if="!loadingResponse">Submit</md-button>
      </md-dialog-actions>
    </md-dialog>
  </div>
</template>

<script>
import globals from "../../globals";
import MentorService from "../../services/mentorService";

export default {
  props: {
    showModal: false
  },

  data: function() {
    return {
      errors: {
        submissionError: null,
        missingFieldError: null,
        firstNameError: null,
        lastNameError: null,
        phoneError: null,
        emailError: null
      },
      firstName: null,
      lastName: null,
      email: null,
      phone: null,
      mentorService: null,
      nameMax: 36,
      phoneMax: 10,
      emailMax: 40,
      nameMin: 2,
      phoneMin: 10,
      emailMin: 7,
      loadingResponse: false,
      initialState: {}
    };
  },

  created() {
    this.mentorService = new MentorService();
    let errorsDeepCopy = Object.assign({},this.errors);
    Object.assign(this.initialState,this.$data);
    this.initialState.errors = errorsDeepCopy;
  },

  //In Vue, props are passed in and events are passed out
  //Components should not be allowed to modify props passed in, only the parent
  //therefore showModal is passed in and showModalLocal is a computed function that will set local state (modalOpen) based on the value

  computed: {
    showModalLocalWatch: {
      get() {
        return this.showModal;
      },
      set(val) {
        if (!val && !this.loadingResponse) {
          console.log(this.errors);

          this.resetModal();

          console.log(this.errors);

          this.$emit("closeModal", false);
        }
      }
    },

    //vm isnt accessible but we still need to call the watch function elsewhere to make sure the form is
    //ok to be submitted
    //therefore it is delegated to allValuesSubmitted

    allValuesSubmittedWatch() {
      return this.allValuesSubmitted();
    }
  },

  watch: {
    firstName(val) {
      if (val === "") {
        this.errors.firstName = "Please enter your first name.";
      } else {
        this.errors.firstName = null;
      }
    },
    lastName(val) {
      if (val === "") {
        this.errors.lastName = "Please enter your last name.";
      } else {
        this.errors.lastName = null;
      }
    },
    email(val) {
      if (!globals.regExTests.emailRegex.test(val) && val != null) {
        this.errors.email = "Please enter a valid email";
      } else {
        this.errors.email = null;
      }
    },
    phone(val) {
      if (!globals.regExTests.phoneRegex.test(val) && val != null) {
        this.errors.phone = "Please enter a valid phone";
      } else {
        this.errors.phone = null;
      }
    }
  },

  methods: {
    allValuesSubmitted() {
      return this.phone && this.email && this.firstName && this.lastName;
    },
    formCheck() {
      let noErrors = true;
      Object.keys(this.errors).forEach(key => {
        if (this.errors[key] != null) {
          noErrors = false;
        }
      });
      return noErrors && this.allValuesSubmitted();
    },

    resetModal() {
      Object.assign(this.$data , this.initialState);
      Object.assign(this.errors, this.initialState);
    },

    submitModal() {
      console.log("calling submit function");
      this.errors.submissionError = null;

      if (this.formCheck()) {
        console.log("data successfully passed submission check");

        let formData = {
          name: this.firstName + " " + this.lastName,
          email: this.email,
          phone: this.phone
        };

        this.loadingResponse = true;
        this.mentorService.bookAppointmentWithMentor(formData).then(
          res => {
            //tell the parent to set the flag to false
            this.errors.submissionError = null;
            this.resetModal();
            this.loadingResponse = false;
            this.$emit("closeModal", false);
          },
          err => {
            this.loadingResponse = false;
            this.errors.submissionError = true;
          }
        );
      }

      //set the data fields to null or empty from the parent
    }
  }
};
</script>

<style>
</style>

<!--
Notes for Karanvir

  - This is what we were lookign for with the validation and placement of the html form
  - Please look through the documentation for vue first, remember with frameworks they will have 
    a million different ways of doing something. Sometimes it may not be right.
  - We need this styledand completed accortding to the info we need


-->