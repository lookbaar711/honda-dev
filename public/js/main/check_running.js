var check_running = {
  check_running : function (running) {
    var check = (running).split("TB");
    var run = '';
    var number = ['0', '1', '2','3', '4', '5','6', '7', '8','9'];

    if (check.length == 1) { //NORMAL
      run = check[0];
    }else {
      run = check[1];
    }

    var check_run = run.split("");
    if (check_run.length == 8) {
      var check_all = check_run.concat(number);
      var res = check_running.find_duplicate_in_array(check_all);
      // console.log(res);
      var count = 0;
      res.forEach(function (item) {
        count += item;
      })
      // console.log(count);
      if (count != 8) {
        Swal.fire({
          type: 'error',
          title: 'กรุณาตรวจสอบข้อมูลเลขที่ใบจอง',
        })
        return false;
      }

    }else {
      Swal.fire({
        type: 'error',
        title: 'กรุณาตรวจสอบข้อมูลเลขที่ใบจอง',
      })
      return false;
    }

    return true;
  },
  find_duplicate_in_array : function(data) {
    var object = {};
    var result = [];

    data.forEach(function (item) {
      if(!object[item])
          object[item] = 0;
          object[item] += 1;
    })

      for (var prop in object) {
         if(object[prop] >= 2) {
             result.push(((object[prop]*1)-1));
         }
      }

    return result;
  },
  size : function(obj) {
    var size = 0, key;
    for (key in obj) {
        if (obj.hasOwnProperty(key)) size++;
    }
    return size;
  },
  find_duplicate_array : function(data) {
    var object = {};
    var result = [];

    data.forEach(function (item) {
      if(!object[item])
          object[item] = 0;
          object[item] += 1;
        })

      for (var prop in object) {
         if(object[prop] >= 2) {
             result.push(prop);
         }
      }

    return result;
  },
  hidden_fil_dataTable : function(dataTableName) {
    document.getElementById(dataTableName+'_filter').setAttribute( "style", "display: none" );
    document.getElementById(dataTableName+'_length').setAttribute( "style", "display: none" );
    document.getElementById(dataTableName+'_info').setAttribute( "style", "display: none" );
  }
}
