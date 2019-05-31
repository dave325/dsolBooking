angular.module('wp', ['ngRoute', 'ui.bootstrap', 'ngAnimate'])
  .config(function ($routeProvider, $locationProvider) {
    $routeProvider
      .when('/', {
        templateUrl: localized.partials + '/showRooms.html',
        controller: 'Main',
        resolve: {
          TIMES: ['restapi', function (restapi) {
            return restapi.times(Date.now()).then(
              (res) => {
                //console.log(res);
                return res;
              },
              (err) => {
                console.log(err);
              }
            );
          }]
        }
      })
      .when('/submit', {
        templateUrl: localized.partials + '/submitForm.html',
        controller: 'SubmitForm'
      })
      .when('/profile', {
        templateUrl: localized.partials + '/profile.html',
        controller: 'profile'
      })
      .when('/confirmation', {
        templateUrl: localized.partials + '/confirmation.html'
      })
  })
  .controller('Main', function ($scope, TIMES, $timeout, myFactory, $location, restapi) {
    $scope.oneAtATime = true;
    $scope.validTimes = TIMES.times;
    $scope.reservations = TIMES.reservations;
    $scope.rooms = TIMES.rooms;

    $scope.isCollapsed = false;
    //console.log($scope.validTimes);
    $scope.data = myFactory.getData;
    $scope.selectData = {
      availableOptions: [
        { id: '0', name: 'No Repeat' },
        { id: '1', name: 'Daily' },
        { id: '2', name: 'Weekly' },
        { id: '3', name: 'Biweekly' }
      ]
    };
    myFactory.setReservations(TIMES.reservations);
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

    }
    $scope.isDisabled = false;

    $scope.selectDataChange = function () {
      $scope.data.repeat = $scope.resRepeat;
      myFactory.setRepeat($scope.resRepeat)
    }

    $scope.$watch('dt', function () {
      if (moment(myFactory.getData.date).isSame(moment($scope.dt))) {
        console.log("same");
      } else {
        myFactory.setDate($scope.dt);
        restapi.times(new Date($scope.dt)).then(
          (res) => {
            // console.log(res.times);
            $scope.validTimes = res.times;
            $scope.data.arr = [];
          },
          (err) => {
            console.log(err);
          }
        )
      }
    })

    $scope.selectRoom = function (idx) {
      if (myFactory.getData.room.c_id == $scope.rooms[idx].c_id) {
        console.log("same room");
        return;
      }
      myFactory.setRoom($scope.rooms[idx]);
      $scope.roomShow = $scope.rooms[idx].container_number;
      restapi.times(new Date($scope.dt), $scope.rooms[idx].c_id).then(
        (res) => {
          //console.log(res.times);
          $scope.validTimes = res.times;
          $scope.data.arr = [];
        },
        (err) => {
          console.log(err);
        }
      )
      $scope.isCollapsed = true;
      console.log($scope.data);
    }
    $scope.check = function (obj) {
      /* are there only two checked boxes? */
      //alert();
      var hourChecks = document.getElementsByName('hours[]');
      var boxArr = [];
      var boxCount = 0;
      var lastItem = false;
      const curChecked = obj.place;
      for (var t = 0, checkLength = hourChecks.length; t < checkLength; t++) {
        if ((hourChecks[t].type == 'checkbox') && hourChecks[t].checked == true) {
          boxArr[boxCount++] = t;
          //console.log("New Box: " + t)
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
        angular.element('#hours_' + t).parent().parent().removeClass('selected');
      } else if (hourChecks[curChecked].checked == false) {

        console.log(boxArr)
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
          angular.element('#hours_' + t).parent().parent().addClass('selected');
          chkstat = false;
        }
        // are there multiple and this is the first? just uncheck it
      } else if (boxArr.length > 1) {
        for (var s = boxArr[0] + 1, e = boxArr[boxArr.length - 1]; s < e; s++) {
          var curHour = document.getElementById('hours_' + s);
          if (curHour.value == false) {
            hourChecks[curChecked].checked = false;
            $scope.validTimes[curChecked].selected = false;
            angular.element('#hours_' + s).parent().parent().removeClass('selected');
            alert("Error!\nI'm sorry, but there is already a reservation in the time you've selected. Please make sure your reservation times don't overlap someone else's reservation.");
            break;
          } else {
            hourChecks[s].checked = true;
            $scope.validTimes[s].selected = true;
            angular.element('#hours_' + s).parent().parent().addClass('selected');
          }
        }
      }
      // If box has more than one item in it display the time 
      if (boxArr.length > 1) {
        console.log(boxArr)

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
      // console.log($scope.data.arr);
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
        if (item.selected === true && item.available) {
          return true;
        }
        return false;
      })
      if (info.length > 0) {
        $scope.isDisabled = true;
        myFactory.setArr(info);
        myFactory.setDate($scope.dt);
        console.log(myFactory.getData);
        myFactory.storeInfo();
        $location.path('/submit');
      }
    }
    //reset boxes if user reloads page
    $scope.$on('$viewContentLoaded',
      function () {
        // call timeout so angularjs digrest waits for the next round after ngRepeat calls
        $timeout(function () {
          if (myFactory.getData.room.c_id) {
            console.log(myFactory.getData);
            myFactory.setRoom(myFactory.getData.room);
            $scope.roomShow = myFactory.getData.room.container_number;
            $scope.resRepeat = myFactory.getData.repeat;
            restapi.times(new Date($scope.dt), myFactory.getData.room.c_id).then(
              (res) => {
                //console.log(res.times);
                $scope.validTimes = res.times;
                $scope.data.arr = [];
                var hourChecks = document.getElementsByName('hours[]');
                $scope.togglePast = function () {
                  $scope.validTimes.forEach((ele) => {
                    if (ele.past != undefined) {
                      ele.past = !ele.past;
                    }
                  })
                }
                if (myFactory.getData.arr.length > 0) {
                  if (myFactory.getData.arr.length === 1) {
                    hourChecks[myFactory.getData.arr[0].place].checked = true;
                    $scope.validTimes[myFactory.getData.arr[0].place].selected = true;
                  } else {
                    for (var s = myFactory.getData.arr[0].place, e = myFactory.getData.arr[$scope.data.arr.length - 1].place; s <= e; s++) {
                      hourChecks[s].checked = true;
                      $scope.validTimes[s].selected = true;

                    }
                  }
                }
              },
              (err) => {
                console.log(err);
              }
            )
          } else {
            var hourChecks = document.getElementsByName('hours[]');
            if ($scope.data.arr.length > 0) {
              if ($scope.data.arr.length === 1) {
                hourChecks[$scope.data.arr[0].place].checked = true;
                $scope.validTimes[$scope.data.arr[0].place].selected = true;
              } else {
                for (var s = $scope.data.arr[0].place, e = $scope.data.arr[$scope.data.arr.length - 1].place; s <= e; s++) {
                  hourChecks[s].checked = true;
                  $scope.validTimes[s].selected = true;

                }
              }
            }
          }
        }, 0)
      }
    );
  }).controller('SubmitForm', function ($scope, $http, myFactory, $location, restapi) {
    $scope.data = myFactory.getData;
    $scope.username = localized.username;
    $scope.isDisabled = false;
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
    console.log(myFactory.getData)
    if ($scope.data.arr.length > 1) {
      myFactory.setMultipleDates(restapi.checkRepeatReservations($scope.data.arr[0].start_time, $scope.data.arr[$scope.data.arr.length - 1].end_time, $scope.data.room.c_id, $scope.data.repeat));
    }else{
      myFactory.setMultipleDates(restapi.checkRepeatReservations($scope.data.arr[0].start_time, $scope.data.arr[0].end_time, $scope.data.room.c_id, $scope.data.repeat));
    }
    console.log(myFactory.getData)
    $scope.submit = function () {
      if ($scope.info.desc &&
        $scope.info.desc.length > 0 &&
        $scope.info.numAttend &&
        $scope.info.numAttend > 0 &&
        $scope.info.numAttend <= myFactory.getData.room.occupancy
      ) {
        $scope.isDisabled = true;
        myFactory.setNumAttend($scope.info.numAttend);
        myFactory.setDesc($scope.info.desc);
        myFactory.storeInfo();
        console.log(myFactory.retrieveInfo());
        $http.post(localized.path + '/wp-json/dsol-booking/v1/bookRoom', myFactory.getData, { headers: { 'X-WP-Nonce': localized.nonce } }).then(
          (res) => {
            console.log(res);
            //myFactory.removeData();
            //$location.path('/confirmation');
          }, (err) => {
            console.log(err);
            console.log($scope.data)
            myFactory.setRoom($scope.data.room);
          }
        )
      } else {

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
        room: {},
        repeat: { id: '0', name: 'No Repeat' },
        reservations: [],
        multipleDates: [],
        nonce: localized.nonce
      };
    }
    var service = {
      getData: data,
      setReservations: setReservations,
      setMultipleDates:setMultipleDates,
      setArr: setArr,
      setDate: setDate,
      setNumAttend: setNumAttend,
      setDesc: setDesc,
      setRoom: setRoom,
      setRepeat: setRepeat,
      storeInfo: storeInfo,
      retrieveInfo: retrieveInfo,
      removeData: removeData
    };
    function setArr(arr) {
      data.arr = arr;
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
        $http.post(localized.path + '/wp-json/dsol-booking/v1/parse', myFactory.retrieveInfo(), { headers: { 'X-WP-Nonce': localized.nonce } }).then(
          (res) => {
            console.log(res);
            myFactory.removeData();
          }, (err) => {
            console.log(err);
          }
        )
      }
    }
  }).factory('restapi', function ($http, myFactory) {
    function checkValidTimes(validTime, time) {
      validTime.forEach((el) => {
        if (moment(el.start_time).isSame(time)) {
          return false;
        }
      });
      return true;
    }

    function checkRepeatReservations(startTime, endTime, room, repeatType) {

      let validDates = [];
      const curDate = moment.unix(startTime)
      const m = moment.unix(startTime)
      const tempDate = moment.unix(endTime)
      console.log(repeatType)
      console.log(room)
      console.log(m.format("dddd, MMMM Do YYYY, h:mm:ss a"))
      console.log(tempDate.format("dddd, MMMM Do YYYY, h:mm:ss a"))
      switch (repeatType.id) {
        case "1":
          while (m.isSame(curDate, "month")) {
            let canAdd = false;
            console.log(myFactory.getData.reservations)
            myFactory.getData.reservations.forEach((el, idx) => {
              if (
                m.isSame(el.start_time, "day") &&
                !m.isBetween(moment(el.start_time).subtract(1, "m"), moment(el.end_time).subtract(1, "m")) &&
                !tempDate.isBetween(moment(el.start_time).subtract(1, "m"), moment(el.end_time).subtract(1, "m")) &&
                el.c_id == room
              ) {
                canAdd = true;
              }else if ( !m.isSame(el.start_time, "day")){
                canAdd = true;
              }else{
                canAdd = false; 
              }

            });
            if(canAdd){
              validDates.push(moment().unix(startTime));
            }
            m.add('1', "days");
          }
          break;
        case "2":
          while (m.isSame(curDate, "month")) {
            reservations.forEach((el, idx) => {
              if (
                m.isSame(el.start_time, "day") &&
                !m.isBetween(moment(el.start_time).subtract(1, "m"), moment(el.end_time).subtract(1, "m")) &&
                !tempDate.isBetween(moment(el.start_time).subtract(1, "m"), moment(el.end_time).subtract(1, "m")) &&
                el.c_id == room
              ) {
                validDates.push(m.unix());
                return;
              }

            });
            m.add('1', "weeks");
          }
          break;
        case "3":
          while (m.isSame(curDate, "month")) {
            reservations.forEach((el, idx) => {
              if (
                m.isSame(el.start_time, "day") &&
                !m.isBetween(moment(el.start_time).subtract(1, "m"), moment(el.end_time).subtract(1, "m")) &&
                !tempDate.isBetween(moment(el.start_time).subtract(1, "m"), moment(el.end_time).subtract(1, "m")) &&
                el.c_id == room
              ) {
                validDates.push(m.unix());
                return;
              }

            });
            m.add('2', "weeks");
          }
          break;
        default:

          break;
      }
      return validDates;
    }
    function getTimes(date, room) {
      var givenDate = new Date();
      var room;
      if (room) {
        room = room;
      } else {
        room = -1
      }
      if (!moment().isSame(date, "day")) {
        givenDate = date;
      }
      const curDate = moment(givenDate).hours(6).minute(0).seconds(0).milliseconds(0);
      const tempDate = moment(givenDate).hours(6).minute(0).seconds(0).milliseconds(0);

      return $http.post(localized.path + '/wp-json/dsol-booking/v1/test', { room: room }, { headers: { 'X-WP-Nonce': localized.nonce } }).then((res) => {
        let reservations = res.data;
        if (room < 0) {
          room = reservations[0].c_id;
        }
        //console.log(res)
        let validTimes = [];
        let j = 0;
        //console.log(curDate.dayOfYear())
        for (var m = curDate; m.isSame(givenDate, "day"); m.add(30, 'minutes')) {
          tempDate.add(30, "minutes");
          let hasAdded = false;
          if (m.isSameOrAfter(givenDate, "minute")) {
            /**
             * Loop through reservations and check if time is avilable
             * If it is in reesrvation, flip boolean and add reservation idx
             */

            reservations.forEach((el, idx) => {
              if (m.isSame(el.start_time, "day") && m.isBetween(moment(el.start_time).subtract(1, "m"), moment(el.end_time).subtract(1, "m"))
                && el.c_id == room
              ) {
                // console.log(m.toString());
                //console.log("Repeat: " +moment(el.start_time).toString());
                validTimes.push({
                  //start_time: m.format('h:mm ss A'),
                  //end_time: tempDate.format('h:mm ss A'),
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
            });
            /**
             * If not in reservations, add to valid times with no idx
             */
            //console.log(hasAdded);
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

                if (m.isSame(el.start_time, "day") && m.isBetween(moment(el.start_time).subtract(1, "m"), moment(el.end_time).subtract(1, "m"))
                  && el.c_id === room
                ) {
                  validTimes.push({
                    //start_time: m.format('h:mm ss A'),
                    //end_time: tempDate.format('h:mm ss A'),
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
        let rooms;
        return $http.post(localized.path + '/wp-json/dsol-booking/v1/getRoomInfo').then((res) => {
          rooms = res.data;
          return {
            times: validTimes,
            reservations: reservations,
            rooms: rooms
          };
        }, (err) => {
          console.log(err);
        });

      }, (err) => {
        console.log(err);
      });
    }
    var service = {
      times: getTimes,
      checkRepeatReservations: checkRepeatReservations
    };

    return service;
  })