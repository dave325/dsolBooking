<template>
  <div class="container-fluid">
    <filter-mentors></filter-mentors>
    <!-- don't delete me I am an example 
    <mentor-listing class = "col-md-4 " name = "md-example" description = "someDescription sdfasdfasdf"></mentor-listing>
    <mentor-listing class = "col-lg-3 " name = "lg-example" description = "someDescription sdfasdfasdf"></mentor-listing>
    -->

    <div class="row" v-if="this.mentorList">
      <mentor-listing
        v-for="mentor in this.mentorList[pageIndex]"
        :key="mentor.id"
        v-bind:name="mentor.mentor_name"
        v-bind:availableTimes="[{sessionStart:mentor.startTime,sessionEnd:mentor.endTime}]"
        v-bind:duration="[mentor.openTime,mentor.closeTime]"
      ></mentor-listing>

      <mentor-listing
        v-bind:name="'DEEPAK TEST MENTOR'"
        v-bind:availableTimes="[{day:'Monday',sessionStart:'12:00 PM',sessionEnd:'03:00 PM'}]"
        v-bind:duration="[new Date(),new Date()]"
      ></mentor-listing>
    </div>

    <nav>
      <ul class="pagination justify-content-center">
        <div v-for="(mentorPage,index) in this.mentorList" v-bind:key="mentorPage.id">
          <li class="page-item">
            <a class="page-link" @click="setMentorPage(index)">{{index}}</a>
          </li>
        </div>
      </ul>
    </nav>
  </div>
</template>


<script>
import mentorListing from "./mentorListing.vue";
import filterMentors from "./filterMentors.vue";
import MentorService from "../services/mentorService";
import fd from "../services/fakeData";


/*

MULTIPLY THE PAGE NUMBER BY NUMBER PER PAGE 
*/

export default {
  name: "frontPage",
  components: {
    mentorListing,
    filterMentors
  },

  data: () => ({
    mentorService: null,
    fakeData: null,
    mentorList: null,
    pageIndex: 0,
    numMentorsPerPage: 2
  }),

  watch: {
    $route: "fetchData"
  },

  created() {
    this.mentorService = new MentorService();
    this.mentorService.getAllMentors().then(res => {
      this.mentorList = res;

      let mentorCount = 0;
      this.mentorList = [];
      this.mentorList.push([]);
      res.forEach(mentor => {
        mentorCount++;
        if (mentorCount <= this.numMentorsPerPage) {
          this.mentorList[this.mentorList.length - 1].push(mentor);
        } else {
          this.mentorList.push([]);
          mentorCount = 0;

          mentorCount++;
          this.mentorList[this.mentorList.length - 1].push(mentor);
        }
      });

      console.log(this.mentorList);
    });
  },

  methods: {
    filterMentor(skill, firstname, lastname) {
      //starts with function
    },
    setMentorPage(index) {
      this.pageIndex = index;
    }
  }
};
</script>

<style>
</style>