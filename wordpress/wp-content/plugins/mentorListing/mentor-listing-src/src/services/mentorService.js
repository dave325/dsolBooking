import globals from "../globals"


export default class MentorService {


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
        
            /*
        return new Promise(function(resolve, reject) {

            if(window.sessionStorage.getItem('mentor') == null){
                this.getMentorProfile(id).then(
                    res => window.sessionStorage.setItem('mentor', {})
                )
            }
            resolve(window.sessionStorage.getItem('mentor'));

        });
        */


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
        
        /*
        return new Promise(function(resolve, reject) {
            sessionStorage.setItem('mentor', formData);
            resolve(true);
        })
        */
    }
}