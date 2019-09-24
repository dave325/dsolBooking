angular.module('wp', ['ngRoute', 'ngAnimate', 'ui.bootstrap'])
  .controller('Main', ['$scope', '$timeout', 'myFactory', '$location', 'restapi', function ($scope, $timeout, myFactory, $location, restapi) {
    //$scope.oneAtATime = true;
    // /angular.element(document.getElementById('loading')).hide();
    // Stores the valid Times from the resolved object in the route
    $scope.validTimes = [];
    // Stores the reservations from the resolved object in the route
    $scope.reservations = [];
    // Stores the room from the resolved object in the route
    $scope.rooms = [];
    // Set the current user and reservations to a factory object for reuse
    myFactory.setUser(localized.user);
    //myFactory.setReservations(TIMES.reservations);
    // Sets the collapsable dropdown of room
    $scope.isCollapsed = false;
    // store the data from factory into local scope variable
    $scope.data = myFactory.getData;
    $scope.loading = false;
    $scope.gif = localized.assets + "/loading.gif";
    // store the options of the repeat dropdown 
    $scope.selectData = {
      availableOptions: [
        { id: '0', name: 'No Repeat' },
        { id: '1', name: 'Daily' },
        { id: '2', name: 'Weekly' },
        { id: '3', name: 'Biweekly' }
      ]
    };

    $scope.isEdit;

    $scope.info = null;
    function init() {
      $scope.loading = true;
      let resId = -1;
      let date = Date.now();
      let room = 1;
      console.log(myFactory.getData)
      if (myFactory.getData.arr.length > 0) {
        // Set data 

        date = new Date();
        var urlParams = new URLSearchParams(window.location.search);
        // If user is editing post
        if (urlParams.get('res_id')) {
          // set resId to query parameter and date
          resId = urlParams.get('res_id');
          $scope.isEdit = true;
          date = myFactory.getData.date;
          room = myFactory.getData.room.c_id;
        } else {
          $scope.isEdit = false;
          if (myFactory.getData.arr.length == 0) {
            $scope.data.arr = [];
          } else {
            date = myFactory.getData.date;
            room = myFactory.getData.room.c_id;
          }
        }
      }
      // Call api with given date, room, and reservation id
      restapi.times(date, room, resId).then(
        (res) => {
          console.log(res)
          $scope.loading = false;
          // Store user information from php passed object 
          // php object recieved from wp_localized_script
          $scope.validTimes = res.times;
          $scope.reservations = res.reservations;
          $scope.rooms = res.rooms;
          myFactory.setReservations(res.reservations);
          console.log($scope.rooms.length)
          console.log(myFactory.getData.arr.length)
          if (myFactory.getData.arr.length > 0) {

            console.log(res.reservations);
            // Use timeout for angular digest and wait for content to load before executing
            $timeout(() => {
              // set room
              myFactory.setRoom(myFactory.getData.room);
              myFactory.setReservations(res.reservations);
              $scope.roomShow = myFactory.getData.room.container_number;
              $scope.resRepeat = myFactory.getData.repeat;
              $scope.validTimes = res.times;
              $scope.reservations = res.reservations;
              console.log(res)
              $scope.rooms = res.rooms;
              $scope.data = myFactory.getData;
              // Call timeout to wait for digest to render times with new $scope data
              $timeout(() => {
                let hourChecks = document.getElementsByName('hours[]');
                $scope.togglePast = function () {
                  $scope.validTimes.forEach((ele) => {
                    if (ele.past != undefined) {
                      ele.past = !ele.past;
                    }
                  })
                }
                if (moment().isAfter(moment.unix(myFactory.getData.arr[0].start_time))) {
                  $scope.data.arr = [];
                  myFactory.removeData();
                  myFactory.storeInfo();
                  $scope.dt = new Date();
                  $scope.roomShow = $scope.rooms[0];
                  $scope.resRepeat = { id: '0', name: 'No Repeat' };
                  $scope.info = "Selected time is before current date and time";
                  return;
                }
                // If user is not editing or has no times selected
                else if (myFactory.getData.arr.length > 0 && resId < 0) {
                  if (myFactory.getData.arr.length === 1) {
                    // Call timeout to wait for digest one more time
                    $timeout(function () {
                      if (resId == -1) {
                        hourChecks[myFactory.getData.arr[0].place].checked = true;
                        $scope.validTimes[myFactory.getData.arr[0].place].selected = true;
                        $scope.validTimes[myFactory.getData.arr[0].place].available = true;
                        angular.element('#hours_' + myFactory.getData.arr[0].place).parent().parent().addClass('selected');
                      } else {
                        hourChecks[res.selectedTimes[0]].checked = true;
                        $scope.validTimes[res.selectedTimes[0]].selected = true;
                        $scope.validTimes[res.selectedTimes[0]].available = true;
                        angular.element('#hours_' + res.selectedTimes[0]).parent().parent().addClass('selected');
                      }
                    });
                  } else {
                    // Call time out to wait for digest one more time
                    $timeout(() => {
                      // Store the points to check off on the UI
                      let startPoint, endPoint;
                      if (resId == -1) {
                        startPoint = myFactory.getData.arr[0].place;
                        endPoint = myFactory.getData.arr[$scope.data.arr.length - 1].place;
                      } else {
                        startPoint = res.selectedTimes[0];
                        endPoint = res.selectedTimes[res.selectedTimes.length - 1];
                      }
                      // Loop through array and select all relevant boxes
                      for (var s = startPoint, e = endPoint; s <= e; s++) {
                        if ($scope.validTimes[s].available) {
                          hourChecks[s].checked = true;
                          $scope.validTimes[s].selected = true;
                          $scope.validTimes[s].available = true;
                          angular.element('#hours_' + s).parent().parent().addClass('selected');
                        }
                      }
                    });
                  }
                } else if (myFactory.getData.arr.length == 0) {
                  $scope.info = "Error getting information";
                  return;
                } else {
                  let i;

                  // Call timeout to wait for digest
                  $timeout(() => {
                    console.log(res);
                    for (i = 0; i < res.selectedTimes.length; i++) {
                      hourChecks[res.selectedTimes[i]].checked = true;
                      $scope.validTimes[res.selectedTimes[i]].selected = true;
                      $scope.validTimes[res.selectedTimes[i]].available = true;
                      angular.element('#hours_' + res.selectedTimes[i]).parent().parent().addClass('selected');
                    }

                  });
                }
              }, 0);



              $scope.resRepeat = myFactory.getData.repeat;
              $scope.roomShow = myFactory.getData.room.container_number;
            }, 0);
          }
          // If query parameter res_id does not exist, then reset room info and valid times
          else if ($scope.rooms.length > 0 && !$location.search().hasOwnProperty("res_id") || myFactory.getData.arr.length == 0) {
            console.log($scope.rooms);
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
            } else {
              $scope.roomShow = myFactory.getData.room.container_number;
              $scope.togglePast = function () {
                $scope.validTimes.forEach((ele) => {
                  if (ele.past != undefined) {
                    ele.past = !ele.past;
                  }
                })
              }
              $scope.resRepeat = myFactory.getData.repeat;
            }
          } else {
          }
          // Sets submit button disable feature
          $scope.isDisabled = false;

        },
        (err) => {
          $scope.loading = false;
          console.error(err);
        }
      );
      //document.getElementById('booking-view').classList.remove('loading-view')
    }

    // Call init function 
    init();

    // Change the factory repeat when user changes the dropdown
    $scope.selectDataChange = function () {
      $scope.data.repeat = $scope.resRepeat;
      myFactory.setRepeat($scope.resRepeat)
    }

    // Set the factory date based on what the user selects on the calendar
    $scope.changeDate = function () {
      // I the same data is selected, do nothing
      if (
        moment(myFactory.getData.date).isSame(moment($scope.dt)) ||
        moment($scope.data.date).isSame(moment($scope.dt))
      ) {
        if ($scope.info) {
          $scope.info = null;
          $timeout(() => {
            $scope.info = "Date already selected";
          }, 300)
        } else {
          $scope.info = "Date already selected";
        }
        return;
      } else {
        $scope.info = null
        myFactory.setDate($scope.dt);
        $scope.loading = true;
        // make api call with new date in mind
        restapi.times(new Date($scope.dt), myFactory.getData.room.c_id).then(
          (res) => {
            // change valid times and reset selected times 
            $scope.validTimes = res.times;
            $scope.reservations = res.reservations;
            console.log(res)
            $scope.data.arr = [];
            $scope.loading = false;
            myFactory.setReservations(res.reservations);
          },
          (err) => {
            console.error(err)
            $scope.loading = false;
            if ($scope.info) {
              $scope.info = null;
              $timeout(() => {
                $scope.info = "Error switching dates";
              }, 300);
            } else {
              $scope.info = "Error switching dates";
            }
          }
        )
      }

    }

    /**
     * @params idx int 
     */
    $scope.selectRoom = function (idx) {
      // Check if data in factory is same as selected room and do nothing
      if (myFactory.getData.room.c_id == $scope.rooms[idx].c_id) {
        if ($scope.info) {
          $scope.info = null;
          $timeout(() => {
            $scope.info = "Room already selected";
          }, 300);
        } else {
          $scope.info = "Room already selected";
        }
        return;
      }
      $scope.info = null;
      // Set room in factory
      myFactory.setRoom($scope.rooms[idx]);
      // Set info that is displayed to users
      $scope.roomShow = $scope.rooms[idx].container_number;
      $scope.loading = true;
      // Make api call with new room check
      restapi.times(new Date($scope.dt), $scope.rooms[idx].c_id).then(
        (res) => {
          $scope.loading = false;
          // change valid times and reset selected times 
          $scope.validTimes = res.times;
          $scope.reservations = res.reservations;
          console.log(res);
          $scope.data.arr = [];
          myFactory.setReservations(res.reservations);
          // Collapse room selection box
          $scope.isCollapsed = false;
        },
        (err) => {
          console.error(err);
          $scope.loading = false;
          if ($scope.info) {
            $scope.info = null;
            $timeout(() => {
              $scope.info = "There is an issue";
            }, 300)
          } else {
            $scope.info = "There is an issue";
          }
        }
      )

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
          if (boxArr.indexOf(t) > -1) {
            console.log(t)
            console.log("Index: " + boxArr.indexOf(t))
            boxArr.splice(boxArr.indexOf(t), 1);
          }
          $scope.validTimes[t].selected = false;
          hourChecks[t].checked = false;
          angular.element('#hours_' + t).parent().parent().removeClass('selected');
        }
        console.log(boxArr)
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
      } else {
        $scope.data.arr = [];
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
      // If the user is editing, store date as given time
      if ($location.search().hasOwnProperty("res_id") || myFactory.getData.arr.length > 0) {
        date = new Date(myFactory.getData.date);
      }
      // Store date for calendar and 
      $scope.dt = date;
      $scope.data.date = date;
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
      $scope.loading = true;
      // Filter results of user data to all avialable times
      let info = $scope.validTimes.filter((item) => {
        if (item.selected === true && item.available) {
          return true;
        }
        return false;
      })
      if (info.length < 1) {
        $scope.info = "No times selected";
        return;
      }
      myFactory.getData.seperateIndexes = [0];
      for (let i = info[0].place, j = 0; j < info.length; i++ , j++) {
        if (info[j].place != i) {
          myFactory.getData.isSeperate = 1;
          myFactory.getData.seperateIndexes.push(j)
          i = info[j].place;
        }
      }
      // console.log(myFactory.getData);
      // Check if times field is stores
      if (info.length > 0) {
        // Disable submit button
        $scope.isDisabled = true;
        // Store infor in factory
        myFactory.setArr(info);
        myFactory.setDate($scope.dt);
        myFactory.storeInfo();
        // Send to submit page
        //$location.path('/').search('action', 'submit');
        var urlParams = new URLSearchParams(window.location.search);
        let id = urlParams.get("res_id");
        if (id) {
          window.location.href = localized.path + '/submit-page?res_id=' + id;
        } else {
          window.location.href = localized.path + '/submit-page';
        }
      }
    }

  }])

  .controller('SubmitForm', function ($scope, $http, myFactory, $uibModal, restapi) {
    if (myFactory.getData.arr.length == 0) {
      window.location.href = localized.path + "/members";
      //$location.path('/');
    } else {
      // Store factory data in scope
      $scope.data = myFactory.getData;
      // Store username data
      $scope.username = localized.username.data.display_name;
      $scope.isDisabled = false;
      $scope.error = {
        desc: '',
        numAttend: -1
      };
      $scope.submitting = false;
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
      $scope.invalidDates = [];
      $scope.validDates = [];
      $scope.modal = null;
      // Check if data time length is greater than 1
      if ($scope.data.repeat.id > 0) {
        $scope.loading = true;
        // Store valid dates if repeat is selecteds
        restapi.checkRepeatReservations($scope.data.arr[0].start_time, $scope.data.arr[$scope.data.arr.length - 1].end_time, $scope.data.room.c_id, $scope.data.repeat).then(
          (data) => {
            let reservations = data;
            myFactory.setMultipleDates(reservations.validDates);
            $scope.invalidDates = reservations.invalidDates;
            $scope.validDates = reservations.validDates;
            $scope.loading = false;
            $scope.$apply();
          }
        )
      }
    }

    $scope.open = function () {
      // Get the modal
      $scope.modal = document.getElementById("myModal");
      // When the user clicks on the button, open the modal
      $scope.modal.style.display = "block";
    };
    /**
     * Submits form
     */
    $scope.submit = function () {
      $scope.submitting = true;
      console.log($scope.info)
      // Check if user data fields are added
      if ($scope.info.desc &&
        $scope.info.desc.length > 0 &&
        $scope.info.numAttend &&
        $scope.info.numAttend > 0 &&
        $scope.info.numAttend <= myFactory.getData.room.occupancy
      ) {
        console.log(myFactory.getData)
        $scope.error = {
          desc: '',
          numAttend: -1
        };
        // Disbale submit 
        $scope.isDisabled = true;
        // set factory data
        myFactory.setNumAttend($scope.info.numAttend);
        myFactory.setDesc($scope.info.desc);
        myFactory.storeInfo();
        var urlParams = new URLSearchParams(window.location.search);
        let id = urlParams.get("res_id");
        // Check if res_id is not selected and make new api call
        if (!id) {
          // Make api call to book room
          $http.post(localized.path + '/wp-json/dsol-booking/v1/bookRoom', myFactory.getData, { headers: { 'X-WP-Nonce': localized.nonce } }).then(
            (res) => {
              $scope.submitting = false;
              console.log(res);
              // remove data from factory
              myFactory.removeData();
              $scope.open();

              setTimeout(() => {
                $scope.modal.style.display = "none";
              }, 1000)
              window.location.href = localized.path + "/members";
              // send page to confirmation
              //$location.path('/').search('action', 'confirmation');
            }, (err) => {
              $scope.info = "There was an error with the booking. Please try again or contact the admin.";
              $scope.submitting = false;
            }
          )
        } else {
          $scope.submitting = false;
          // Add edit route
          myFactory.getData.res_id = parseInt(id);
          // Make edit call
          $http.post(localized.path + '/wp-json/dsol-booking/v1/editUserReservation', myFactory.getData, { headers: { 'X-WP-Nonce': localized.nonce } }).then(
            (res) => {
              $scope.submitting = false;
              myFactory.removeData();
              alert("Successfully booked!");
              window.location.href = localized.path + "/members";
              $location.path('/?action=confirmation');
            }, (err) => {
              $scope.info = "There was an error with the booking. Please try again or contact the admin.";
              $scope.submitting = false;
              myFactory.setRoom($scope.data.room);
            }
          )
        }
      } else {
        console.log($scope.info.desc.length)
        $scope.submitting = false;
        if ($scope.info.desc.length == 0) {
          $scope.error.desc = "Please include a description";
        } else {
          $scope.error.desc = "";
        }
        if (
          $scope.info.numAttend <= 0
        ) {
          $scope.error.numAttend = "Please include a valid attendance number";
        } else {
          $scope.error.numAttend = "";
        }
        console.log($scope.error)
      }
    }
  })

  .controller('profile', function ($scope, restapi, $location, myFactory) {
    // Set user 
    myFactory.setUser(localized.username);
    // Store user in local scope
    $scope.user = localized.username;
    restapi.getUserReservations().then(
      (res) => {
        console.log(res.data)
        // Loop through returned data and transform each start and end date to unix
        res.data.forEach((el, idx) => {
          /**
           * Need to update and check fo multiple columns
           */
          el.time.forEach((time) => {
            time.start_time = new Date(time.start_time).getTime() / 1000;
            time.end_time = new Date(time.end_time).getTime() / 1000;
          })
        });
        $scope.data = res.data;
        console.log($scope.data)
        // Store current res_id from checkboxes
        $scope.items = [];
        // Set lastCheck for editRes info
        $scope.lastCheck; 
      },
      (err) => {
        console.error(err)
      }
    );



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
      let tempEditUserTime = $scope.data[$scope.lastCheck];
      // Set factory information and store information
      myFactory.setArr(tempEditUserTime.time);
      myFactory.setRoom(tempRoom);
      myFactory.setDate(new Date($scope.data[$scope.lastCheck].time[0].start_time * 1000));
      myFactory.setNumAttend(parseInt($scope.data[$scope.lastCheck].attendance));
      myFactory.setRepeat({ id: '0', name: 'No Repeat' });
      myFactory.setDesc($scope.data[$scope.lastCheck].notes);
      myFactory.setReservations($scope.data);
      myFactory.getData.t_id = parseInt($scope.data[$scope.lastCheck].t_id);
      myFactory.storeInfo();

      // Remove action info and semd to book page
      window.location.href = localized.path + "/members?res_id=" + $scope.items[0];
      //$location.path('/').search('res_id', $scope.items[0]);
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
        isSeperate: 0
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
        invalidDates = [],
        returnedReservations = [],
        timeSlots = [];
      const curDate = moment.unix(new Date().getTime() / 1000);
      const m = moment.unix(startTime);
      const tempDate = moment.unix(endTime);
      const checkDate = moment(new Date()).add('1', 'months');
      // Check id of repeat
      switch (repeatType.id) {

        // if daily
        case "1":
          return new Promise((resolve, reject) => {
            this.getReservationByDate(m, myFactory.getData.room.c_id, 1).then(
              (res) => {
                // Loop through each promise data
                for (let k = 0; k < data.length; k++) {
                  let canAdd = true;
                  // Check if data for each selected day has any reservations
                  if (data[k].length == 0) {
                    canAdd = true;
                  } else {
                    for (let i = 0; i < data[k].length; i++) {
                      // Store individual reservation
                      let reservation = data[k][i];

                      // Check if time is stored in multiple sections
                      if (reservation.time.length > 1) {
                        for (let j = 0; j < reservation.time.length; j++) {
                          //console.log('ere')
                          //console.log(checkTimes(myFactory.getData.arr, reservation.time[j]))
                          if (j == reservation.time.length - 1) {
                            //BOOKMARK
                            if (
                              // Check if m is the same day as starttime, 
                              // room is came, m is between start and end time
                              // and tempDate is between the start and end time
                              // set can Add to false
                              moment(timeSlots[k] * 1000).isSame(moment(reservation.time[j].start_time), 'day') &&
                              checkTimes(myFactory.getData.arr, reservation.time[j]) &&
                              reservation.c_id == room
                            ) {
                              canAdd = false;
                              break;
                            } else {
                              // Set flag to true
                              canAdd = true;
                            }
                          } else {

                          }
                        }
                      } else {
                        if (
                          // Check if m is the same day as starttime, 
                          // room is came, m is between start and end time
                          // and tempDate is between the start and end time
                          // set can Add to false
                          moment(timeSlots[k] * 1000).isSame(moment(reservation.time[0].start_time), 'day') &&
                          checkTimes(myFactory.getData.arr, reservation.time[0]) &&
                          reservation.c_id == room
                        ) {
                          canAdd = false;
                          break;
                        } else {
                          // Set flag to true
                          canAdd = true;
                        }

                      }
                    }
                  }
                  if (!canAdd) {
                    invalidDates.push(timeaSlots[k]);
                  } else {
                    validDates.push(timeSlots[k]);
                  }
                  isDone = true;
                }
              }
            )
            resolve({ "validDates": validDates, "invalidDates": invalidDates })
          }), (err) => {
            console.log(err);
            reject({ 'error': true })
          };
          break;

        // Check weekly
        case "2":
          returnedReservations = [];
          timeSlots = [];
          // Loop while m is in same month
          for (m; m.isSame(curDate, "month") || (m.isSame(checkDate, 'month') && m.date() <= 7); m.add('1', "weeks")) {
            // Add promise returned from function call to array
            returnedReservations.push(this.getReservationByDate(m, myFactory.getData.room.c_id))
            // Add the current time sent to function call
            timeSlots.push(m.unix());
            continue;
          }
          console.log(myFactory.getData)
          // Execute all promises from arr variable
          return Promise.all(returnedReservations).then(
            (data) => {
              //console.log("data length: " + data.length)
              // Loop through each promise data
              for (let k = 0; k < data.length; k++) {
                let canAdd = true;
                // Check if data for each selected day has any reservations
                if (data[k].length == 0) {
                  canAdd = true;
                } else {
                  for (let i = 0; i < data[k].length; i++) {
                    // Store individual reservation
                    let reservation = data[k][i];

                    // Check if time is stored in multiple sections
                    if (reservation.time.length > 1) {
                      for (let j = 0; j < reservation.time.length; j++) {
                                                //console.log('ere')
                        //console.log(checkTimes(myFactory.getData.arr, reservation.time[j]))
                        if (j == reservation.time.length - 1) {
                          //BOOKMARK
                          if (
                            // Check if m is the same day as starttime, 
                            // room is came, m is between start and end time
                            // and tempDate is between the start and end time
                            // set can Add to false
                            moment(timeSlots[k] * 1000).isSame(moment(reservation.time[j].start_time), 'day') &&
                            checkTimes(myFactory.getData.arr, reservation.time[j]) &&
                            reservation.c_id == room
                          ) {
                            canAdd = false;
                            break;
                          } else {
                            // Set flag to true
                            canAdd = true;
                          }
                        } else {

                        }
                      }
                    } else {
                      if (
                        // Check if m is the same day as starttime, 
                        // room is came, m is between start and end time
                        // and tempDate is between the start and end time
                        // set can Add to false
                        moment(timeSlots[k] * 1000).isSame(moment(reservation.time[0].start_time), 'day') &&
                        checkTimes(myFactory.getData.arr, reservation.time[0]) &&
                        reservation.c_id == room
                      ) {
                        canAdd = false;
                        break;
                      } else {
                        // Set flag to true
                        canAdd = true;
                      }

                    }
                  }
                }
                if (!canAdd) {
                  invalidDates.push(timeSlots[k]);
                } else {
                  validDates.push(timeSlots[k]);
                }
                isDone = true;
              }
              return { "validDates": validDates, "invalidDates": invalidDates };
            }



          )
        case "3":
          returnedReservations = [];
          timeSlots = [];
          // Loop while m is in same month
          for (m; m.isSame(curDate, "month") || (m.isSame(checkDate, 'month') && m.date() <= 7); m.add('2', "weeks")) {
            // Add promise returned from function call to array
            returnedReservations.push(this.getReservationByDate(m, myFactory.getData.room.c_id))
            // Add the current time sent to function call
            timeSlots.push(m.unix());
            continue;
          }
          //console.log(myFactory.getData)
          // TODO - Double check this logic (variable m might be wrong place)
          // Execute all promises from arr variable
          return Promise.all(returnedReservations).then(
            (data) => {
              // Loop through each promise data
              for (let k = 0; k < data.length; k++) {
                let canAdd = true;
                // Check if data for each selected day has any reservations
                if (data[k].length == 0) {
                  canAdd = true;
                } else {
                  for (let i = 0; i < data[k].length; i++) {
                    // Store individual reservation
                    let reservation = data[k][i];

                    // Check if time is stored in multiple sections
                    if (reservation.time.length > 1) {
                      for (let j = 0; j < reservation.time.length; j++) {
                                                //console.log('ere')
                        //console.log(checkTimes(myFactory.getData.arr, reservation.time[j]))
                        if (j == reservation.time.length - 1) {
                          //BOOKMARK
                          if (
                            // Check if m is the same day as starttime, 
                            // room is came, m is between start and end time
                            // and tempDate is between the start and end time
                            // set can Add to false
                            moment(timeSlots[k] * 1000).isSame(moment(reservation.time[j].start_time), 'day') &&
                            checkTimes(myFactory.getData.arr, reservation.time[j]) &&
                            reservation.c_id == room
                          ) {
                            canAdd = false;
                            break;
                          } else {
                            // Set flag to true
                            canAdd = true;
                          }
                        } else {

                        }
                      }
                    } else {
                      if (
                        // Check if m is the same day as starttime, 
                        // room is came, m is between start and end time
                        // and tempDate is between the start and end time
                        // set can Add to false
                        moment(timeSlots[k] * 1000).isSame(moment(reservation.time[0].start_time), 'day') &&
                        checkTimes(myFactory.getData.arr, reservation.time[0]) &&
                        reservation.c_id == room
                      ) {
                        canAdd = false;
                        break;
                      } else {
                        // Set flag to true
                        canAdd = true;
                      }

                    }
                  }
                }
                if (!canAdd) {
                  invalidDates.push(timeSlots[k]);
                } else {
                  validDates.push(timeSlots[k]);
                }
                isDone = true;
              }

              //console.log(validDates)
              return { "validDates": validDates, "invalidDates": invalidDates };
            }



          )
        default:

          break;
      }
      return { "validDates": validDates, "invalidDates": invalidDates };
    }

    /**
     * 
     * @param {Moment} date 
     * @param {int} room 
     * @param {boolean} daily 
     * 
     * @returns Promise<Array>
     * 
     * API call to server to retrieve information about reservations
     */
    function getReservationByDate(date, room, daily) {
      var givenDate = new Date();
      let isDaily = -1;
      if (daily) {
        isDaily = daily;
      }
      // If the current date is not the same as the date provided, set the given Date to the date arg
      if (!moment().isSame(date)) {
        givenDate = date;
      }
      if (room) {
        room = room;
      } else {
        room = -1;
      }
      // console.log("Date: " + moment(date).format("YYYY/MM/DD"))
      // console.log("Given Date: " + new Date(givenDate))
      let newdate = moment(givenDate).format("YYYY/MM/DD");
      // console.log("New Date: " + newdate)
      let reservations;
      return $http.post(localized.path + '/wp-json/dsol-booking/v1/getReservationByDate',
        { room: room, date: newdate, isDaily: isDaily }, { headers: { 'X-WP-Nonce': localized.nonce } }).then(
          (res) => {
            console.log(res)
            return res.data;
          }
        );

    }

    /**
     * 
     * @param {*} selectedTimes 
     * @param {*} reservationTime 
     */
    function checkTimes(selectedTimes, reservationTime) {

      let result = false;
      let resStartTime = new Date(reservationTime.start_time);
      let resEndTIme = new Date(reservationTime.end_time);
      for (let i = 0; i < selectedTimes.length; i++) {
        let timeToBeCheckedStartTime = new Date(selectedTimes[i].start_time * 1000);
        let timeToBeCheckedEndTime = new Date(selectedTimes[i].end_time * 1000);
        if (
          timeToBeCheckedStartTime.getHours() >= resStartTime.getHours() &&
          timeToBeCheckedStartTime.getHours() <= resEndTIme.getHours() &&
          timeToBeCheckedStartTime.getMinutes() >= resStartTime.getMinutes() &&
          timeToBeCheckedStartTime.getMinutes() <= resEndTIme.getMinutes() &&
          timeToBeCheckedEndTime.getHours() >= resStartTime.getHours() &&
          timeToBeCheckedEndTime.getHours() <= resEndTIme.getHours() &&
          timeToBeCheckedEndTime.getMinutes() >= resStartTime.getMinutes() &&
          timeToBeCheckedEndTime.getMinutes() <= resEndTIme.getMinutes()
        ) {
          result = true;
        } else {
          result = false;
        }
      }
      console.log(result)
      return result;
    }


    /**
     * 
     * @param {*} date 
     * @param {*} room 
     * @param {*} resid 
     */
    function getTimes(date, room, resid) {
      // Store current time in givenDate variable
      var givenDate = new Date();
      // Store room and resid in variables
      var room, residEdit = -1;

      // If room is valid, then set the value 
      if (room) {
        room = room;
      } else {
        room = -1;
      }

      // If resid is valid, then set the value
      if (resid && resid > -1) {
        residEdit = resid;
      }
      // If the current date is not the same as the date provided, set the given Date to the date arg
      if (!moment().isSame(date)) {
        givenDate = date;
      }
      //console.log("Date: " + moment(date).format("YYYY/MM/DD"))
      //console.log("Given Date: " + new Date(givenDate))
      let newdate = moment(givenDate).format("YYYY/MM/DD");
      //console.log("New Date: " + newdate)
      return $http.post(localized.path + '/wp-json/dsol-booking/v1/test', { room: room, date: newdate }, { headers: { 'X-WP-Nonce': localized.nonce } }).then(
        (res) => {
          return $http.post(localized.path + '/wp-json/dsol-booking/v1/getRoomInfo').then(
            (roomData) => {
              let rooms = roomData.data;
              let reservations = res.data;
              let validTimes = [];
              // res id if the user is editing a reservation
              let selectedresIdx = [];
              let j = 0;
              // Holds current date at 6:00 AM start time
              const curDate = moment(givenDate).hours(6).minute(0).seconds(0).milliseconds(0);
              // Holds current date at start time and will represent 30 minutes after current time
              const tempDate = moment(givenDate).hours(6).minute(0).seconds(0).milliseconds(0);
              // Store the current Date of system to check against curDate in loop
              const currentSysDate = moment();
              if (room < 0) {
                room = rooms[0].c_id;
              }

              // Flag set in loop below to determine if time slot has a reservation
              let hasAdded = false;
              for (let m = curDate; m.isSame(givenDate, "day"); m.add(30, 'minutes')) {

                tempDate.add(30, "minutes");
                hasAdded = false;
                if (reservations.length === 0 && m.isSameOrAfter(currentSysDate, "minute")) {
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
                } else {
                  if (m.isSameOrAfter(currentSysDate, "minute")) {
                    /**
                     * Loop through reservations and check if time is avilable
                     * If it is in reesrvation, flip boolean and add reservation idx
                     */

                    reservations.forEach((el, idx) => {
                      /*                     if (resid && resid > -1 && el.res_id == residEdit) {
                                            console.log(m.format())
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
                                          } */
                      /*
                        if (m.isSame(el.start_time, "day") && m.isBetween(moment(el.start_time).subtract(1, "m"), moment(el.end_time).subtract(1, "m"))
                          && el.c_id == room
                        )
                        */
                      if (!hasAdded && el.time) {
                        if (el.time.length > 1) {
                          for (let resTime = 0; resTime < el.time.length; resTime++) {
                            let resCurTime = el.time[resTime];

                            if (moment(resCurTime.start_time) != undefined && m.isSame(resCurTime.start_time, "day") && m.isBetween(moment(resCurTime.start_time).subtract(1, "m"), moment(resCurTime.end_time).subtract(1, "m"))
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
                              if (resid == el.res_id) {

                                selectedresIdx.push(j);
                              }
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
                            if (resid == el.res_id) {
                              selectedresIdx.push(j);
                            }
                            hasAdded = true;
                          }
                        }
                      }
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
                    // If the time is before the current time and the day is the same
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
                        if (el.time && el.time.length > 1) {
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
                              if (resid > -1) {
                                selectedresIdx.push(j);
                              }
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
                            if (resid > -1) {
                              selectedresIdx.push(j);
                            }
                            hasAdded = true;
                          }
                        }
                      });
                      if (!hasAdded && m.isSame(Date.now(), "day")) {
                        validTimes.push({
                          //start_time: m.format('h:mm ss A'),
                          //end_time: tempDate.format('h:mm ss A'),
                          start_time: m.unix(),
                          end_time: tempDate.unix(),
                          available: true,
                          place: j,
                          selected: false,
                          past: true
                        });
                      } else if (!hasAdded && !m.isSame(Date.now(), "day")) {
                        validTimes.push({
                          //start_time: m.format('h:mm ss A'),
                          //end_time: tempDate.format('h:mm ss A'),
                          start_time: m.unix(),
                          end_time: tempDate.unix(),
                          available: true,
                          place: j,
                          selected: false,
                        });
                      }
                    }
                    hasAdded - false;
                  }
                }
                // Safety net in case moment calculates date wrong 
                if (j > 40) {
                  break;
                }
                j++;
              }
              if (resid > -1) {
                return {
                  times: validTimes,
                  reservations: reservations,
                  rooms: rooms,
                  selectedTimes: selectedresIdx
                };
              } else {
                return {
                  times: validTimes,
                  reservations: reservations,
                  rooms: rooms
                };
              }

            }, (err) => {
              console.error(err);
            });

        }, (err) => {
          console.error(err);
        });
    }

    function getUserReservations() {
      console.log(localized)
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
      getReservationByDate: getReservationByDate,
      checkRepeatReservations: checkRepeatReservations,
      checkValidUser: checkValidUser,
      getUserReservations: getUserReservations,
      editUserReservation: editUserReservation,
      deleteUserReservation: deleteUserReservation
    };

    return service;
  })