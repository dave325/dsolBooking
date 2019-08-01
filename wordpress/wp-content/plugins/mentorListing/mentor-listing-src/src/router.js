  import Vue from 'vue'
  import Router from 'vue-router'
  import frontPage from './components/frontPage.vue';
  import mentorListing from './components/mentorListing.vue';
  import mentorProfile from './components/mentorProfile.vue';
  import dateRangeModal from './components/modals/dateRangeModal';
  import appointmentModal from './components/modals/appointmentModal.vue';
  import notFoundPage from './components/notFoundPage.vue';
  import profileEditModal from './components/modals/profileEditModal.vue';
  import aboutPage from './components/aboutPage.vue';
  import filterMentors from './components/filterMentors.vue';

  Vue.use(Router);

  export default new Router({
    mode: 'history',
    base: process.env.BASE_URL,
    routes: [
      {
        path: '/',
        name: 'frontPage',
        component: frontPage
      },
      {
        path: '/mentorProfile',
        name: 'mentorProfile',
        component: mentorProfile
      },
      {
        path: '/mentorListing',
        name: 'mentorListing',
        component: mentorListing
      },
      {
        path: '/dateRangeModal',
        name: 'dateRangeModal',
        component: dateRangeModal
      },
      {
        path: '/appointmentModal',
        name: 'appointmentModal',
        component: appointmentModal
      },
      {
        path: '/profileEditModal',
        name: 'profileEditModal',
        component: profileEditModal
      },
      {
        path: '/aboutPage',
        name: 'aboutPage',
        component: aboutPage
      },
      {
        path: '/filterMentors',
        name: 'filterMentors',
        component: filterMentors
      },
      {
        path: '*',
        name: 'notFound',
        component: notFoundPage
      }
    ]
  })
