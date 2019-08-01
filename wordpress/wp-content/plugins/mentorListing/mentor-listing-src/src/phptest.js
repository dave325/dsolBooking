console.log(localized);

Vue.use(VueMaterial.default)
/*
contains global constants used across the application
*/
//const serverBaseUrl = 'http://localhost:80/wordpress/wp-json/mentor-listing/v1';
//'http://localhost:3000/wp-json/mentor-listing/v1';
const serverBaseUrl = localized.path + "/wp-json/mentor-listing/v1";

      //TODO ADD THE GET TOKEN FUNCTION 
const standardHttpPostOptions = {
    method: 'POST', 
    headers:{
      'Accept': 'application/json',
      'Content-Type': 'application/json'
    }
} 


const regExTests = {
    emailRegex: /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/,
    phoneRegex: /^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/

}

const globals = {

    regExTests,
    serverBaseUrl,
    standardHttpPostOptions


}
class MentorService {


    bookAppointmentWithMentor(formData) {   
        let bookAppointmentRoute = '/listing/create';

        let options = Object.assign({},globals.standardHttpPostOptions);
        options.body = JSON.stringify(formData);

        return fetch(globals.serverBaseUrl + bookAppointmentRoute, options).then(

            (res) => res.json().catch(
                (err) => {
                    console.log(err);
                    return err;
                }
            )
        );

        /**
    *   Required info: 
    *    - startTime
    *    - endTime
    *    - email
    *    - name
    *    - phone
    */
        


    }



    getAllMentors() {

        let getAllMentorsRoute = '/listings';


        return fetch(globals.serverBaseUrl + getAllMentorsRoute, globals.standardHttpPostOptions).then(

            (res) => res.json().catch(
                (err) => {
                    console.log(err);
                    return err;
                }
            )
        );
    }

    getMentorProfile(id){

        let getMentorProfile = `/profile/${id}`;

        return fetch(globals.serverBaseUrl + getMentorProfile, globals.standardHttpPostOptions).then(
            (res) => res.json().catch(
                (err) => {
                    console.log(err);
                    return err;
                }
            )
        );

    }

    postMentorProfile(formData){
        let postMentorProfile = `/profile/edit`;
        let options = Object.assign({}, globals.standardHttpPostOptions);
        options.body = JSON.stringify(formData);

        return fetch(globals.serverBaseUrl + postMentorProfile, options).then(
            (res) => res.json().catch(
                (err) => {
                    console.log(err);
                    return err;
                }
            )
        );
    }
}
Vue.component(
    'appointment-modal',
    {
        name: "appointment-modal",
        template:`
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
        `,
        props: {
          name: String,
          subject: String,
          time: String,
          description: String,
        },
      
        data: () => ({
          showDialog: false
        })
    }
)
Vue.component(
    'mentor-listing',
    {
        name: "mentor-listing",
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
        template: `
        <div class="col-12 col-sm-6 col-lg-4">
        <!-- 3 cards per row -->
        <md-card md-with-hover class="card mb-3 py-2 border-primary">
          <!-- the actual card  -->
          <!-- KARANVIRS CODE, MODAL BUTTON IS COMPLETE, CODE JUST NEEDS TO BE REVIWED AND CLEANED UP-->
          <!--LINES 5 - 38 -->
          <appointment-modal :showModal="showDialog" @closeModal="respondToModalCloseRequest"></appointment-modal>
    
          <!-- Need this div for the horizontal card format -->
          
          <!-- <md-card-header> -->
            <md-card-media class = "row" md-medium sm-small>
              <img class = "col-12" src="../assets/headshot.jpg" alt="People">
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
        `,
        created() { },

        watch: {},

        data: () => ({
            showDialog: false
        }),
        methods: {
            respondToModalCloseRequest: function (val) {
                this.showDialog = val;
            }
        }
    }
)

Vue.component(
    'front-page',
    {
        name: "front-page",
        template: `
        <div class="container-fluid">
        <!-- don't delete me I am an example 
        <mentor-listing class = "col-md-4 " name = "md-example" description = "someDescription sdfasdfasdf"></mentor-listing>
        <mentor-listing class = "col-lg-3 " name = "lg-example" description = "someDescription sdfasdfasdf"></mentor-listing>
        -->
    
        <div class="row">
          <mentor-listing
            v-for="mentor in this.mentorList"
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
      </div>
        `,
        data: () => ({
            mentorService: null,
            fakeData: null,
            mentorList: null
        }),

        watch: {
            $route: "fetchData"
        },

        created() {
            this.mentorService = new MentorService();
            this.mentorService.getAllMentors().then(res => {
                console.log(res);
                this.mentorList = res;
            });

        }
    }
)
const app = new Vue({
    el: "#app",
    data: {
        message: 'Hello Vue!'
    }
})