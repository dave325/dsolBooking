// Note:

// def of a mentor:
// "mentor": {
// 		"mentorName": "John Lennon",
// 		"email": "JL@gmail.com"
// 	},
// 	"availableTime": {
// 		"startTime": "2019-01-01 09:00:00",
// 		"endTime": "2019-01-25 17:00:00",
// 		"recurring": 1
// 	},
// "skills": [
// 		{
// 			"skillName": "Freezing"
// 		},
// 		{
// 			"skillName": "Eating"
// 		},
// 		{
// 			"skillName": "Sleeping"
// 		}
// 	],
// 	"certification": [
// 		{
// 			"certificationName": "OPE Academy"
// 		},
// 		{
// 			"certificationName": "ZBF Academy"
// 		},
// 		{
// 			"certificationName": "EAK Academy"
// 		}
// 	]
// }

export default class fakeData{

    getRandomName(){

        let returnString, randomChar = "";
        let rsLength = Math.floor(Math.random()*8) + 2 //Returns a number between 2-10
        for(let i = 0; i < rsLength; i++){
            
            randomChar = String.fromCharCode(Math.floor(Math.random()*25) + 65);
            returnString += randomChar;
            
        } 
        return returnString;
    }
    getRandomEmail(){
        
        let email = getRandomName();
        switch(Math.floor(Math.random(4))){
            case 0:
                email += "@gmail.com";
                break;
            case 1:
                email += "@yahoo.com";
                break;
            case 2:
                email += "@something.com";
                break;
            case 3:
                email += "@yupyup.com";
                break;
        }
        return email;

    }


    genMentors(i) {

        mentors = [];
        let name;
        let email;

        // create a new mentor
        for(let j = 0; j < i; j++){

            

            let newEntry = {
                name : this.getRandomName(),
                email : this.getRandomEmail()
            }

            mentors.push(newEntry);

        }

    
        
    
        // return array
        return mentors;
    
    
    }
}
