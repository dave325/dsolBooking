angular.module('wp', ['ngRoute', 'ui.bootstrap', 'ngAnimate'])
  .config(function ($routeProvider, $locationProvider, $locationProvider) {
    $locationProvider.html5Mode(true);
    $routeProvider
      .when('/', {
        templateUrl: localized.partials + '/showRooms.html',
        controller: 'Main',
        resolve: {
          TIMES: ['restapi', 'myFactory',
            /**
             * 
             * @param {*} restapi 
             * @param {*} myFactory 
             * @returns res object {times, reservations, rooms}
             */
            function (restapi, myFactory) {

              let date = Date.now();
              if (myFactory.getData.arr.length > 0) {
                date = new Date(myFactory.getData.date);
              }
              console.log('reach')
              return restapi.times(date).then(
                (res) => {
                  // Store user information from php passed object 
                  // php object recieved from wp_localized_script
                  res.user = localized.username;
                  return res;
                },
                (err) => {
                  console.error(err);
                }
              );
            }],
          USERDATA: ["restapi", "$location",
            /**
             * 
             * @param {*} restapi 
             * @param {*} $location 
             * @returns res object {times}
             */
            function (restapi, $location) {
              // Checks to see if query parameter exists and then make api call
              if ($location.search().hasOwnProperty("action") && $location.search()['action'] == "profile") {
                return restapi.getUserReservations().then(
                  (res) => {
                    // Loop through returned data and transform each start and end date to unix
                    res.data.forEach((el, idx) => {
                      /**
                       * Need to update and check fo multiple columns
                       */
                      el.start_time = el.start_time.split(",")
                    });
                    return res.data;
                  },
                  (err) => {
                    console.error(err)
                  }
                )
              }
            }]
        }
      })
      .when('/submit', {
        templateUrl: localized.partials + '/submitForm.html',
        controller: 'SubmitForm'
      })
      .when('/profile', {
        templateUrl: localized.partials + '/profile.html',
        controller: 'profile',
        resolve: {

        }
      })
      .when('/confirmation', {
        templateUrl: localized.partials + '/confirmation.html'
      })

  })

  .controller('Main', ['$scope', 'TIMES', '$timeout', 'myFactory', '$location', 'restapi', function ($scope, TIMES, $timeout, myFactory, $location, restapi) {
    //$scope.oneAtATime = true;
    // Stores the valid Times from the resolved object in the route
    $scope.validTimes = TIMES.times;
    // Stores the reservations from the resolved object in the route
    $scope.reservations = TIMES.reservations;
    // Stores the room from the resolved object in the route
    $scope.rooms = TIMES.rooms;
    // Set the current user and reservations to a factory object for reuse
    myFactory.setUser(TIMES.user);
    myFactory.setReservations(TIMES.reservations);
    // Sets the collapsable dropdown of room
    $scope.isCollapsed = false;
    // store the data from factory into local scope variable
    $scope.data = myFactory.getData;
    // store the options of the repeat dropdown 
    $scope.selectData = {
      availableOptions: [
        { id: '0', name: 'No Repeat' },
        { id: '1', name: 'Daily' },
        { id: '2', name: 'Weekly' },
        { id: '3', name: 'Biweekly' }
      ]
    };
    // If query aparamete res_id does not exist, then reset room info and valid times
    if (!$location.search().hasOwnProperty("res_id") || myFactory.getData.arr.length == 0) {
      if (!myFactory.getData.room.c_id) {
        $scope.resRepeat = { id: '0', name: 'No Repeat' };
        $scope.roomShow = $scope.rooms[0].container_number;
        myFactory.setRoom($scope.rooms[0]);
        $scope.togglePast = function () {
          $scope.validTimes.forEach((ele) => {
            if (ele.past != undefined) {
              ele.past = !ele.past;
            }
          })
        }
      }
    }
    // Sets submit button disable feature
    $scope.isDisabled = false;

    // Change the factory repeat when user changes the dropdown
    $scope.selectDataChange = function () {
      $scope.data.repeat = $scope.resRepeat;
      myFactory.setRepeat($scope.resRepeat)
    }

    // Set the factory date based on what the user selects on the calendar
    $scope.$watch('dt', function () {
      // I the same data is selected, do nothing
      if (moment(myFactory.getData.date).isSame(moment($scope.dt))) {
        console.error("same");
      } else {
        myFactory.setDate($scope.dt);
        // make api call with new date in mind
        restapi.times(new Date($scope.dt)).then(
          (res) => {
            // change valid times and reset selected times 
            $scope.validTimes = res.times;
            console.log(res)
            $scope.data.arr = [];
          },
          (err) => {
            console.error(err);
          }
        )
      }
    })

    /**
     * @params idx int 
     */
    $scope.selectRoom = function (idx) {
      // Check if data in factory is same as selected room and do nothing
      if (myFactory.getData.room.c_id == $scope.rooms[idx].c_id) {
        console.warn("same room");
        return;
      }
      // Set room in factory
      myFactory.setRoom($scope.rooms[idx]);
      // Set info that is displayed to users
      $scope.roomShow = $scope.rooms[idx].container_number;
      // Make api call with new room check
      restapi.times(new Date($scope.dt), $scope.rooms[idx].c_id).then(
        (res) => {
          // change valid times and reset selected times 
          $scope.validTimes = res.times;
          console.log(res);
          $scope.data.arr = [];
        },
        (err) => {
          console.error(err);
        }
      )
      // Collapse room selection box
      $scope.isCollapsed = true;
    }

    /**
     * Call when user changes a checkbox on time select field
     */
    $scope.check = function (obj) {
      /* are there only two checked boxes? */
      var hourChecks = document.getElementsByName('hours[]');
      var boxArr = [];
      var boxCount = 0;
      var lastItem = false;
      const curChecked = obj.place;
      for (var t = 0, checkLength = hourChecks.length; t < checkLength; t++) {
        if ((hourChecks[t].type == 'checkbox') && hourChecks[t].checked == true) {
          boxArr[boxCount++] = t;
          // Id time is available then select it and set class
          if ($scope.validTimes[t].available) {
            $scope.validTimes[t].selected = true;
            angular.element('#hours_' + t).parent().parent().addClass('selected');

          }
        }
      }

      // is this unchecking - clear under
      if (hourChecks[curChecked].checked == false && curChecked < boxArr[0]) {
        hourChecks[curChecked].checked = false;
        $scope.validTimes[curChecked].selected = false;
        angular.element('#hours_' + curChecked).parent().parent().removeClass('selected');
      } else if (hourChecks[curChecked].checked == false) {

        for (var t = curChecked, checkLength = hourChecks.length; t < checkLength; t++) {
          // Check if box array contains still contains the invalid checkmarks and remove them
          if (boxArr.indexOf(t) == -1) {
            boxArr.splice(boxArr.indexOf(t), 1);
          }
          $scope.validTimes[t].selected = false;
          hourChecks[t].checked = false;
          angular.element('#hours_' + t).parent().parent().removeClass('selected');
        }
        // is checked box higher? clear underneath (after first)
      } else if (hourChecks[curChecked].checked == true && boxArr[1] > curChecked) {
        var chkstat = true;
        for (var t = curChecked, checkLength = hourChecks.length; t < checkLength; t++) {
          hourChecks[t].checked = chkstat;
          $scope.validTimes[t].selected = chkstat;
          if (chkstat) {
            angular.element('#hours_' + t).parent().parent().addClass('selected');
          } else {
            angular.element('#hours_' + t).parent().parent().removeClass('selected');
          }
          chkstat = false;
        }
        // are there multiple and this is the first? just uncheck it
      } else if (boxArr.length > 1) {
        for (var s = boxArr[0] + 1, e = boxArr[boxArr.length - 1]; s < e; s++) {
          var curHour = document.getElementById('hours_' + s);
          if (curHour.value == false) {
            hourChecks[s].checked = false;
            $scope.validTimes[s].selected = false;
            angular.element('#hours_' + s).parent().parent().removeClass('selected');
            alert("Error!\nI'm sorry, but there is already a reservation in the time you've selected. Please make sure your reservation times don't overlap someone else's reservation.");
            break;
          } else {
            if ($scope.validTimes[s].available) {
              hourChecks[s].checked = true;
              $scope.validTimes[s].selected = true;
              angular.element('#hours_' + s).parent().parent().addClass('selected');
            } else {
              hourChecks[s].checked = false;
              $scope.validTimes[s].selected = false;
              angular.element('#hours_' + s).parent().parent().removeClass('selected');
            }
          }
        }
      }
      // If box has more than one item in it display the time 
      if (boxArr.length > 1) {
        $scope.data.arr[0] = $scope.validTimes[boxArr[0]];
        $scope.data.arr[1] = $scope.validTimes[boxArr[boxArr.length - 1]];
      } else if (boxArr.length == 1) {
        $scope.data.arr[0] = $scope.validTimes[boxArr[0]];
        $scope.data.arr[1] = $scope.validTimes[boxArr[0]];
      }
    }

    /**
     * Check the amount of days in a given month
     * Limits amount of days a usre can book
     */
    var getRemaningDays = function () {
      var date = new Date();
      var time = new Date(date.getTime());
      time.setMonth(date.getMonth() + 1);
      time.setDate(0);
      var days = time.getDate() > date.getDate() ? time.getDate() - date.getDate() : 0;
      return days + 7;
    }

    // Set the current date
    $scope.today = function () {
      //set date to current date
      let date = new Date();
      console.log(myFactory.getData)
      // If the user is editing, store date as given time
      if ($location.search().hasOwnProperty("res_id") || myFactory.getData.arr.length > 0) {
        date = new Date(myFactory.getData.date);
      }
      // Store date for calendar and 
      $scope.dt = date;
    };
    // Call the today function
    $scope.today();

    /**
     * When user clicks clear button, remove the time in the calendar
     */
    $scope.clear = function () {
      $scope.dt = null;
    };

    // Sets the options for the calendar
    $scope.options = {
      customClass: getDayClass,
      minDate: moment(),
      maxDate: moment().add(getRemaningDays(), "days"),
      showWeeks: false
    };

    // Disable weekend selection
    function disabled(data) {
      var date = data.date,
        mode = data.mode;
      return mode === 'day' && (date.getDay() === 0 || date.getDay() === 6);
    }

    // Set the date using a date object
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

    /**
     * 
     * @param {*} data 
     */
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

    /**
     * Submit event listener 
     * Function when user clicks submit button
     */
    $scope.submit = function () {
      // Filter results of user data to all avialable times
      let info = $scope.validTimes.filter((item) => {
        if (item.selected === true && item.available) {
          return true;
        }
        return false;
      })
      myFactory.getData.seperateIndexes = [0];
      for(let i = info[0].place, j = 0; j < info.length; i++, j++){
        if(info[j].place != i){
          myFactory.getData.isSeperate = 1;
          myFactory.getData.seperateIndexes.push(j)
          i = info[j].place;
        }
      }
      console.log(myFactory.getData);
      // Check if times field is stores
      if (info.length > 0) {
        // Disable submit button
        $scope.isDisabled = true;
        // Store infor in factory
        myFactory.setArr(info);
        myFactory.setDate($scope.dt);
        myFactory.storeInfo();
        // Send to submit page
        $location.path('/').search('action', 'submit');
      }
    }

    //reset boxes if user reloads page
    $scope.$on('$viewContentLoaded',
      function () {
        // call timeout so angularjs digrest waits for the next round after ngRepeat calls
        $timeout(function () {
          console.log(myFactory.getData)
          if (myFactory.getData.arr.length > 0) {
            // Set data 
            let resId = -1;
            let date = new Date();
            // If user is editing post
            if ($location.search().hasOwnProperty('res_id')) {
              // set resId to query parameter and date
              resId = $location.search()['res_id'];

            } else {
              if (myFactory.getData.arr.length == 0) {
                $scope.data.arr = [];
              } else {
                date = myFactory.getData.date;
              }
            }
            // Call api with given date, room, and reservation id
            restapi.times(date, myFactory.getData.room.c_id, resId).then(
              (res) => {
                // set room
                myFactory.setRoom(myFactory.getData.room);
                $scope.roomShow = myFactory.getData.room.container_number;
                $scope.resRepeat = myFactory.getData.repeat;
                $scope.validTimes = res.times;
                // Call timeout to wait for digest to render times with new $scope data
                $timeout(function () {
                  let hourChecks = document.getElementsByName('hours[]');
                  $scope.togglePast = function () {
                    $scope.validTimes.forEach((ele) => {
                      if (ele.past != undefined) {
                        ele.past = !ele.past;
                      }
                    })
                  }
                  // If user is not editing or has no times selected
                  if (myFactory.getData.arr.length > 0 && resId < 0) {
                    if (myFactory.getData.arr.length === 1) {
                      // Call timeout to wait for digest one more time
                      $timeout(function () {
                        // Place checks where needed
                        hourChecks[myFactory.getData.arr[0].place].checked = true;
                        $scope.validTimes[myFactory.getData.arr[0].place].selected = true;
                        angular.element('#hours_' + myFactory.getData.arr[0].place).parent().parent().addClass('selected');
                      }, 500);
                    } else {
                      // Call time out to wait for digest one more time
                      $timeout(function () {
                        // Loop through array and select all relevant boxes
                        for (var s = myFactory.getData.arr[0].place, e = myFactory.getData.arr[$scope.data.arr.length - 1].place; s <= e; s++) {
                          if ($scope.validTimes[s].available) {
                            hourChecks[s].checked = true;
                            $scope.validTimes[s].selected = true;
                            angular.element('#hours_' + s).parent().parent().addClass('selected');
                          }
                        }
                      }, 500);
                    }
                  } else if (myFactory.getData.arr.length == 0) {
                    return;
                  } else {
                    // Store moment variables from factory data
                    let curDate = moment.unix(myFactory.getData.arr[0]).hours(6).minute(0).seconds(0).milliseconds(0);
                    let resStartTimestamp = moment.unix(myFactory.getData.arr[0]);
                    let resEndTimestamp = moment.unix(myFactory.getData.arr[myFactory.getData.arr.length - 1]);
                    let i, m = curDate;
                    // Call timeout to wait for digest
                    $timeout(function () {
                      // Using momentjs, loop through the current time and increment by 30 minuets
                      for (m = curDate, i = 0; m.isBefore(resEndTimestamp); m.add(30, 'minutes')) {
                        // Check if current moment is between the 2 times and then select validTimes
                        if (m.isBetween(resStartTimestamp.subtract(1, "minutes"), resEndTimestamp)) {
                          if ($scope.validTimes[i].available) {
                            hourChecks[i].checked = true;
                            $scope.validTimes[i].selected = true;
                            angular.element('#hours_' + i).parent().parent().addClass('selected');
                          }
                        }
                        if (m.isAfter(resEndTimestamp)) {
                          break;
                        }
                        i++;
                      }
                    }, 500);
                  }
                }, 1000);
              },
              (err) => {
                console.error(err);
              }
            )
          } else {
            var hourChecks = document.getElementsByName('hours[]');
            if ($scope.data.arr.length > 0) {
              if ($scope.data.arr.length === 1) {
                $timeout(function () {
                  hourChecks[$scope.data.arr[0].place].checked = true;
                  $scope.validTimes[$scope.data.arr[0].place].selected = true;
                  angular.element('#hours_' + myFactory.getData.arr[0].place).parent().parent().addClass('selected');
                }, 500);
              } else {
                $timeout(function () {
                  for (var s = $scope.data.arr[0].place, e = $scope.data.arr[$scope.data.arr.length - 1].place; s <= e; s++) {
                    hourChecks[s].checked = true;
                    $scope.validTimes[s].selected = true;
                    angular.element('#hours_' + s).parent().parent().addClass('selected');
                  }
                }, 500);
              }
            }
          }
        }, 0)
      }
    );
  }])

  .controller('SubmitForm', function ($scope, $http, myFactory, $location, restapi) {
    // Store factory data in scope
    $scope.data = myFactory.getData;
    // Store username data
    $scope.username = localized.username.data.display_name;
    $scope.isDisabled = false;
    // Check if factory data is set 
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
    $scope.invalidDates;
    // Check if data time length is greater than 1
    if ($scope.data.arr.length > 1) {
      // Store valid dates if repeat is selected
      let dates = restapi.checkRepeatReservations($scope.data.arr[0].start_time, $scope.data.arr[$scope.data.arr.length - 1].end_time, $scope.data.room.c_id, $scope.data.repeat);
      myFactory.setMultipleDates(dates.validDates);
      $scope.invalidDates = dates.invalidDates;
    } else {
      // Store valid dates if repeat is selecteds
      let dates = restapi.checkRepeatReservations($scope.data.arr[0].start_time, $scope.data.arr[0].end_time, $scope.data.room.c_id, $scope.data.repeat);
      myFactory.setMultipleDates(dates.validDates);
      $scope.invalidDates = dates.invalidDates;
    }

    /**
     * Submite form
     */
    $scope.submit = function () {
      // Check if user data fields are added
      if ($scope.info.desc &&
        $scope.info.desc.length > 0 &&
        $scope.info.numAttend &&
        $scope.info.numAttend > 0 &&
        $scope.info.numAttend <= myFactory.getData.room.occupancy
      ) {
        console.log(myFactory.getData)
        // Disbale submit 
        $scope.isDisabled = true;
        // set factory data
        myFactory.setNumAttend($scope.info.numAttend);
        myFactory.setDesc($scope.info.desc);
        myFactory.storeInfo();
        // Check if res_id is not selected and make new api call
        if (!$location.search().hasOwnProperty('res_id')) {
          // Make api call to book room
          $http.post(localized.path + '/wp-json/dsol-booking/v1/bookRoom', myFactory.getData, { headers: { 'X-WP-Nonce': localized.nonce } }).then(
            (res) => {
              console.log(res);
              // remove data from factory
              myFactory.removeData();
              // send page to confirmation
              $location.path('/').search('action', 'confirmation');
            }, (err) => {
              console.error(err);
            }
          )
        } else {
          // Add edit route
          myFactory.getData.res_id = parseInt($location.search()['res_id']);
          // Make edit call
          restapi.editUserReservation(myFactory.getData).then(
            (res) => {
              myFactory.removeData();
              $location.path('/?action=confirmation');
            }, (err) => {
              console.error(err);
              myFactory.setRoom($scope.data.room);
            }
          )
        }
      } else {

      }
    }
  })

  .controller('profile', function ($scope, USERDATA, restapi, $location, myFactory) {
    // Set user 
    myFactory.setUser(localized.username);
    // Store user in local scope
    $scope.user = localized.username;
    // Store data from resolve provider
    $scope.data = USERDATA;
    // Store current res_id from checkboxes
    $scope.items = [];
    // Set lastCheck for editRes info
    $scope.lastCheck;
    /**
     * @params idx
     */
    $scope.selectedBox = function (idx) {
      // When selecting box, check if res Id is selected
      if ($scope.items.includes($scope.data[idx].res_id)) {
        // If id exists, remove the res id from the items array
        $scope.items = $scope.items.filter(function (value, index, arr) {
          if (value == $scope.data[idx].res_id) {
            return false;
          } else {
            return true;
          }

        });
      } else {
        // Push res_id into items array 
        $scope.items.push($scope.data[idx].res_id);
        // Set lastCheck index
        $scope.lastCheck = idx;
      }
    }

    /**
     * Delete user when they click on delete button
     * Make api call and then reset items array
     */
    $scope.deleteUserReservation = function () {
      restapi.deleteUserReservation($scope.items).then(
        (res) => {
          $scope.data = $scope.data.filter(function (value, index, arr) {
            return !$scope.items.includes(value.res_id);
          });
          $scope.items = [];
        },
        (err) => {
          console.error(err)
        }
      )
    }
    /**
     * Edit user 
     */
    $scope.editUserReservation = function () {

      // Set tempRoom variable 
      let tempRoom = {
        "c_id": $scope.data[$scope.lastCheck].c_id,
        "r_id": $scope.data[$scope.lastCheck].r_id,
        "container_number": $scope.data[$scope.lastCheck].container_number,
        "occupancy": $scope.data[$scope.lastCheck].occupancy
      };
      // Set temp Time variable
      let tempEditUserTime = $scope.data[$scope.lastCheck].start_time;

      // Set factory information and store information
      myFactory.setArr(tempEditUserTime);
      myFactory.setRoom(tempRoom);
      myFactory.setDate(new Date($scope.data[$scope.lastCheck].start_time[0] * 1000));
      myFactory.setNumAttend(parseInt($scope.data[$scope.lastCheck].attendance));
      myFactory.setRepeat({ id: '0', name: 'No Repeat' });
      myFactory.setDesc($scope.data[$scope.lastCheck].notes);
      myFactory.setReservations($scope.data);
      myFactory.storeInfo();

      // Remove action info and semd to book page
      $location.search('action', null);
      $location.path('/').search('res_id', $scope.items[0]);
    }
  })


  .factory('myFactory', function factory() {
    let data;
    //Check if user already has data stored
    if (window.sessionStorage.getItem('userData')) {
      data = angular.fromJson(window.sessionStorage.getItem('userData'));
    } else {
      data = {
        arr: [],
        date: new Date(),
        numAttend: 0,
        desc: '',
        room: {},
        repeat: { id: '0', name: 'No Repeat' },
        reservations: [],
        multipleDates: [],
        nonce: localized.nonce,
        user: {},
        isSeperate: 0,
        seperateIndexes: [0]
      };
    }
    // Expose data and functions
    var service = {
      getData: data,
      setArr: setArr,
      setReservations: setReservations,
      setMultipleDates: setMultipleDates,
      setUser: setUser,
      setDate: setDate,
      setNumAttend: setNumAttend,
      setDesc: setDesc,
      setRoom: setRoom,
      setRepeat: setRepeat,
      storeInfo: storeInfo,
      retrieveInfo: retrieveInfo,
      removeData: removeData
    };
    function setArr(arrCopy) {
      data.arr = arrCopy;
    }
    function setReservations(res) {
      data.reservations = res;
    }
    function setMultipleDates(dates) {
      data.multipleDates = dates;
    }
    function setDate(date) {
      data.date = date;
    }
    function setNumAttend(numAttend) {
      if (numAttend <= 0) {
        data.numAttend = 1;
        return;
      }
      data.numAttend = numAttend;
    }
    function setDesc(desc) {
      data.desc = desc;
    }

    function setRoom(room) {
      data.room = room;
    }

    function setRepeat(repeat) {
      data.repeat = repeat;
    }
    function setUser(user) {
      data.user = user;
    }

    function storeInfo() {
      window.sessionStorage.setItem('userData', JSON.stringify(data));
    }
    function retrieveInfo() {
      return angular.fromJson(window.sessionStorage.getItem('userData'));
    }
    function removeData() {
      window.sessionStorage.removeItem('userData');
      data = {
        arr: [],
        date: new Date(),
        numAttend: 0,
        desc: '',
        room: {},
        repeat: { id: '0', name: 'No Repeat' },
        reservations: [],
        multipleDates: [],
        nonce: localized.nonce,
        user: {},
        isSeperate:0
      };
    }
    return service;
  })

  /**
   * Call api endpoints on wordpres backend
   */
  .factory('restapi', function ($http, myFactory) {
    function checkValidUser() {
      return $http.get(localized.path + '/wp-json/dsol-booking/v1/getUser', { headers: { 'X-WP-Nonce': localized.nonce } });
    }

    /**
     * 
     * @param {*} startTime 
     * @param {*} endTime 
     * @param {*} room 
     * @param {*} repeatType 
     */
    function checkRepeatReservations(startTime, endTime, room, repeatType) {
      let validDates = [],
        invalidDates = [];
      const curDate = moment.unix(new Date())
      const m = moment.unix(startTime)
      const tempDate = moment.unix(endTime);
      const checkDate = moment(new Date()).add('1','months');
      // Check id of repeat
      switch (repeatType.id) {
        // if daily
        case "1":
          // Check if currend date is the same as m
          while (m.isSame(curDate, "month") ||(m.isSame(checkDate,'month') && m.date() <= 7)) {
            console.log(m.date())
            // boolean variable to see if adding things 
            let canAdd = false;
            // Loop through each reservation
            for (let i = 0; i < myFactory.getData.reservations.length; i++) {
              // Store individual reservation
              let el = myFactory.getData.reservations[i];
              if (
                // Check if m is the same day as starttime, 
                // room is came, m is between start and end time
                // and tempDate is between the start and end time
                // set can Add to false
                m.isSame(el.start_time, "day") &&
                el.c_id == room &&
                m.isBetween(el.start_time, el.end_time) ||
                tempDate.isBetween(el.start_time, el.end_time)
              ) {
                canAdd = false;
                break;
              } else if (
                // Check if m is not the same day as starttime, 
                // room is came, m is between start and end time
                // and tempDate is between the start and end time
                // set can Add to false
                !m.isSame(el.start_time, "day") &&
                el.c_id == room &&
                m.isBetween(el.start_time, el.end_time) ||
                tempDate.isBetween(el.start_time, el.end_time)
              ) {
                canAdd = false;
                break;
              } else {
                // Set flag to true
                canAdd = true;
              }

            }
            // If date is not possible, set time to invalidDate
            if (!canAdd) {
              invalidDates.push(m.unix(startTime));
            } else {
              validDates.push(m.unix(startTime));
            }
            // Increase m by 1 day
            m.add('1', "days");
          }
          break;
        // Check weekly
        case "2":
          // Loop while m is in same month
          while (m.isSame(curDate, "month") ||(m.isSame(checkDate,'month') && m.date() <= 7)) {
            let canAdd = false;
            for (let i = 0; i < myFactory.getData.reservations.length; i++) {
              let el = myFactory.getData.reservations[i];
              if (
                // Check if m is the same day as starttime, 
                // room is came, m is between start and end time
                // and tempDate is between the start and end time
                // set can Add to false
                m.isSame(el.start_time, "day") &&
                el.c_id == room &&
                m.isBetween(el.start_time, el.end_time) ||
                tempDate.isBetween(el.start_time, el.end_time)
              ) {
                canAdd = false;
                break;
              } else if (
                // Check if m is not the same day as starttime, 
                // room is came, m is between start and end time
                // and tempDate is between the start and end time
                // set can Add to false
                !m.isSame(el.start_time, "day") &&
                el.c_id == room &&
                m.isBetween(el.start_time, el.end_time) ||
                tempDate.isBetween(el.start_time, el.end_time)
              ) {
                canAdd = false;
                break;
              } else {
                canAdd = true;
              }

            }
            if (!canAdd) {
              invalidDates.push(m.unix(startTime));
            } else {
              validDates.push(m.unix(startTime));
            }
            // Increase m by 1 day
            m.add('1', "weeks");
          }
          break;
        case "3":
          while (m.isSame(curDate, "month")||(m.isSame(checkDate,'month') && m.date() <= 7)) {
            let canAdd = false;
            for (let i = 0; i < myFactory.getData.reservations.length; i++) {
              let el = myFactory.getData.reservations[i];
              if (
                m.isSame(el.start_time, "day") &&
                el.c_id == room &&
                m.isBetween(el.start_time, el.end_time) ||
                tempDate.isBetween(el.start_time, el.end_time)
              ) {
                canAdd = false;
                break;
              } else if (
                !m.isSame(el.start_time, "day") &&
                el.c_id == room &&
                m.isBetween(el.start_time, el.end_time) ||
                tempDate.isBetween(el.start_time, el.end_time)
              ) {
                canAdd = false;
                break;
              } else {
                canAdd = true;
              }

            }
            if (!canAdd) {
              invalidDates.push(m.unix(startTime));
            } else {
              validDates.push(m.unix(startTime));
            }
            m.add('2', "weeks");
          }
          break;
        default:

          break;
      }
      return { "validDates": validDates, "invalidDates": invalidDates };
    }
    function getTimes(date, room, resid) {
      var givenDate = new Date();
      var room, residEdit = -1;
      if (room) {
        room = room;
      } else {
        room = -1;
      }
      if (resid && resid > -1) {
        residEdit = resid;
      }
      if (!moment().isSame(date)) {
        givenDate = date;
      }
      return $http.post(localized.path + '/wp-json/dsol-booking/v1/test', { room: room }, { headers: { 'X-WP-Nonce': localized.nonce } }).then(
        (res) => {
          return $http.post(localized.path + '/wp-json/dsol-booking/v1/getRoomInfo').then(
            (roomData) => {
              let rooms = roomData.data;
              let reservations = res.data;

              let validTimes = [];
              let j = 0;
              const curDate = moment(givenDate).hours(6).minute(0).seconds(0).milliseconds(0);
              const tempDate = moment(givenDate).hours(6).minute(0).seconds(0).milliseconds(0);
              if (room < 0) {
                room = rooms[0].c_id;
              }
              for (let m = curDate; m.isSame(givenDate, "day"); m.add(30, 'minutes')) {
                tempDate.add(30, "minutes");
                let hasAdded = false;
                if (m.isSameOrAfter(givenDate, "minute")) {
                  /**
                   * Loop through reservations and check if time is avilable
                   * If it is in reesrvation, flip boolean and add reservation idx
                   */

                  reservations.forEach((el, idx) => {
                    if (resid && resid > -1 && el.res_id == residEdit) {
                      validTimes.push({
                        //start_time: m.format('h:mm ss A'),
                        //end_time: tempDate.format('h:mm ss A'),
                        start_time: m.unix(),
                        end_time: tempDate.unix(),
                        available: true,
                        place: j,
                        selected: false
                      });
                      hasAdded = true;
                    }
                    /*
                      if (m.isSame(el.start_time, "day") && m.isBetween(moment(el.start_time).subtract(1, "m"), moment(el.end_time).subtract(1, "m"))
                        && el.c_id == room
                      )
                      */
                    if (!hasAdded) {
                      if (el.time.length > 1) {
                        for (let resTime = 0; resTime < el.time.length; resTime++) {
                          let resCurTime = el.time[resTime];
                          if (m.isSame(resCurTime.start_time, "day") && m.isBetween(moment(resCurTime.start_time).subtract(1, "m"), moment(resCurTime.end_time).subtract(1, "m"))
                            && el.c_id == room
                          ) {
                            validTimes.push({
                              start_time: m.unix(),
                              end_time: tempDate.unix(),
                              available: false,
                              reservation: idx,
                              place: j,
                              selected: true
                            });
                            hasAdded = true;
                            break;
                          }
                        }
                      } else {
                        if (m.isSame(el.time[0].start_time, "day") && m.isBetween(moment(el.time[0].start_time).subtract(1, "m"), moment(el.time[0].end_time).subtract(1, "m"))
                          && el.c_id == room
                        ) {
                          validTimes.push({
                            start_time: m.unix(),
                            end_time: tempDate.unix(),
                            available: false,
                            reservation: idx,
                            place: j,
                            selected: true
                          });
                          hasAdded = true;
                        }
                      }
                    }
                    /*
                    Timestamp implementation
                    if (el.start_time.split(",").includes(m.unix().toString()) && el.c_id == room) {
                      validTimes.push({
                        start_time: m.unix(),
                        end_time: tempDate.unix(),
                        available: false,
                        reservation: idx,
                        place: j,
                        selected: true
                      });
                      hasAdded = true;
                      return;
                    }
                    */
                  });

                  /**
                   * If not in reservations, add to valid times with no idx
                   */
                  if (!hasAdded) {
                    validTimes.push({
                      //start_time: m.format('h:mm ss A'),
                      //end_time: tempDate.format('h:mm ss A'),
                      start_time: m.unix(),
                      end_time: tempDate.unix(),
                      available: true,
                      place: j,
                      selected: false
                    });

                  }
                  hasAdded = false;
                } else {
                  if (m.isSame(Date.now(), "day")) {
                    validTimes.push({
                      //start_time: m.format('h:mm ss A'),
                      //end_time: tempDate.format('h:mm ss A'),
                      start_time: m.unix(),
                      end_time: tempDate.unix(),
                      available: false,
                      place: j,
                      selected: false,
                      past: true
                    });
                  } else {
                    /**
                   * Loop through reservations and check if time is avilable
                   * If it is in reesrvation, flip boolean and add reservation idx
                   */
                    reservations.forEach((el, idx) => {

                      /*
                    if (m.isSame(el.start_time, "day") && m.isBetween(moment(el.start_time).subtract(1, "m"), moment(el.end_time).subtract(1, "m"))
                      && el.c_id == room
                    )
                    */
                      if (el.time.length > 1) {
                        for (let resTime = 0; resTime < el.time.length; resTime++) {
                          let resCurTime = el.time[resTime];
                          if (m.isSame(resCurTime.start_time, "day") && m.isBetween(moment(resCurTime.start_time).subtract(1, "m"), moment(resCurTime.end_time).subtract(1, "m"))
                            && el.c_id == room
                          ) {
                            validTimes.push({
                              start_time: m.unix(),
                              end_time: tempDate.unix(),
                              available: false,
                              reservation: idx,
                              place: j,
                              selected: true
                            });
                            hasAdded = true;
                            break;
                          }
                        }
                      } else {
                        if (m.isSame(el.time[0].start_time, "day") && m.isBetween(moment(el.time[0].start_time).subtract(1, "m"), moment(el.time[0].end_time).subtract(1, "m"))
                          && el.c_id == room
                        ) {
                          validTimes.push({
                            start_time: m.unix(),
                            end_time: tempDate.unix(),
                            available: false,
                            reservation: idx,
                            place: j,
                            selected: true
                          });
                          hasAdded = true;
                        }
                      }
                      /**
                       * Timestamp implementation - Old code
                       *
                      if (el.start_time.split(",").includes(m.unix().toString()) && el.c_id == room) {
                        validTimes.push({
                          start_time: m.unix(),
                          end_time: tempDate.unix(),
                          available: false,
                          reservation: idx,
                          place: j,
                          selected: true
                        });
                        hasAdded = true;
                        return;
                      }
                      */
                    });
                    if (!hasAdded) {
                      validTimes.push({
                        //start_time: m.format('h:mm ss A'),
                        //end_time: tempDate.format('h:mm ss A'),
                        start_time: m.unix(),
                        end_time: tempDate.unix(),
                        available: true,
                        place: j,
                        selected: false
                      });
                    }
                  }
                  hasAdded - false;
                }
                // Safety net in case moment calculates date wrong 
                if (j > 40) {
                  break;
                }
                j++;
              }
              return {
                times: validTimes,
                reservations: reservations,
                rooms: rooms
              };
            }, (err) => {
              console.error(err);
            });

        }, (err) => {
          console.error(err);
        });
    }

    function getUserReservations() {
      return $http.post(localized.path + '/wp-json/dsol-booking/v1/getReservations', { user: localized.username }, { headers: { 'X-WP-Nonce': localized.nonce } });
    }

    function editUserReservation(info) {
      return $http.post(localized.path + '/wp-json/dsol-booking/v1/editUserReservation', { info: info }, { headers: { 'X-WP-Nonce': localized.nonce } });
    }

    function deleteUserReservation(item) {
      return $http.post(localized.path + '/wp-json/dsol-booking/v1/deleteUserResrvation', { items: item }, { headers: { 'X-WP-Nonce': localized.nonce } });
    }

    var service = {
      times: getTimes,
      checkRepeatReservations: checkRepeatReservations,
      checkValidUser: checkValidUser,
      getUserReservations: getUserReservations,
      editUserReservation: editUserReservation,
      deleteUserReservation: deleteUserReservation
    };

    return service;
  })


  .run(function ($rootScope, $location, $route) {
    $rootScope.$on("$routeChangeStart", function ($event, next, current) {
      // handle route changes     
      switch ($location.search()['action']) {

        case 'profile':
          next.$$route.controller = "profile"
          next.templateUrl = localized.partials + '/profile.html';
          break;
        case 'submit':
          next.$$route.controller = "SubmitForm"
          next.templateUrl = localized.partials + '/submitForm.html';
          break;
        case 'confirmation':
          next.templateUrl = localized.partials + '/confirmation.html';
          break;
        default:
          next.$$route.controller = "Main"
          next.templateUrl = localized.partials + '/showRooms.html';
          break;
      }
    });
  });