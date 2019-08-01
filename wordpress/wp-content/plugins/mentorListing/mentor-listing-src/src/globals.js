/*
contains global constants used across the application
*/
const serverBaseUrl = 'http://localhost:80/wordpress/wp-json/mentor-listing/v1';
//'http://localhost:3000/wp-json/mentor-listing/v1';


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

export default {

    regExTests,
    serverBaseUrl,
    standardHttpPostOptions


}