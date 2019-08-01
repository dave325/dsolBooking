<template>
  <div id="app">
    <router-view></router-view>
  </div>
</template>

<script>

//import appointmentPage from './components/appointmentPage'
//import mentorProfile from './components/mentorProfile'
import frontPage from './components/frontPage';



export default {
  name: "app",
  el:"#app",
  components: {
    frontPage,
    //mentorProfile,
    //appointmentPage
  }
};
</script>

<style>
#app {
  font-family: "Avenir", Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
}

.standardRed{

}

.errorColor{
  color: red;
}

.loading{
  background-color: gold;
  color: white;
  font-weight: 500;
  text-align: center;
}

</style>
