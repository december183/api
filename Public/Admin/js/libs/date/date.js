/**
 * [getFormatDate 格式化时间戳]
 * @param  {timestamp} time [时间戳]
 * @return {string}      [格式化好的日期字符串]
 */
function getFormatDate(timestamp=''){
  var time=new Date(timestamp);
  var yearStr=time.getFullYear();
  var monthStr=time.getMonth()+1;
  if(monthStr < 10){
    monthStr='0'+monthStr;
  }
  var dayStr=time.getDate();
  if(dayStr <10){
    dayStr='0'+dayStr;
  }
  return yearStr+''+monthStr+''+dayStr;
}
/**
 * [getPrevDay 根据页面当前日期获取上一天的日期]
 * @param  {[string]} time [当前日期字符串]
 * @return {[string]} yesterday [当前日期上一天的日期字符串]
 */
function getPrevDay(time){
  var year=parseInt(time.substr(0,4));
  var month=parseInt(time.substr(4,2))-1;
  var day=parseInt(time.substr(6,2));
  var datetime=new Date(year,month,day);
  var yesterdayTime=datetime.getTime()-1000*60*60*24;
  var yesterday=new Date(yesterdayTime);
  var yearStr=yesterday.getFullYear();
  var monthStr=yesterday.getMonth()+1;
  if(monthStr < 10){
    monthStr='0'+monthStr;
  }
  var dayStr=yesterday.getDate();
  if(dayStr < 10){
    dayStr='0'+dayStr;
  }
  return yearStr+''+monthStr+''+dayStr;
}
/**
 * [getNextDay 根据页面当前日期获取下一天的日期]
 * @param  {string} time [当前日期字符串]
 * @return {string} tomorrow [当前日期下一天的日期字符串]
 */
function getNextDay(time){
  var year=parseInt(time.substr(0,4));
  var month=parseInt(time.substr(4,2))-1;
  var day=parseInt(time.substr(6,2));
  var datetime=new Date(year,month,day);
  var tomorrowTime=datetime.getTime()+1000*60*60*24;
  var tomorrow=new Date(tomorrowTime);
  var yearStr=tomorrow.getFullYear();
  var monthStr=tomorrow.getMonth()+1;
  if(monthStr < 10){
    monthStr='0'+monthStr;
  }
  var dayStr=tomorrow.getDate();
  if(dayStr <10){
    dayStr='0'+dayStr;
  }
  return yearStr+''+monthStr+''+dayStr;
}
/**
 * [getCurWeek 获取当前一周的日期]
 * @param  {string} time [当前日期字符串]
 * @return {array} curWeek [当前一周的日期字符串数组]
 */
function getCurWeek(time){
  var year=parseInt(time.substr(0,4));
  var month=parseInt(time.substr(4,2))-1;
  var day=parseInt(time.substr(6,2));
  var datetime=new Date(year,month,day);
  var timestamp=datetime.getTime();
  var curWeekDay=datetime.getDay();
  var Mon=Tue=Wed=Thu=Fri=Sat=Sun='';
  var curWeek=[];
  switch(curWeekDay){
    case 0:
      Mon=getFormatDate(timestamp-1000*60*60*24*6);
      Tue=getFormatDate(timestamp-1000*60*60*24*5);
      Wed=getFormatDate(timestamp-1000*60*60*24*4);
      Thu=getFormatDate(timestamp-1000*60*60*24*3);
      Fri=getFormatDate(timestamp-1000*60*60*24*2);
      Sat=getFormatDate(timestamp-1000*60*60*24);
      Sun=time;
      break;
    case 1:
      Mon=time;
      Tue=getFormatDate(timestamp+1000*60*60*24);
      Wed=getFormatDate(timestamp+1000*60*60*24*2);
      Thu=getFormatDate(timestamp+1000*60*60*24*3);
      Fri=getFormatDate(timestamp+1000*60*60*24*4);
      Sat=getFormatDate(timestamp+1000*60*60*24*5);
      Sun=getFormatDate(timestamp+1000*60*60*24*6);
      break;
    case 2:
      Mon=getFormatDate(timestamp-1000*60*60*24);
      Tue=time;
      Wed=getFormatDate(timestamp+1000*60*60*24);
      Thu=getFormatDate(timestamp+1000*60*60*24*2);
      Fri=getFormatDate(timestamp+1000*60*60*24*3);
      Sat=getFormatDate(timestamp+1000*60*60*24*4);
      Sun=getFormatDate(timestamp+1000*60*60*24*5);
      break;
    case 3:
      Mon=getFormatDate(timestamp-1000*60*60*24*2);
      Tue=getFormatDate(timestamp-1000*60*60*24);
      Wed=time;
      Thu=getFormatDate(timestamp+1000*60*60*24);
      Fri=getFormatDate(timestamp+1000*60*60*24*2);
      Sat=getFormatDate(timestamp+1000*60*60*24*3);
      Sun=getFormatDate(timestamp+1000*60*60*24*4);
      break;
    case 4:
      Mon=getFormatDate(timestamp-1000*60*60*24*3);
      Tue=getFormatDate(timestamp-1000*60*60*24*2);
      Wed=getFormatDate(timestamp-1000*60*60*24);
      Thu=time;
      Fri=getFormatDate(timestamp+1000*60*60*24);
      Sat=getFormatDate(timestamp+1000*60*60*24*2);
      Sun=getFormatDate(timestamp+1000*60*60*24*3);
      break;
    case 5:
      Mon=getFormatDate(timestamp-1000*60*60*24*4);
      Tue=getFormatDate(timestamp-1000*60*60*24*3);
      Wed=getFormatDate(timestamp-1000*60*60*24*2);
      Thu=getFormatDate(timestamp-1000*60*60*24);
      Fri=time;
      Sat=getFormatDate(timestamp+1000*60*60*24);
      Sun=getFormatDate(timestamp+1000*60*60*24*2);
      break;
    case 6:
      Mon=getFormatDate(timestamp-1000*60*60*24*5);
      Tue=getFormatDate(timestamp-1000*60*60*24*4);
      Wed=getFormatDate(timestamp-1000*60*60*24*3);
      Thu=getFormatDate(timestamp-1000*60*60*24*2);
      Fri=getFormatDate(timestamp-1000*60*60*24);
      Sat=time;
      Sun=getFormatDate(timestamp+1000*60*60*24);
      break;
  }
  curWeek=[Mon,Tue,Wed,Thu,Fri,Sat,Sun];
  return curWeek;
}