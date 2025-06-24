function clock() {
    var time = new Date(),          
        hours = time.getHours(),    
        minutes = time.getMinutes(),
        seconds = time.getSeconds();
        day = time.getDate();
        month = time.getMonth()+1;
        year = time.getFullYear();
        

    document.querySelectorAll('.clock')[0].innerHTML = harold(hours) + ":" + harold(minutes) + ":" + harold(seconds);
    document.querySelectorAll('.date')[0].innerHTML = harold(day) + "." + harold(month) + "." + harold(year);
    
    function harold(standIn) {
        if (standIn < 10) {
        standIn = '0' + standIn
        }
        return standIn;
    }
    }
    setInterval(clock, 1000); 