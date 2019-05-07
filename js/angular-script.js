angular.module('wp', ['ngRoute', 'ui.bootstrap', 'ngAnimate'])
  .config(function ($routeProvider, $locationProvider) {
    $routeProvider
      .when('/', {
        templateUrl: localized.partials + '/showRooms.html',
        controller: 'Main',
        resolve: {
          TIMES: ['$http', function ($http) {
            return $http.post(localized.path + '/wp-json/dsol-booking/v1/test', { name: 'David ' }).then((res) => {
              let reservations = res.data;
              let validTimes = [];
              const curDate = moment().hours(6).minute(0).seconds(0).milliseconds(0);
              let j = 0;
              const tempDate = moment().hours(6).minute(0).seconds(0).milliseconds(0)
              for (var m = curDate; m.isSame(Date.now(), "day"); m.add(30, 'minutes')) {
                tempDate.add(30, "minutes");
                let hasAdded = false;
                if (m.isSameOrAfter(Date.now(), "minute")) {
                  /**
                   * Loop through reservations and check if time is avilable
                   * If it is in reesrvation, flip boolean and add reservation idx
                   */
                  reservations.forEach((el, idx) => {
                    if (m.isBetween(moment(el.start_time).subtract(1, "hours"), el.end_time, "minute")) {
                      validTimes.push({
                        start_time: m.format('h:mm ss A'),
                        end_time: tempDate.format('h:mm ss A'),
                        available: false,
                        reservation: idx,
                        place: j,
                        selected: true
                      });
                      hasAdded = true;
                      return;
                    }
                  });
                  /**
                   * If not in reservations, add to valid times with no idx
                   */
                  if (!hasAdded) {
                    validTimes.push({
                      start_time: m.format('h:mm A'),
                      end_time: tempDate.format('h:mm A'),
                      available: true,
                      place: j,
                      selected: false
                    });

                  }
                  hasAdded = false;
                } else {
                  validTimes.push({
                    start_time: m.format('h:mm A'),
                    end_time: tempDate.format('h:mm A'),
                    available: false,
                    place: j,
                    selected: false,
                    past: true
                  });
                }
                // Safety net in case moment calculates date wrong 
                if (j > 40) {
                  break;
                }
                j++;
              }
              let rooms;
              return $http.post(localized.path + '/wp-json/dsol-booking/v1/getRoomInfo').then((res) =>{
                rooms = res.data;
                return {
                  times: validTimes,
                  reservations: reservations,
                  rooms: rooms
                };
              },(err) => {
                console.log(err);
              });
              
            }, (err) => {
              console.log(err);
            });
          }]
        }
      })
      .when('/submit', {
        templateUrl: localized.partials + '/submitForm.html',
        controller: 'SubmitForm'
      })
      .when('/profile',{
        templateUrl:  localized.partials + '/profile.html',
        controller: 'profile'
      })
      .when('/confirmation', {
        templateUrl: localized.partials + '/confirmation.html',
        controller: 'confirmation'
      })
  })
  .controller('Main', function ($scope, TIMES, $http, myFactory, $location) {
    $scope.oneAtATime = true;

    $scope.validTimes = TIMES.times;
    $scope.reservations = TIMES.reservations;
    $scope.rooms = TIMES.rooms;
    $scope.isCollapsed = false;
    $scope.data = myFactory.getData;
    $scope.togglePast = function(){
      $scope.validTimes.forEach((ele)=>{
        if(ele.past != undefined){
          ele.past = !ele.past;
        }
      })
    }
    $scope.$watch('dt',function(){
      myFactory.setDate($scope.dt);
    })
    $scope.selectRoom = function(idx){
      myFactory.setRoom($scope.rooms[idx].r_id);
      $scope.data.room = $scope.rooms[idx].container_number;
      $scope.isCollapsed = true;
      console.log($scope.data);
    }
    $scope.check = function (curChecked) {
      /* are there only two checked boxes? */
      //alert();
      var hourChecks = document.getElementsByName('hours[]');
      var boxArr = [];
      var boxCount = 0;
      var lastItem = false;
      // count total boxes checked
      for (var t = 0, checkLength = hourChecks.length; t < checkLength; t++) {
        if ((hourChecks[t].type == 'checkbox') && hourChecks[t].checked == true) {
          boxArr[boxCount++] = t;
          if ($scope.validTimes[t].available) {
            $scope.validTimes[t].selected = true;
          }
        }
      }

      // is this unchecking - clear under
      if (hourChecks[curChecked].checked == false && curChecked < boxArr[0]) {
        hourChecks[curChecked].checked = false;
        $scope.validTimes[curChecked].selected = false;
      } else if (hourChecks[curChecked].checked == false) {
        for (var t = curChecked, checkLength = hourChecks.length; t < checkLength; t++) {
          // Check if box array contains still contains the invalid checkmarks and remove them
          if (boxArr.indexOf(t) > -1) {
            boxArr.splice(boxArr.indexOf(t), 1);
          }
          $scope.validTimes[t].selected = false;
          hourChecks[t].checked = false;
        }
        // is checked box higher? clear underneath (after first)
      } else if (hourChecks[curChecked].checked == true && boxArr[1] > curChecked) {
        var chkstat = true;
        $scope.validTimes[curChecked].selected = true;
        for (var t = curChecked, checkLength = hourChecks.length; t < checkLength; t++) {
          hourChecks[t].checked = chkstat;
          $scope.validTimes[t].selected = false;
          chkstat = false;

        }
        // are there multiple and this is the first? just uncheck it
      } else if (boxArr.length > 1) {
        for (var s = boxArr[0] + 1, e = boxArr[boxArr.length - 1]; s < e; s++) {
          var curHour = document.getElementById('hours_' + s);

          if (curHour.value == false) {
            hourChecks[curChecked].checked = false;
            $scope.validTimes[curChecked].selected = false;
            alert("Error!\nI'm sorry, but there is already a reservation in the time you've selected. Please make sure your reservation times don't overlap someone else's reservation.");
            break;
          } else {
            hourChecks[s].checked = true;
            $scope.validTimes[s].selected = true;
          }
        }
      }
      // If box has more than one item in it display the time 
      if (boxArr.length > 1) {
       // let start = jQuery('#hours_' + [boxArr[0]]).parent().parent().siblings('.calTime').children().text().trim().split('-')[0];
       // let end = jQuery('#hours_' + [boxArr[boxArr.length - 1]]).parent().parent().siblings('.calTime').children().text().trim().split('-')[1];
        $scope.data.arr[0] = $scope.validTimes[boxArr[0]];
        $scope.data.arr[1] = $scope.validTimes[boxArr[boxArr.length - 1]];
       // jQuery('#topSubmit').children('div').children('span').text('Time: ' + start + ' - ' + end);
      } else if (boxArr.length == 1) {
       // let start = jQuery('#hours_' + [boxArr[0]]).parent().parent().siblings('.calTime').children().text().trim().split('-')[0];
       // let end = jQuery('#hours_' + [boxArr[0]]).parent().parent().siblings('.calTime').children().text().trim().split('-')[1];
       // jQuery('#topSubmit').children('div').children('span').text('Time: ' + start + ' - ' + end);
       $scope.data.arr[0] = $scope.validTimes[boxArr[0]];
      }
      console.log($scope.data.arr);
    }
    var getRemanningDays = function () {
      var date = new Date();
      var time = new Date(date.getTime());
      time.setMonth(date.getMonth() + 1);
      time.setDate(0);
      var days = time.getDate() > date.getDate() ? time.getDate() - date.getDate() : 0;
      return days;
    }
    $scope.today = function () {
      $scope.dt = new Date();
    };
    $scope.today();

    $scope.clear = function () {
      $scope.dt = null;
    };
    $scope.options = {
      customClass: getDayClass,
      minDate: moment(),
      maxDate: moment().add(getRemanningDays(), "days"),
      showWeeks: false
    };

    // Disable weekend selection
    function disabled(data) {
      var date = data.date,
        mode = data.mode;
      return mode === 'day' && (date.getDay() === 0 || date.getDay() === 6);
    }

    $scope.setDate = function (year, month, day) {
      $scope.dt = new Date(year, month, day);
    };

    var tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    var afterTomorrow = new Date(tomorrow);
    afterTomorrow.setDate(tomorrow.getDate() + 1);
    $scope.events = [
      {
        date: tomorrow,
        status: 'full'
      },
      {
        date: afterTomorrow,
        status: 'partially'
      }
    ];
    function getDayClass(data) {
      var date = data.date,
        mode = data.mode;
      if (mode === 'day') {
        var dayToCheck = new Date(date).setHours(0, 0, 0, 0);

        for (var i = 0; i < $scope.events.length; i++) {
          var currentDay = new Date($scope.events[i].date).setHours(0, 0, 0, 0);

          if (dayToCheck === currentDay) {
            return $scope.events[i].status;
          }
        }
      }

      return '';
    }

    $scope.submit = function () {
      let info = $scope.validTimes.filter((item) => {
        if (item.selected === true) {
          return true;
        }
        return false;
      })
      if (info.length > 0) {
        myFactory.setArr(info);
        myFactory.setDate($scope.dt);
        console.log(myFactory.getData);
        myFactory.storeInfo();
        $location.path('/submit');
      }
    }
  }).controller('SubmitForm', function ($scope, $http, myFactory,$location) {
    if (myFactory.retrieveInfo()) {
      $scope.info = {
        numAttend: myFactory.getData.numAttend,
        desc: myFactory.getData.desc
      }
    } else {
      $scope.info = {
        numAttend: 0,
        desc: ''
      };
    }
    $scope.submit = function () {
      if ($scope.info.desc &&
        $scope.info.desc.length > 0 &&
        $scope.info.numAttend &&
        $scope.info.numAttend > 0
      ) {
        myFactory.setNumAttend($scope.info.numAttend);
        myFactory.setDesc($scope.info.desc);
        myFactory.storeInfo();
        console.log(myFactory.retrieveInfo());
        $http.post(localized.path + '/wp-json/dsol-booking/v1/bookRoom', myFactory.getData, { headers:{'X-WP-Nonce':localized.nonce}}).then(
          (res) => {
            console.log(res);
           // myFactory.removeData();
           $location.path('/confirmation');
          }, (err) => {
            console.log(err);
          }
        )
      }
    }
  }).factory('myFactory', function factory($http) {
    let data;
    if (window.sessionStorage.getItem('userData')) {
      data = angular.fromJson(window.sessionStorage.getItem('userData'));
    } else {
      data = {
        arr: [],
        date: new Date(),
        numAttend: 0,
        desc: '',
        room: "",
        nonce: localized.nonce
      };
    }
    var service = {
      getData: data,
      setArr: setArr,
      setDate: setDate,
      setNumAttend: setNumAttend,
      setDesc: setDesc,
      setRoom: setRoom,
      storeInfo: storeInfo,
      retrieveInfo: retrieveInfo,
      removeData:removeData
    };
    function setArr(arr) {
      data.arr = arr;
    }
    function setDate(date) {
      data.date = date;
    }
    function setNumAttend(numAttend) {
      if(numAttend<=0) {
        data.numAttend = 1;
        return;
      } 
      data.numAttend = numAttend;
    }
    function setDesc(desc) {
      data.desc = desc;
    }

    function setRoom(room){
      data.room = room;
    }

    function storeInfo() {
      window.sessionStorage.setItem('userData', JSON.stringify(data));
    }
    function retrieveInfo() {
      return angular.fromJson(window.sessionStorage.getItem('userData'));
    }
    function removeData() {
      window.sessionStorage.removeItem('userData')
    }
    return service;
  }).controller('profile', function ($scope, $http, myFactory) {
    if (myFactory.retrieveInfo()) {
      $scope.info = {
        numAttend: myFactory.getData.numAttend,
        desc: myFactory.getData.desc
      }
    } else {
      $scope.info = {
        numAttend: 0,
        desc: ''
      };
    }
    $scope.submit = function () {
      if ($scope.info.desc &&
        $scope.info.desc.length > 0 &&
        $scope.info.numAttend &&
        $scope.info.numAttend > 0
      ) {
        myFactory.setNumAttend($scope.info.numAttend);
        myFactory.setDesc($scope.info.desc);
        myFactory.storeInfo();
        console.log(myFactory.retrieveInfo());
        $http.post(localized.path + '/wp-json/dsol-booking/v1/parse', myFactory.retrieveInfo()).then(
          (res) => {
            console.log(res);
            myFactory.removeData();
          }, (err) => {
            console.log(err);
          }
        )
      }
    }
  });